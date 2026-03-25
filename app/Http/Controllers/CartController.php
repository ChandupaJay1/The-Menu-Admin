<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $carts = \App\Models\Cart::with('items.food.ingredients')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($carts);
    }

    public function add(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:food,id',
            'quantity' => 'required|integer|min:1',
            'delivery_address' => 'nullable|string',
            'delivery_fee' => 'nullable|numeric',
        ]);

        $cart = \App\Models\Cart::firstOrCreate(
            ['user_id' => $request->user()->id],
            [
                'delivery_address' => $request->delivery_address ?? $request->user()->address ?? '',
                'delivery_fee' => $request->delivery_fee ?? 0,
            ]
        );

        $cartItem = \App\Models\CartItem::where('cart_id', $cart->id)
            ->where('food_id', $request->food_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            \App\Models\CartItem::create([
                'cart_id' => $cart->id,
                'food_id' => $request->food_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json($cart->load('items.food.ingredients'));
    }

    public function remove(Request $request)
    {
        $request->validate(['food_id' => 'required|exists:food,id']);

        $cart = \App\Models\Cart::where('user_id', $request->user()->id)->first();
        if ($cart) {
            \App\Models\CartItem::where('cart_id', $cart->id)
                ->where('food_id', $request->food_id)
                ->delete();
        }

        return response()->json(['message' => 'Item removed']);
    }
}
