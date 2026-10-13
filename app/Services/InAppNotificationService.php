<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PlannedMeal;
use App\Models\User;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class InAppNotificationService
{
    /**
     * Check and trigger automatic in-app notifications for the given user.
     */
    public static function syncForUser(User $user): void
    {
        try {
            self::checkAbandonedMealPlan($user);
            self::checkLastOrderFollowup($user);
            self::checkMealPlanStart($user);
            self::checkMealPlanEnd($user);
        } catch (\Throwable $e) {
            Log::error("Error syncing in-app notifications for user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Trigger 1: If customer selected meals and not gone for checkout (waited a day or so).
     */
    protected static function checkAbandonedMealPlan(User $user): void
    {
        // 1. Check Cart items
        $cart = Cart::where('user_id', $user->id)->first();
        $hasCartItems = false;
        $cartLastUpdated = null;

        if ($cart) {
            $latestCartItem = CartItem::where('cart_id', $cart->id)->latest('updated_at')->first();
            if ($latestCartItem) {
                $hasCartItems = true;
                $cartLastUpdated = $latestCartItem->updated_at;
            }
        }

        // 2. Check Planned Meals
        $latestPlannedMeal = PlannedMeal::where('user_id', $user->id)
            ->whereDate('date', '>=', now()->toDateString())
            ->latest('updated_at')
            ->first();

        $hasPlannedMeals = $latestPlannedMeal !== null;
        $planLastUpdated = $latestPlannedMeal?->updated_at;

        if (!$hasCartItems && !$hasPlannedMeals) {
            return;
        }

        // Determine most recent activity
        $mostRecentActivity = $cartLastUpdated ?? $planLastUpdated;
        if ($cartLastUpdated && $planLastUpdated) {
            $mostRecentActivity = $cartLastUpdated->greaterThan($planLastUpdated) ? $cartLastUpdated : $planLastUpdated;
        }

        // User must have waited around ~18 to 24 hours without placing an order
        if (!$mostRecentActivity || $mostRecentActivity->greaterThan(now()->subHours(18))) {
            return;
        }

        // Check if an order was placed after that activity
        $recentOrder = Order::where('user_id', $user->id)
            ->where('created_at', '>=', $mostRecentActivity)
            ->exists();

        if ($recentOrder) {
            return; // Order was placed, not abandoned
        }

        // Check if we sent an abandoned_plan notification in the last 48 hours
        $alreadyNotified = UserNotification::where('user_id', $user->id)
            ->where('type', 'abandoned_plan')
            ->where('created_at', '>=', now()->subDays(2))
            ->exists();

        if (!$alreadyNotified) {
            UserNotification::create([
                'user_id' => $user->id,
                'title' => '🍽️ Still Planning Your Meals?',
                'message' => 'You have delicious curated meals waiting in your plan! Complete your checkout to ensure uninterrupted daily kitchen prep and on-time delivery.',
                'type' => 'abandoned_plan',
                'data' => [
                    'action' => 'cart',
                    'route' => '/cart',
                ],
                'is_read' => false,
            ]);
        }
    }

    /**
     * Trigger 2: Follow-up from last completed/delivered order.
     */
    protected static function checkLastOrderFollowup(User $user): void
    {
        $lastOrder = Order::where('user_id', $user->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->latest('updated_at')
            ->first();

        if (!$lastOrder) {
            return;
        }

        // Order must have been delivered/completed at least 1 day ago, but within the last 7 days
        $completedTime = $lastOrder->updated_at;
        if ($completedTime->greaterThan(now()->subHours(20)) || $completedTime->lessThan(now()->subDays(7))) {
            return;
        }

        // Deduplication: Has a followup been sent for this order id?
        $alreadyNotified = UserNotification::where('user_id', $user->id)
            ->where('type', 'order_followup')
            ->whereJsonContains('data->order_id', $lastOrder->id)
            ->exists();

        if (!$alreadyNotified) {
            $orderName = $lastOrder->order_number ?? "#OD{$lastOrder->id}";
            UserNotification::create([
                'user_id' => $user->id,
                'title' => '✨ How Was Your Recent Meal?',
                'message' => "We hope you loved your dishes from {$orderName}! Rate your food experience or discover exciting new chef's specials on our menu.",
                'type' => 'order_followup',
                'data' => [
                    'action' => 'orders',
                    'order_id' => $lastOrder->id,
                    'route' => '/orders',
                ],
                'is_read' => false,
            ]);
        }
    }

    /**
     * Trigger 3: Notify before starting of customer's meal plan (1 day prior / starting tomorrow).
     */
    protected static function checkMealPlanStart(User $user): void
    {
        $tomorrowDate = now()->addDay()->toDateString();
        $todayDate = now()->toDateString();

        // Find upcoming order items starting tomorrow or today
        $startingItem = OrderItem::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->whereNotIn('status', ['cancelled', 'rejected']);
        })
        ->whereDate('scheduled_date', '>=', $todayDate)
        ->whereDate('scheduled_date', '<=', $tomorrowDate)
        ->orderBy('scheduled_date', 'asc')
        ->first();

        if (!$startingItem || !$startingItem->scheduled_date) {
            return;
        }

        $startDateStr = Carbon::parse($startingItem->scheduled_date)->format('Y-m-d');
        $displayDate = Carbon::parse($startingItem->scheduled_date)->format('l, M j');

        // Check if already notified for this start date
        $alreadyNotified = UserNotification::where('user_id', $user->id)
            ->where('type', 'meal_plan_start')
            ->whereJsonContains('data->start_date', $startDateStr)
            ->exists();

        if (!$alreadyNotified) {
            $isTomorrow = $startDateStr === $tomorrowDate;
            $title = $isTomorrow ? '🚀 Your Meal Plan Starts Tomorrow!' : '🚀 Your Meal Plan Starts Today!';
            UserNotification::create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => "Get excited! Your gourmet meal deliveries begin on {$displayDate}. Check your order calendar to view your upcoming dishes.",
                'type' => 'meal_plan_start',
                'data' => [
                    'action' => 'calendar',
                    'start_date' => $startDateStr,
                    'route' => '/calendar',
                ],
                'is_read' => false,
            ]);
        }
    }

    /**
     * Trigger 4: Notify before end of customer's meal plan (within 2 days of final scheduled day).
     */
    protected static function checkMealPlanEnd(User $user): void
    {
        // Find the user's latest scheduled item across active orders
        $latestScheduledItem = OrderItem::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->whereNotIn('status', ['cancelled', 'rejected']);
        })
        ->whereNotNull('scheduled_date')
        ->orderBy('scheduled_date', 'desc')
        ->first();

        if (!$latestScheduledItem || !$latestScheduledItem->scheduled_date) {
            return;
        }

        $endDate = Carbon::parse($latestScheduledItem->scheduled_date)->startOfDay();
        $nowDate = now()->startOfDay();

        // If the end date is between today and 2 days in future
        if ($endDate->lessThan($nowDate) || $endDate->greaterThan($nowDate->copy()->addDays(2))) {
            return;
        }

        $endDateStr = $endDate->format('Y-m-d');
        $displayDate = $endDate->format('l, M j');

        // Check if already notified for this end date
        $alreadyNotified = UserNotification::where('user_id', $user->id)
            ->where('type', 'meal_plan_end')
            ->whereJsonContains('data->end_date', $endDateStr)
            ->exists();

        if (!$alreadyNotified) {
            UserNotification::create([
                'user_id' => $user->id,
                'title' => '⏳ Meal Plan Ending Soon - Renew Today!',
                'message' => "Your current scheduled meal plan concludes on {$displayDate}. Renew your subscription or plan your next 20 days now to keep daily meals coming seamlessly!",
                'type' => 'meal_plan_end',
                'data' => [
                    'action' => 'menu',
                    'end_date' => $endDateStr,
                    'route' => '/menu',
                ],
                'is_read' => false,
            ]);
        }
    }
}
