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
            'meal_type' => 'nullable|string',
            'scheduled_date' => 'nullable|date',
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

        $query = CartItem::where('cart_id', $cart->id)
            ->where('food_id', $request->food_id);

        if ($request->filled('scheduled_date')) {
            $query->where('scheduled_date', $request->scheduled_date);
        } else {
            $query->whereNull('scheduled_date');
        }

        if ($request->filled('meal_type')) {
            $query->where('meal_type', $request->meal_type);
        } else {
            $query->whereNull('meal_type');
        }

        $cartItem = $query->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'food_id' => $request->food_id,
                'quantity' => $request->quantity,
                'meal_type' => $request->meal_type,
                'scheduled_date' => $request->scheduled_date,
            ]);
        }

        return response()->json($this->formatCart($cart));
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'food_id' => 'nullable|exists:food,id',
            'cart_item_id' => 'nullable|exists:cart_items,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = Cart::where('user_id', $request->user()->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $query = CartItem::where('cart_id', $cart->id);
        if ($request->filled('cart_item_id')) {
            $query->where('id', $request->cart_item_id);
        } else {
            $query->where('food_id', $request->food_id);
        }
        $cartItem = $query->first();

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
        $request->validate([
            'food_id' => 'nullable|exists:food,id',
            'cart_item_id' => 'nullable|exists:cart_items,id',
        ]);

        $cart = Cart::where('user_id', $request->user()->id)->first();
        if ($cart) {
            $query = CartItem::where('cart_id', $cart->id);
            if ($request->filled('cart_item_id')) {
                $query->where('id', $request->cart_item_id);
            } else {
                $query->where('food_id', $request->food_id);
            }
            $query->delete();
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

    public function syncPlan(Request $request)
    {
        $user = $request->user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Remove previous scheduled meals from cart to prevent duplicate stacking
        CartItem::where('cart_id', $cart->id)
            ->whereNotNull('scheduled_date')
            ->delete();

        $items = $request->input('meals', []);
        if (empty($items)) {
            $plannedMeals = \App\Models\PlannedMeal::where('user_id', $user->id)->get();
            foreach ($plannedMeals as $pm) {
                $items[] = [
                    'food_id' => $pm->food_id,
                    'quantity' => 1,
                    'meal_type' => $pm->meal_type,
                    'scheduled_date' => $pm->date ? $pm->date->format('Y-m-d') : null,
                ];
            }
        }

        foreach ($items as $item) {
            CartItem::create([
                'cart_id' => $cart->id,
                'food_id' => $item['food_id'],
                'quantity' => $item['quantity'] ?? 1,
                'meal_type' => $item['meal_type'] ?? null,
                'scheduled_date' => $item['scheduled_date'] ?? null,
            ]);
        }

        return response()->json($this->formatCart($cart));
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
