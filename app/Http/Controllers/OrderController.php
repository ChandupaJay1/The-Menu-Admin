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

        $query = \App\Models\Order::with(['user', 'items.food'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $orders = $query->paginate(10)->withQueryString();

        $counts = \App\Models\Order::selectRaw('status, count(*) as total')
                                   ->groupBy('status')
                                   ->pluck('total', 'status');

        $drivers = \App\Models\Driver::orderBy('name')->get();

        return view('orders', compact('orders', 'counts', 'search', 'statusFilter', 'drivers'));
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
            $order->update(['driver_id' => $driver->id]);

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
            'payment_method' => 'nullable|string',
        ]);

        $order = \App\Models\Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_phone' => $user->phone ?? '',
            'delivery_address' => $request->address,
            'total_amount' => $request->total_price,
            'total_price' => $request->total_price,
            'status' => 'pending',
            'address' => $request->address,
            'payment_method' => $request->payment_method ?? 'Cash on Delivery',
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
            \App\Models\UserNotification::create([
                'user_id' => $user->id,
                'title' => 'Order Confirmed! 🎉',
                'message' => "Order #{$order->id} placed successfully for Rs. " . number_format($order->total_price, 2) . ". We're preparing your delicious meal!",
                'type' => 'order',
                'data' => [
                    'order_id' => $order->id,
                    'total_price' => $order->total_price,
                ],
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {}

        $loadedOrder = $order->load(['items.food.ingredients', 'user', 'driver']);
        \App\Events\OrderStatusUpdated::safeDispatch($loadedOrder, 'created');

        return response()->json($loadedOrder, 201);
    }
}
