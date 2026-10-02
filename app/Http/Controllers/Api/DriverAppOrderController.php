<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DriverAppOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $driver = $request->user();
        $orders = Order::where('driver_id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    public function latest(Request $request): JsonResponse
    {
        $driver = $request->user();
        $orders = Order::where('driver_id', $driver->id)
            ->whereIn('status', ['assigned', 'on_delivery'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:assigned,on_delivery,delivered,cancelled'
        ]);

        $order = Order::where('id', $id)
            ->where('driver_id', $request->user()->id)
            ->firstOrFail();

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated.',
            'order' => $order
        ]);
    }
}
