<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionRenewalService
{
    /**
     * Check all active subscriptions and auto-renew those whose meal plans
     * have ended or reached their end date, scheduling the new cycle for the day
     * after it ends with the exact same meal pattern.
     *
     * @return array Summary of processed renewals
     */
    public static function processAutoRenewals(): array
    {
        $today = Carbon::today();
        $renewedOrders = [];

        // Find active subscription orders that have auto_renew enabled and not yet renewed
        $subscriptions = Order::with(['items.food', 'user'])
            ->where('is_subscription', true)
            ->where('auto_renew', true)
            ->whereNull('renewed_to_order_id')
            ->whereIn('payment_status', ['paid', 'verified'])
            ->get();

        foreach ($subscriptions as $order) {
            if ($order->items->isEmpty()) {
                continue;
            }

            // Find start and end date of the current subscription
            $sortedItems = $order->items->filter(fn($i) => !empty($i->scheduled_date))->sortBy('scheduled_date');
            if ($sortedItems->isEmpty()) {
                continue;
            }

            $firstItem = $sortedItems->first();
            $lastItem = $sortedItems->last();

            $startDate = Carbon::parse($firstItem->scheduled_date)->startOfDay();
            $endDate = Carbon::parse($lastItem->scheduled_date)->startOfDay();

            // When subscription ends (today >= endDate), renew it for the day after it ends
            if ($today->lt($endDate)) {
                continue;
            }

            $newOrder = self::renewSubscription($order, $startDate, $endDate);
            if ($newOrder) {
                $renewedOrders[] = $newOrder;
            }
        }

        return $renewedOrders;
    }

    /**
     * Renew a single subscription order.
     */
    public static function renewSubscription(Order $order, Carbon $startDate, Carbon $endDate): ?Order
    {
        return DB::transaction(function () use ($order, $startDate, $endDate) {
            // Lock and check again to ensure idempotency
            $freshOrder = Order::lockForUpdate()->find($order->id);
            if (!$freshOrder || $freshOrder->renewed_to_order_id) {
                return null;
            }

            $nextCycleStartDate = (clone $endDate)->addDay()->startOfDay();

            // Calculate duration in days between first and last date
            $cycleDays = $startDate->diffInDays($endDate) + 1;
            $nextCycleEndDate = (clone $nextCycleStartDate)->addDays($cycleDays - 1)->startOfDay();

            $holdCredit = (float)$order->paused_credit_amount;
            $newDiscount = (float)$order->discount_amount + $holdCredit;
            $finalPrice = max(0.0, (float)$order->total_price - $holdCredit);

            // Create new renewal order
            $newOrder = Order::create([
                'order_number' => 'ORD-SUB-' . strtoupper(uniqid()),
                'user_id' => $order->user_id,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'delivery_address' => $order->delivery_address,
                'dinner_address' => $order->dinner_address,
                'address' => $order->address,
                'total_amount' => $finalPrice,
                'total_price' => $finalPrice,
                'status' => 'pending',
                'payment_method' => $order->payment_method ?: 'Card',
                'payment_status' => 'paid', // Recurring subscription card charge
                'payment_reference' => Order::generateReference($order->user_id),
                'is_subscription' => true,
                'renewed_from_order_id' => $order->id,
                'auto_renew' => true,
                'coupon_code' => $order->coupon_code,
                'discount_amount' => $newDiscount,
            ]);

            // Recreate all items with the exact same pattern shifted by dates
            foreach ($order->items as $item) {
                $itemDate = Carbon::parse($item->scheduled_date)->startOfDay();
                $dayOffset = $startDate->diffInDays($itemDate);
                $newScheduledDate = (clone $nextCycleStartDate)->addDays($dayOffset)->toDateString();

                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'food_id' => $item->food_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'meal_type' => $item->meal_type,
                    'scheduled_date' => $newScheduledDate,
                ]);
            }

            // Mark old order as renewed
            $freshOrder->update([
                'renewed_to_order_id' => $newOrder->id,
                'renewed_at' => now(),
            ]);

            // Send in-app notification to user
            try {
                $renewalMsg = $holdCredit > 0
                    ? "Your meal subscription renewed for ({$nextCycleStartDate->format('M d')} – {$nextCycleEndDate->format('M d')}). Rs. " . number_format($holdCredit, 2) . " hold credit was deducted from your invoice!"
                    : "Your meal subscription has automatically renewed for the next cycle ({$nextCycleStartDate->format('M d')} – {$nextCycleEndDate->format('M d')}). Your meals are prepared according to your preferred pattern!";

                UserNotification::create([
                    'user_id' => $order->user_id,
                    'title' => 'Subscription Renewed Automatically 🔄',
                    'message' => $renewalMsg,
                    'type' => 'subscription',
                    'data' => [
                        'order_id' => $newOrder->id,
                        'previous_order_id' => $order->id,
                        'start_date' => $nextCycleStartDate->toDateString(),
                        'end_date' => $nextCycleEndDate->toDateString(),
                        'total_price' => $newOrder->total_price,
                        'hold_credit_applied' => $holdCredit,
                    ],
                    'is_read' => false,
                ]);
            } catch (\Throwable $e) {
                Log::warning("Could not dispatch renewal notification: " . $e->getMessage());
            }

            return $newOrder;
        });
    }

    /**
     * Get active subscription summary for a user, including countdown details.
     */
    public static function getUserSubscriptionStatus(int $userId): array
    {
        // First check and perform auto-renewal if cycle ended
        self::checkUserRenewal($userId);

        // Find the user's latest active subscription or scheduled plan
        $subscription = Order::with(['items.food'])
            ->where('user_id', $userId)
            ->where(function ($q) {
                $q->where('is_subscription', true)
                  ->orWhereHas('items', function ($iq) {
                      $iq->whereNotNull('scheduled_date');
                  });
            })
            ->whereIn('payment_status', ['paid', 'verified'])
            ->whereHas('items', function ($iq) {
                $iq->whereNotNull('scheduled_date');
            })
            ->latest('id')
            ->first();

        if (!$subscription || $subscription->items->isEmpty()) {
            return [
                'has_active_subscription' => false,
                'subscription' => null,
            ];
        }

        $sortedItems = $subscription->items->filter(fn($i) => !empty($i->scheduled_date))->sortBy('scheduled_date');
        if ($sortedItems->isEmpty()) {
            return [
                'has_active_subscription' => false,
                'subscription' => null,
            ];
        }

        $firstItem = $sortedItems->first();
        $lastItem = $sortedItems->last();

        $startDate = Carbon::parse($firstItem->scheduled_date)->startOfDay();
        $endDate = Carbon::parse($lastItem->scheduled_date)->endOfDay();
        $today = Carbon::today();

        // Calculate days & seconds remaining until the current meal plan ends
        $now = Carbon::now();
        $endDateDay = Carbon::parse($lastItem->scheduled_date)->startOfDay();
        $daysRemaining = max(0, $today->diffInDays($endDateDay, false));
        $secondsRemaining = max(0, $now->diffInSeconds($endDate, false));

        // Week prior rule: Show countdown if within 7 days of ending (and not yet expired)
        $isInWeekPrior = ($daysRemaining <= 7 && $now->lte($endDate));

        $nextCycleStartDate = (clone $endDateDay)->addDay()->format('Y-m-d');

        // Pattern summary
        $uniqueDaysCount = $sortedItems->pluck('scheduled_date')->map(fn($d) => Carbon::parse($d)->toDateString())->unique()->count();
        $mealTypes = $sortedItems->pluck('meal_type')->map(fn($t) => ucfirst($t))->unique()->values()->all();

        return [
            'has_active_subscription' => true,
            'is_in_week_prior' => $isInWeekPrior,
            'order_id' => $subscription->id,
            'order_number' => $subscription->order_number,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'days_remaining' => (int)$daysRemaining,
            'seconds_remaining' => (int)$secondsRemaining,
            'auto_renew_date' => $nextCycleStartDate,
            'auto_renew' => (bool)$subscription->auto_renew,
            'renewed_to_order_id' => $subscription->renewed_to_order_id,
            'total_price' => (float)$subscription->total_price,
            'total_days' => $uniqueDaysCount,
            'meal_types' => $mealTypes,
            'pattern_label' => implode(' & ', $mealTypes) . " ({$uniqueDaysCount} Days)",
            'items_count' => $subscription->items->count(),
        ];
    }

    /**
     * Check and renew for a specific user.
     */
    public static function checkUserRenewal(int $userId): ?Order
    {
        $today = Carbon::today();

        $subscription = Order::with(['items.food'])
            ->where('user_id', $userId)
            ->where('is_subscription', true)
            ->where('auto_renew', true)
            ->whereNull('renewed_to_order_id')
            ->whereIn('payment_status', ['paid', 'verified'])
            ->latest('id')
            ->first();

        if (!$subscription || $subscription->items->isEmpty()) {
            return null;
        }

        $sortedItems = $subscription->items->filter(fn($i) => !empty($i->scheduled_date))->sortBy('scheduled_date');
        if ($sortedItems->isEmpty()) {
            return null;
        }

        $firstItem = $sortedItems->first();
        $lastItem = $sortedItems->last();

        $startDate = Carbon::parse($firstItem->scheduled_date)->startOfDay();
        $endDate = Carbon::parse($lastItem->scheduled_date)->startOfDay();

        if ($today->lt($endDate)) {
            return null;
        }

        return self::renewSubscription($subscription, $startDate, $endDate);
    }
}
