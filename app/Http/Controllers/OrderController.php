<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // API consumers (mobile app) receive JSON for the authenticated user.
        if ($request->wantsJson()) {
            $orders = \App\Models\Order::with('items.food.ingredients')
                                       ->where('user_id', $request->user()->id)
                                       ->orderBy('created_at', 'desc')
                                       ->get();

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
            }

            // Only an available driver (or the one already on this order) can be assigned.
            if ($driver->status !== 'available' && $driver->id !== ($previousDriver?->id)) {
                return redirect()->back()->with('error', 'The selected driver is not available.');
            }

            $driver->update(['status' => 'on_delivery']);
            $order->update(['driver_id' => $driver->id]);

            return redirect()->route('orders')->with('success', 'Driver assigned to order successfully.');
        }

        // Unassign: return the previous driver to available.
        if ($previousDriver) {
            $previousDriver->update(['status' => 'available']);
        }
        $order->update(['driver_id' => null]);

        return redirect()->route('orders')->with('success', 'Driver assignment removed.');
    }

    public function store(Request $request)
    {
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
            'user_id' => $request->user()->id,
            'total_price' => $request->total_price,
            'status' => 'pending',
            'address' => $request->address,
            'payment_method' => $request->payment_method,
        ]);

        foreach ($request->items as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $item['food_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        \App\Models\Cart::where('user_id', $request->user()->id)->delete();

        return response()->json($order->load('items.food.ingredients'), 201);
    }
}
