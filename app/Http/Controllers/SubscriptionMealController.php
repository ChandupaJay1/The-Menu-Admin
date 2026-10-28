<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionMealController extends Controller
{
    /**
     * Get active subscription meals schedule, eligibility for swaps & pauses, and credits.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $subscription = Order::with(['items.food'])
            ->where('user_id', $user->id)
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
            // Check if there is an order awaiting slip verification
            $pendingOrder = Order::where('user_id', $user->id)
                ->where(function ($q) {
                    $q->where('is_subscription', true)
                      ->orWhereHas('items', function ($iq) {
                          $iq->whereNotNull('scheduled_date');
                      });
                })
                ->where('payment_status', 'pending_verification')
                ->whereHas('items')
                ->latest('id')
                ->first();

            if ($pendingOrder) {
                return response()->json([
                    'has_active_subscription' => false,
                    'is_pending_verification' => true,
                    'message' => "Order #{$pendingOrder->id} is awaiting bank transfer slip verification by our kitchen admin. You can swap meals as soon as it is approved.",
                    'order_id' => $pendingOrder->id,
                    'order_number' => $pendingOrder->order_number,
                    'subscription' => null,
                    'items' => [],
                ]);
            }

            return response()->json([
                'has_active_subscription' => false,
                'message' => 'No active recurring meal subscription found.',
                'subscription' => null,
                'items' => [],
            ]);
        }

        $now = Carbon::now();
        $pausedDates = is_array($subscription->paused_dates) ? $subscription->paused_dates : [];
        $pausedDaysCount = (int)$subscription->paused_days_count;
        $pausedCredit = (float)$subscription->paused_credit_amount;

        $items = $subscription->items->sortBy('scheduled_date')->map(function ($item) use ($now) {
            $scheduledDateStr = $item->scheduled_date ? $item->scheduled_date->format('Y-m-d') : null;
            
            // Fixed delivery cutoff times: Breakfast 7am, Lunch 11am, Dinner 6pm
            $deliveryHour = match (strtolower($item->meal_type ?? 'lunch')) {
                'breakfast' => 7,
                'lunch' => 11,
                'dinner' => 18,
                default => 11,
            };

            $deliveryMoment = $scheduledDateStr 
                ? Carbon::parse($scheduledDateStr)->setTime($deliveryHour, 0, 0)
                : Carbon::tomorrow()->setTime(11, 0, 0);

            // 24-Hour advance notice rule for swaps
            $hoursUntilDelivery = $now->diffInHours($deliveryMoment, false);
            $isSwapEligible = ($hoursUntilDelivery >= 24) && !$item->is_paused;

            // 48-Hour advance notice rule for holds/pauses
            $deliveryDayStart = $scheduledDateStr ? Carbon::parse($scheduledDateStr)->startOfDay() : Carbon::tomorrow();
            $hoursUntilDayStart = $now->diffInHours($deliveryDayStart, false);
            $isPauseEligible = ($hoursUntilDayStart >= 48);

            return [
                'id' => $item->id,
                'order_id' => $item->order_id,
                'food_id' => $item->food_id,
                'food_name' => $item->food ? $item->food->name : 'Dish',
                'food_image' => $item->food ? $item->food->image_url : '',
                'price' => (float)$item->price,
                'quantity' => (int)$item->quantity,
                'meal_type' => $item->meal_type ?? 'lunch',
                'scheduled_date' => $scheduledDateStr,
                'is_paused' => (bool)$item->is_paused,
                'swapped_from_food_id' => $item->swapped_from_food_id,
                'is_swap_eligible' => $isSwapEligible,
                'is_pause_eligible' => $isPauseEligible,
                'hours_until_delivery' => max(0, (int)$hoursUntilDelivery),
                'hours_until_day_start' => max(0, (int)$hoursUntilDayStart),
            ];
        })->values();

        // Group into unique days for pause management
        $uniqueDays = $items->groupBy('scheduled_date')->map(function ($dayItems, $dateStr) use ($now, $pausedDates) {
            $dayStart = Carbon::parse($dateStr)->startOfDay();
            $hoursUntil = $now->diffInHours($dayStart, false);
            $isEligible = ($hoursUntil >= 48);
            $isHeld = in_array($dateStr, $pausedDates) || $dayItems->every(fn($i) => $i['is_paused']);
            $dayTotalValue = $dayItems->sum(fn($i) => $i['price'] * $i['quantity']);

            return [
                'date' => $dateStr,
                'formatted_date' => Carbon::parse($dateStr)->format('D, M d'),
                'is_held' => $isHeld,
                'is_eligible' => $isEligible,
                'hours_until' => max(0, (int)$hoursUntil),
                'meals_count' => $dayItems->count(),
                'day_value' => round($dayTotalValue, 2),
                'meals' => $dayItems->values(),
            ];
        })->values();

        $lastItem = $subscription->items->sortBy('scheduled_date')->last();
        $autoRenewDate = $lastItem && $lastItem->scheduled_date 
            ? Carbon::parse($lastItem->scheduled_date)->addDay()->format('Y-m-d')
            : Carbon::tomorrow()->format('Y-m-d');

        return response()->json([
            'has_active_subscription' => true,
            'order_id' => $subscription->id,
            'order_number' => $subscription->order_number,
            'auto_renew_date' => $autoRenewDate,
            'max_pause_days' => 3,
            'paused_days_count' => $pausedDaysCount,
            'paused_credit_amount' => $pausedCredit,
            'paused_dates' => $pausedDates,
            'remaining_pause_allowance' => max(0, 3 - $pausedDaysCount),
            'items' => $items,
            'days' => $uniqueDays,
        ]);
    }

    /**
     * Get available swap dishes for a specific order item within equivalent price tier.
     */
    public function availableSwaps(Request $request, $orderItemId)
    {
        $user = $request->user();
        $item = OrderItem::with(['food', 'order'])->findOrFail($orderItemId);

        if ($item->order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mealType = strtolower($item->meal_type ?: ($item->food ? $item->food->type : 'lunch'));
        $currentPrice = (float)($item->price ?: ($item->food ? $item->food->price : 0.0));

        // Equivalent price tier: dishes matching meal type with price within ±25% or Rs. 150
        $minPrice = max(0, $currentPrice * 0.75);
        $maxPrice = max($currentPrice * 1.25, $currentPrice + 150);

        $foods = Food::whereRaw('LOWER(type) = ?', [$mealType])
            ->where('id', '!=', $item->food_id)
            ->whereBetween('price', [$minPrice, $maxPrice])
            ->orderBy('name')
            ->get()
            ->map(function ($f) use ($currentPrice) {
                return [
                    'id' => $f->id,
                    'name' => $f->name,
                    'description' => $f->description,
                    'price' => (float)$f->price,
                    'image_url' => $f->image_url,
                    'type' => $f->type,
                    'price_diff' => round((float)$f->price - $currentPrice, 2),
                ];
            });

        return response()->json([
            'current_item' => [
                'id' => $item->id,
                'food_id' => $item->food_id,
                'food_name' => $item->food ? $item->food->name : '',
                'meal_type' => $mealType,
                'price' => $currentPrice,
                'scheduled_date' => $item->scheduled_date ? $item->scheduled_date->format('Y-m-d') : null,
            ],
            'available_foods' => $foods,
        ]);
    }

    /**
     * Swap a meal (Enforces 24-Hour Notice and Equivalent Price Tier).
     */
    public function swapMeal(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'new_food_id' => 'required|exists:food,id',
        ]);

        $user = $request->user();
        $item = OrderItem::with(['food', 'order'])->findOrFail($request->order_item_id);

        if ($item->order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($item->is_paused) {
            return response()->json(['message' => 'Cannot swap a meal on a paused delivery day.'], 422);
        }

        // Enforce 24-Hour advance rule
        $scheduledDateStr = $item->scheduled_date ? $item->scheduled_date->format('Y-m-d') : null;
        $deliveryHour = match (strtolower($item->meal_type ?? 'lunch')) {
            'breakfast' => 7,
            'lunch' => 11,
            'dinner' => 18,
            default => 11,
        };

        $deliveryMoment = $scheduledDateStr 
            ? Carbon::parse($scheduledDateStr)->setTime($deliveryHour, 0, 0)
            : Carbon::tomorrow()->setTime(11, 0, 0);

        if (Carbon::now()->diffInHours($deliveryMoment, false) < 24) {
            return response()->json([
                'message' => 'Meal swaps must be submitted at least 24 hours in advance. The preparation cutoff for this meal has passed.'
            ], 422);
        }

        $newFood = Food::findOrFail($request->new_food_id);
        $currentPrice = (float)$item->price;

        // Equivalent price tier validation (within 25% or up to Rs. 150 difference)
        $maxAllowedPrice = max($currentPrice * 1.25, $currentPrice + 150);
        if ($newFood->price > $maxAllowedPrice) {
            return response()->json([
                'message' => 'Swaps are only permitted for meals within the equivalent price tier of your current plan.'
            ], 422);
        }

        $oldFoodName = $item->food ? $item->food->name : 'Previous dish';

        DB::transaction(function () use ($item, $newFood) {
            $item->update([
                'swapped_from_food_id' => $item->food_id,
                'food_id' => $newFood->id,
                'price' => $newFood->price,
            ]);
        });

        // Send confirmation in-app alert
        try {
            UserNotification::create([
                'user_id' => $user->id,
                'title' => 'Meal Swapped Successfully 🔄',
                'message' => "Your {$item->meal_type} on {$item->scheduled_date->format('M d')} was swapped from {$oldFoodName} to {$newFood->name}.",
                'type' => 'subscription',
                'data' => [
                    'order_item_id' => $item->id,
                    'new_food_id' => $newFood->id,
                    'new_food_name' => $newFood->name,
                ],
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'message' => "Meal swapped to {$newFood->name} successfully!",
            'item' => $item->fresh('food'),
        ]);
    }

    /**
     * Pause / Hold deliveries for specific days (Enforces 48-Hour Notice & 3-Day Cap).
     */
    public function pauseDays(Request $request)
    {
        $request->validate([
            'dates' => 'required|array|min:1',
            'dates.*' => 'required|date_format:Y-m-d',
        ]);

        $user = $request->user();
        $subscription = Order::with(['items.food'])
            ->where('user_id', $user->id)
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

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription found.'], 404);
        }

        $existingPaused = is_array($subscription->paused_dates) ? $subscription->paused_dates : [];
        $requestedDates = array_values(array_unique($request->dates));
        $newDates = array_diff($requestedDates, $existingPaused);

        if (empty($newDates)) {
            return response()->json(['message' => 'The selected dates are already on hold.'], 422);
        }

        // Strict 3-Day Cap Rule
        $totalWouldBe = count($existingPaused) + count($newDates);
        if ($totalWouldBe > 3) {
            return response()->json([
                'message' => "Maximum Hold Duration exceeded. You can only place your subscription on hold for a strict maximum of three (3) days per cycle. (Currently on hold: " . count($existingPaused) . " day(s))."
            ], 422);
        }

        // 48-Hour Advance Notice Rule
        $now = Carbon::now();
        foreach ($newDates as $d) {
            $dayStart = Carbon::parse($d)->startOfDay();
            if ($now->diffInHours($dayStart, false) < 48) {
                return response()->json([
                    'message' => "Advanced Notice required: Any subscription hold or pause request must be submitted at least 48 hours prior to the affected delivery day ({$d})."
                ], 422);
            }
        }

        $addedCredit = 0.0;
        DB::transaction(function () use ($subscription, $newDates, $existingPaused, &$addedCredit) {
            foreach ($newDates as $d) {
                $itemsOnDate = $subscription->items->filter(function ($i) use ($d) {
                    return $i->scheduled_date && $i->scheduled_date->format('Y-m-d') === $d && !$i->is_paused;
                });

                foreach ($itemsOnDate as $item) {
                    $item->update(['is_paused' => true]);
                    $addedCredit += (float)($item->price * $item->quantity);
                }
            }

            $allPaused = array_values(array_unique(array_merge($existingPaused, $newDates)));
            $subscription->update([
                'paused_dates' => $allPaused,
                'paused_days_count' => count($allPaused),
                'paused_credit_amount' => (float)$subscription->paused_credit_amount + $addedCredit,
            ]);
        });

        // Send confirmation in-app alert
        try {
            $formattedDates = array_map(fn($d) => Carbon::parse($d)->format('M d'), $newDates);
            UserNotification::create([
                'user_id' => $user->id,
                'title' => 'Subscription Held ⏸️',
                'message' => "Deliveries paused for " . implode(', ', $formattedDates) . ". Rs. " . number_format($addedCredit, 2) . " credit will be automatically applied to your next renewal invoice (No cash refunds).",
                'type' => 'subscription',
                'data' => [
                    'paused_dates' => $newDates,
                    'credit_added' => $addedCredit,
                ],
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'message' => 'Subscription placed on hold successfully!',
            'paused_days_count' => $subscription->fresh()->paused_days_count,
            'paused_credit_amount' => (float)$subscription->fresh()->paused_credit_amount,
            'paused_dates' => $subscription->fresh()->paused_dates,
            'credit_added' => $addedCredit,
        ]);
    }

    /**
     * Resume a paused day (if still >= 48 hours away).
     */
    public function resumeDay(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $user = $request->user();
        $subscription = Order::with(['items.food'])
            ->where('user_id', $user->id)
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

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription found.'], 404);
        }

        $date = $request->date;
        $existingPaused = is_array($subscription->paused_dates) ? $subscription->paused_dates : [];

        if (!in_array($date, $existingPaused)) {
            return response()->json(['message' => 'This date is not currently on hold.'], 422);
        }

        // Advance notice check (at least 48 hours before date)
        $dayStart = Carbon::parse($date)->startOfDay();
        if (Carbon::now()->diffInHours($dayStart, false) < 48) {
            return response()->json([
                'message' => 'Resuming deliveries requires at least 48 hours notice so the kitchen can source ingredients.'
            ], 422);
        }

        $creditRemoved = 0.0;
        DB::transaction(function () use ($subscription, $date, $existingPaused, &$creditRemoved) {
            $itemsOnDate = $subscription->items->filter(function ($i) use ($date) {
                return $i->scheduled_date && $i->scheduled_date->format('Y-m-d') === $date;
            });

            foreach ($itemsOnDate as $item) {
                if ($item->is_paused) {
                    $item->update(['is_paused' => false]);
                    $creditRemoved += (float)($item->price * $item->quantity);
                }
            }

            $updatedPaused = array_values(array_filter($existingPaused, fn($d) => $d !== $date));
            $newCredit = max(0.0, (float)$subscription->paused_credit_amount - $creditRemoved);

            $subscription->update([
                'paused_dates' => $updatedPaused,
                'paused_days_count' => count($updatedPaused),
                'paused_credit_amount' => $newCredit,
            ]);
        });

        return response()->json([
            'message' => "Deliveries resumed for " . Carbon::parse($date)->format('M d') . ".",
            'paused_days_count' => $subscription->fresh()->paused_days_count,
            'paused_credit_amount' => (float)$subscription->fresh()->paused_credit_amount,
            'paused_dates' => $subscription->fresh()->paused_dates,
        ]);
    }
}
