<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = \App\Models\Order::with('items.food.ingredients')
                                   ->where('user_id', $request->user()->id)
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        return response()->json($orders);
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
