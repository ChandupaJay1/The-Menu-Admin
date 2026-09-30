<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function formatCart(Cart $cart)
    {
        $cart->load('items.food.ingredients');
        $subtotal = $cart->items->sum(function ($item) {
            return ($item->food ? $item->food->price : 0) * $item->quantity;
        });

        $cartData = $cart->toArray();
        $cartData['subtotal'] = round($subtotal, 2);
        $cartData['total_price'] = round($subtotal + (float)$cart->delivery_fee, 2);

        return $cartData;
    }

    public function index(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();

        if (!$cart) {
            return response()->json([
                'id' => null,
                'user_id' => $request->user()->id,
                'items' => [],
                'delivery_address' => $request->user()->address ?? '',
                'delivery_fee' => 0.0,
                'subtotal' => 0.0,
                'total_price' => 0.0,
            ]);
        }

        return response()->json($this->formatCart($cart));
    }

    public function add(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:food,id',
            'quantity' => 'required|integer|min:1',
            'delivery_address' => 'nullable|string',
            'delivery_fee' => 'nullable|numeric',
        ]);

        $cart = Cart::firstOrCreate(
            ['user_id' => $request->user()->id],
            [
                'delivery_address' => $request->delivery_address ?? $request->user()->address ?? '',
                'delivery_fee' => $request->delivery_fee ?? 0.0,
            ]
        );

        if ($request->filled('delivery_address')) {
            $cart->update(['delivery_address' => $request->delivery_address]);
        }
        if ($request->filled('delivery_fee')) {
            $cart->update(['delivery_fee' => $request->delivery_fee]);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('food_id', $request->food_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'food_id' => $request->food_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json($this->formatCart($cart));
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:food,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = Cart::where('user_id', $request->user()->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('food_id', $request->food_id)
            ->first();

        if ($cartItem) {
            if ($request->quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->update(['quantity' => $request->quantity]);
            }
        }

        return response()->json($this->formatCart($cart));
    }

    public function remove(Request $request)
    {
        $request->validate(['food_id' => 'required|exists:food,id']);

        $cart = Cart::where('user_id', $request->user()->id)->first();
        if ($cart) {
            CartItem::where('cart_id', $cart->id)
                ->where('food_id', $request->food_id)
                ->delete();
        }

        return response()->json([
            'message' => 'Item removed',
            'cart' => $cart ? $this->formatCart($cart) : null
        ]);
    }

    public function clear(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }

        return response()->json(['message' => 'Cart cleared successfully']);
    }

    public function addToCart(Request $request)
    {
        return $this->add($request);
    }

    public function getCart(Request $request)
    {
        return $this->index($request);
    }
}
