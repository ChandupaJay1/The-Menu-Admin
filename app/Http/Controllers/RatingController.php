<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, $foodId)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $user = $request->user();
        $food = Food::findOrFail($foodId);

        $rating = Rating::updateOrCreate(
            [
                'user_id' => $user->id,
                'food_id' => $food->id,
            ],
            [
                'order_id' => $request->order_id,
                'rating' => round((float)$request->rating, 1),
                'review' => $request->review,
            ]
        );

        $newRating = $food->recalculateRating();

        return response()->json([
            'message' => 'Thank you for your rating!',
            'rating' => $rating->load('user:id,name,profile_photo_url'),
            'food_rating' => $newRating,
        ], 200);
    }

    public function index($foodId)
    {
        $food = Food::findOrFail($foodId);
        $ratings = Rating::with('user:id,name,profile_photo_url')
            ->where('food_id', $food->id)
            ->latest()
            ->paginate(15);

        return response()->json([
            'food_id' => $food->id,
            'average_rating' => $food->rating,
            'total_ratings' => Rating::where('food_id', $food->id)->count(),
            'ratings' => $ratings,
        ]);
    }
}
