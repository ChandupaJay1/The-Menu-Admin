<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // API consumers (mobile app) receive JSON for the authenticated user/driver.
        if ($request->wantsJson() || $request->is('api/*')) {
            $user = $request->user();
            if ($user instanceof Driver) {
                $orders = \App\Models\Order::with(['items.food.ingredients', 'user'])
                                           ->where('driver_id', $user->id)
                                           ->orderBy('created_at', 'desc')
                                           ->get();
            } else {
                $orders = \App\Models\Order::with('items.food.ingredients')
                                           ->where('user_id', $user?->id)
                                           ->orderBy('created_at', 'desc')
                                           ->get();
            }

            return response()->json($orders);
        }

        $search = $request->input('search');
        $statusFilter = $request->input('status', 'all');
        $scheduleFilter = $request->input('schedule', 'all');

        $todayStr = now()->toDateString();
        $tomorrowStr = now()->addDay()->toDateString();

        $query = \App\Models\Order::with(['user', 'driver', 'items.food'])->latest();

        // Search by ID, Customer Name, Email, Phone, Address, or Ordered Food Item
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('delivery_address', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('items.food', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status Filter
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Schedule Filter
        if ($scheduleFilter === 'today') {
            $query->whereHas('items', function ($q) use ($todayStr) {
                $q->whereDate('scheduled_date', $todayStr);
            });
        } elseif ($scheduleFilter === 'tomorrow') {
            $query->whereHas('items', function ($q) use ($tomorrowStr) {
                $q->whereDate('scheduled_date', $tomorrowStr);
            });
        } elseif ($scheduleFilter === 'upcoming') {
            $query->whereHas('items', function ($q) use ($todayStr) {
                $q->whereDate('scheduled_date', '>=', $todayStr);
            });
        } elseif ($scheduleFilter === 'immediate') {
            $query->whereDoesntHave('items', function ($q) use ($todayStr) {
                $q->whereDate('scheduled_date', '>', $todayStr);
            });
        }

        $orders = $query->paginate(12)->withQueryString();

        // Counts by status
        $counts = \App\Models\Order::selectRaw('status, count(*) as total')
                                   ->groupBy('status')
                                   ->pluck('total', 'status');

        // Upcoming Alerts: Orders scheduled for Today & Tomorrow that require kitchen preparation/dispatch
        $todayUpcomingOrders = \App\Models\Order::with(['user', 'driver', 'items.food'])
            ->whereIn('status', ['pending', 'processing'])
            ->whereHas('items', function ($q) use ($todayStr) {
                $q->whereDate('scheduled_date', $todayStr);
            })
            ->latest()
            ->get();

        $tomorrowUpcomingOrders = \App\Models\Order::with(['user', 'driver', 'items.food'])
            ->whereIn('status', ['pending', 'processing'])
            ->whereHas('items', function ($q) use ($tomorrowStr) {
                $q->whereDate('scheduled_date', $tomorrowStr);
            })
            ->latest()
            ->get();

        $todayScheduledCount = \App\Models\Order::whereHas('items', function ($q) use ($todayStr) {
            $q->whereDate('scheduled_date', $todayStr);
        })->count();

        $upcomingScheduledCount = \App\Models\Order::whereHas('items', function ($q) use ($tomorrowStr) {
            $q->whereDate('scheduled_date', '>=', $tomorrowStr);
        })->count();

        $drivers = \App\Models\Driver::orderBy('name')->get();

        return view('orders', compact(
            'orders', 'counts', 'search', 'statusFilter', 'scheduleFilter',
            'drivers', 'todayUpcomingOrders', 'tomorrowUpcomingOrders',
            'todayScheduledCount', 'upcomingScheduledCount'
        ));
    }

    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\OrdersExport, 'orders.xlsx');
    }

    /**
     * Assign (or unassign) a driver to an order.
     * Only drivers that are "available" (or already assigned to this order) may be assigned;
     * assigning flips the driver's status to "on_delivery", while removing the assignment
     * returns the previous driver to "available".
     */
    public function assignDriver(Request $request, Order $order)
    {
        $driverId = $request->input('driver_id') ?: null;
        $request->merge(['driver_id' => $driverId]);

        $request->validate([
            'driver_id' => ['nullable', 'exists:drivers,id'],
        ]);

        $previousDriver = $order->driver;

        if ($driverId) {
            $driver = Driver::findOrFail($driverId);

            // Free up the previously assigned driver if it's a different one.
            if ($previousDriver && $previousDriver->id !== $driver->id) {
                $previousDriver->update(['status' => 'available']);
                \App\Events\DriverStatusUpdated::safeDispatch($previousDriver);
            }

            // Only an available driver (or the one already on this order) can be assigned.
            if ($driver->status !== 'available' && $driver->id !== ($previousDriver?->id)) {
                return redirect()->back()->with('error', 'The selected driver is not online/available.');
            }

            $driver->update(['status' => 'on_delivery']);
            $order->update([
                'driver_id' => $driver->id,
                'status' => 'assigned'
            ]);

            \App\Events\DriverStatusUpdated::safeDispatch($driver);
            \App\Events\OrderStatusUpdated::safeDispatch($order->fresh(['user', 'driver', 'items.food']));

            // Notify user of driver assignment
            if ($order->user_id) {
                try {
                    \App\Models\UserNotification::create([
                        'user_id' => $order->user_id,
                        'title' => 'Driver Assigned 🛵',
                        'message' => "Driver {$driver->name} has been assigned to your order #{$order->id}!",
                        'type' => 'driver',
                        'data' => [
                            'order_id' => $order->id,
                            'driver_name' => $driver->name,
                            'driver_phone' => $driver->phone,
                        ],
                        'is_read' => false,
                    ]);
                } catch (\Throwable $e) {}
            }

            return redirect()->route('orders')->with('success', 'Driver assigned to order successfully.');
        }

        // Unassign: return the previous driver to available.
        if ($previousDriver) {
            $previousDriver->update(['status' => 'available']);
            \App\Events\DriverStatusUpdated::safeDispatch($previousDriver);
        }
        $order->update(['driver_id' => null]);
        \App\Events\OrderStatusUpdated::safeDispatch($order->fresh(['user', 'driver', 'items.food']));

        return redirect()->route('orders')->with('success', 'Driver assignment removed.');
    }

    /**
     * API for Mobile: Return user's order schedule grouped date-wise into breakfast, lunch, and dinner.
     */
    public function calendar(Request $request)
    {
        $user = $request->user();

        $orderItems = \App\Models\OrderItem::with(['food.ingredients', 'order'])
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->get();

        $grouped = [];

        foreach ($orderItems as $item) {
            $date = $item->scheduled_date 
                ? $item->scheduled_date->format('Y-m-d') 
                : ($item->order ? $item->order->created_at->format('Y-m-d') : now()->format('Y-m-d'));
            
            $mealType = strtolower($item->meal_type ?: ($item->food ? $item->food->type : 'lunch'));
            if (!in_array($mealType, ['breakfast', 'lunch', 'dinner'])) {
                $mealType = 'lunch';
            }

            if (!isset($grouped[$date])) {
                $grouped[$date] = [
                    'date' => $date,
                    'total_spent' => 0.0,
                    'total_items' => 0,
                    'breakfast' => [],
                    'lunch' => [],
                    'dinner' => [],
                ];
            }

            $subtotal = round((float)($item->price * $item->quantity), 2);

            $itemData = [
                'id' => $item->id,
                'order_id' => $item->order_id,
                'order_status' => $item->order ? $item->order->status : 'pending',
                'food_id' => $item->food_id,
                'food_name' => $item->food ? $item->food->name : 'Meal Item',
                'food_image' => $item->food ? $item->food->image_url : '',
                'quantity' => $item->quantity,
                'price' => (float)$item->price,
                'subtotal' => $subtotal,
                'meal_type' => $mealType,
                'scheduled_date' => $date,
            ];

            $grouped[$date][$mealType][] = $itemData;
            $grouped[$date]['total_spent'] = round($grouped[$date]['total_spent'] + $subtotal, 2);
            $grouped[$date]['total_items'] += $item->quantity;
        }

        // Sort descending by date
        krsort($grouped);

        return response()->json([
            'dates' => array_values($grouped),
            'summary' => [
                'total_scheduled_days' => count($grouped),
                'total_meals' => array_sum(array_column($grouped, 'total_items')),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // If items not explicitly provided, convert from active cart
        if (!$request->has('items') || empty($request->input('items'))) {
            $cart = \App\Models\Cart::with('items.food')->where('user_id', $user->id)->first();
            if ($cart && $cart->items->isNotEmpty()) {
                $subtotal = 0;
                $items = [];
                foreach ($cart->items as $cartItem) {
                    $foodPrice = $cartItem->food ? (float)$cartItem->food->price : 0.0;
                    $items[] = [
                        'food_id' => $cartItem->food_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $foodPrice,
                        'meal_type' => $cartItem->meal_type,
                        'scheduled_date' => $cartItem->scheduled_date ? $cartItem->scheduled_date->format('Y-m-d') : null,
                    ];
                    $subtotal += ($foodPrice * $cartItem->quantity);
                }
                $deliveryFee = (float)($request->input('delivery_fee', $cart->delivery_fee ?? 0.0));
                $totalPrice = $subtotal + $deliveryFee;

                $request->merge([
                    'items' => $items,
                    'total_price' => $request->input('total_price', round($totalPrice, 2)),
                    'address' => $request->input('address', $request->input('delivery_address', $cart->delivery_address ?: ($user->address ?? ''))),
                ]);
            }
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.food_id' => 'required|exists:food,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'total_price' => 'required|numeric',
            'address' => 'required|string',
            'dinner_address' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'payment_reference' => 'nullable|string',
            'bank_receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'bank_receipt_url' => 'nullable|string',
            'coupon_code' => 'nullable|string',
            'discount_amount' => 'nullable|numeric',
        ]);

        // Enforce 20 distinct days minimum for scheduled meal plans
        $scheduledDates = collect($request->items)
            ->pluck('scheduled_date')
            ->filter()
            ->unique();
        if ($scheduledDates->count() > 0 && $scheduledDates->count() < 20) {
            return response()->json([
                'message' => 'Minimum 20 days of meals required for monthly plan (currently ' . $scheduledDates->count() . ' days selected). Please select at least 20 days.',
            ], 422);
        }

        // Handle Bank Receipt Upload
        $receiptUrl = $request->bank_receipt_url;
        if ($request->hasFile('bank_receipt')) {
            $path = $request->file('bank_receipt')->store('receipts', 'public');
            $receiptUrl = asset('storage/' . $path);
        }

        $paymentMethod = $request->payment_method ?: 'Bank Transfer';
        $isSubscription = in_array($paymentMethod, ['Visa / Mastercard', 'Card', 'Credit / Debit Card']);
        $paymentStatus = $isSubscription ? 'paid' : 'pending_verification';

        $reference = $request->payment_reference ?: \App\Models\Order::generateReference($user->id);

        $order = \App\Models\Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_phone' => $user->phone ?? '',
            'delivery_address' => $request->address,
            'dinner_address' => $request->dinner_address,
            'total_amount' => $request->total_price,
            'total_price' => $request->total_price,
            'status' => 'pending',
            'address' => $request->address,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'payment_reference' => $reference,
            'bank_receipt_url' => $receiptUrl,
            'is_subscription' => $isSubscription,
            'coupon_code' => $request->coupon_code,
            'discount_amount' => $request->discount_amount ?? 0.00,
        ]);

        foreach ($request->items as $item) {
            $food = \App\Models\Food::find($item['food_id']);
            $defaultMealType = strtolower($food?->type ?? 'lunch');
            if (!in_array($defaultMealType, ['breakfast', 'lunch', 'dinner'])) {
                $defaultMealType = 'lunch';
            }

            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $item['food_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'meal_type' => $item['meal_type'] ?? $defaultMealType,
                'scheduled_date' => $item['scheduled_date'] ?? now()->toDateString(),
            ]);
        }

        \App\Models\Cart::where('user_id', $request->user()->id)->delete();

        // Create UserNotification for placed order
        try {
            $msg = $isSubscription
                ? "Monthly Subscription Order #{$order->id} confirmed! Card billed for Rs. " . number_format($order->total_price, 2) . "."
                : "Order #{$order->id} placed via Bank Transfer (Ref: {$reference}). Awaiting admin verification.";

            \App\Models\UserNotification::create([
                'user_id' => $user->id,
                'title' => 'Order Confirmed! 🎉',
                'message' => $msg,
                'type' => 'order',
                'data' => [
                    'order_id' => $order->id,
                    'total_price' => $order->total_price,
                    'reference' => $reference,
                    'payment_status' => $paymentStatus,
                ],
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {}

        $loadedOrder = $order->load(['items.food.ingredients', 'user', 'driver']);
        \App\Events\OrderStatusUpdated::safeDispatch($loadedOrder, 'created');

        return response()->json($loadedOrder, 201);
    }

    /**
     * Admin: Update the status of an order (web panel).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,assigned,on_delivery,completed,cancelled,delivered',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return redirect()->back()->with('info', 'Order status is already ' . $newStatus . '.');
        }

        $order->update(['status' => $newStatus]);

        // Notify the user about the status change
        if ($order->user_id) {
            $messages = [
                'processing'  => "Your order #{$order->id} is now being prepared! 🍳",
                'completed'   => "Your order #{$order->id} has been completed! Thank you for ordering. ✅",
                'delivered'   => "Your order #{$order->id} has been delivered! Enjoy your meal! 🎉",
                'cancelled'   => "Your order #{$order->id} has been cancelled. Please contact us if you have questions.",
            ];

            try {
                \App\Models\UserNotification::create([
                    'user_id' => $order->user_id,
                    'title'   => 'Order Update 📦',
                    'message' => $messages[$newStatus] ?? "Your order #{$order->id} status changed to {$newStatus}.",
                    'type'    => 'order',
                    'data'    => ['order_id' => $order->id, 'status' => $newStatus],
                    'is_read' => false,
                ]);
            } catch (\Throwable $e) {}
        }

        \App\Events\OrderStatusUpdated::safeDispatch($order->fresh(['user', 'driver', 'items.food']));

        return redirect()->route('orders')->with('success', "Order #ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT) . " status updated to {$newStatus}.");
    }

    /**
     * Admin: Verify bank transfer payment.
     */
    public function verifyPayment(Order $order)
    {
        $order->update(['payment_status' => 'verified']);

        if ($order->user_id) {
            try {
                \App\Models\UserNotification::create([
                    'user_id' => $order->user_id,
                    'title'   => 'Payment Verified! 💳✅',
                    'message' => "Your bank transfer for Order #{$order->id} (Ref: {$order->payment_reference}) has been verified. You can now download your official invoice.",
                    'type'    => 'payment',
                    'data'    => ['order_id' => $order->id, 'payment_status' => 'verified'],
                    'is_read' => false,
                ]);
            } catch (\Throwable $e) {}
        }

        return redirect()->back()->with('success', "Payment for Order #ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT) . " has been verified successfully!");
    }

    /**
     * Admin: Reject bank transfer payment.
     */
    public function rejectPayment(Order $order)
    {
        $order->update(['payment_status' => 'rejected']);

        if ($order->user_id) {
            try {
                \App\Models\UserNotification::create([
                    'user_id' => $order->user_id,
                    'title'   => 'Payment Verification Failed ⚠️',
                    'message' => "We could not verify the bank transfer for Order #{$order->id}. Please contact support or re-upload your transfer slip.",
                    'type'    => 'payment',
                    'data'    => ['order_id' => $order->id, 'payment_status' => 'rejected'],
                    'is_read' => false,
                ]);
            } catch (\Throwable $e) {}
        }

        return redirect()->back()->with('warning', "Payment for Order #ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT) . " marked as rejected.");
    }

    /**
     * Printable Web Invoice for Admin and Users.
     */
    public function invoice(Order $order)
    {
        $order->load(['items.food', 'user']);
        return view('orders.invoice', compact('order'));
    }

    /**
     * Mobile JSON Invoice Endpoint.
     */
    public function apiInvoice($id, Request $request)
    {
        $order = Order::with(['items.food', 'user'])->findOrFail($id);

        if ($request->user() && $order->user_id !== $request->user()->id && !($request->user() instanceof Driver)) {
            return response()->json(['message' => 'Unauthorized access to invoice'], 403);
        }

        return response()->json([
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'invoice_number' => 'INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            'date' => $order->created_at->format('Y-m-d H:i:s'),
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'delivery_address' => $order->delivery_address,
            'dinner_address' => $order->dinner_address,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'payment_reference' => $order->payment_reference,
            'is_subscription' => (bool)$order->is_subscription,
            'is_verified' => in_array($order->payment_status, ['paid', 'verified']),
            'coupon_code' => $order->coupon_code,
            'discount_amount' => (float)$order->discount_amount,
            'subtotal' => (float)($order->total_amount + $order->discount_amount),
            'total_amount' => (float)$order->total_amount,
            'items' => $order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->food?->name ?? 'Dish',
                    'quantity' => $item->quantity,
                    'price' => (float)$item->price,
                    'total' => (float)($item->price * $item->quantity),
                    'meal_type' => $item->meal_type,
                    'scheduled_date' => $item->scheduled_date,
                ];
            }),
        ]);
    }

    /**
     * Delete/Cancel an order cleanly.
     */
    public function destroy(Order $order)
    {
        // Free up the driver if assigned
        if ($order->driver) {
            $order->driver->update(['status' => 'available']);
            \App\Events\DriverStatusUpdated::safeDispatch($order->driver);
        }

        $order->delete();
        
        return redirect()->route('orders')->with('success', 'Order has been successfully deleted.');
    }
}
