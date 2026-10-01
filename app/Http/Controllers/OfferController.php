<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Offer;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * API for Mobile App: Return all active promotional offers.
     */
    public function apiIndex()
    {
        $offers = Offer::with(['food.ingredients'])
            ->where('is_active', true)
            ->latest()
            ->get();

        return response()->json($offers);
    }

    /**
     * Web Admin: List all offers for admin management.
     */
    public function index()
    {
        $offers = Offer::with('food')->latest()->paginate(10);
        $foods = Food::orderBy('name')->get();

        return view('offers', compact('offers', 'foods'));
    }

    /**
     * Web Admin: Create a new offer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'badge_text' => 'nullable|string|max:50',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'food_id' => 'nullable|exists:food,id',
            'banner_image_url' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        $offer = Offer::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'badge_text' => $validated['badge_text'] ?: 'SPECIAL OFFER',
            'discount_percentage' => $validated['discount_percentage'] ?? 0,
            'food_id' => $validated['food_id'] ?? null,
            'banner_image_url' => $validated['banner_image_url'] ?? null,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        // Optionally notify existing users of the new offer
        try {
            $users = User::take(50)->get();
            foreach ($users as $user) {
                UserNotification::create([
                    'user_id' => $user->id,
                    'title' => 'New Special Offer! 🏷️',
                    'message' => $offer->title . ($offer->discount_percentage ? " - {$offer->discount_percentage}% OFF!" : ''),
                    'type' => 'promo',
                    'data' => [
                        'offer_id' => $offer->id,
                        'food_id' => $offer->food_id,
                    ],
                    'is_read' => false,
                ]);
            }
        } catch (\Throwable $e) {
            // Silently continue if notification broadcast fails
        }

        if ($request->wantsJson()) {
            return response()->json($offer->load('food'), 201);
        }

        return redirect()->route('offers')->with('success', 'Offer created successfully!');
    }

    /**
     * Web Admin: Update an existing offer.
     */
    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'badge_text' => 'nullable|string|max:50',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'food_id' => 'nullable|exists:food,id',
            'banner_image_url' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        $offer->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'badge_text' => $validated['badge_text'] ?: 'SPECIAL OFFER',
            'discount_percentage' => $validated['discount_percentage'] ?? 0,
            'food_id' => $validated['food_id'] ?? null,
            'banner_image_url' => $validated['banner_image_url'] ?? null,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        if ($request->wantsJson()) {
            return response()->json($offer->load('food'));
        }

        return redirect()->route('offers')->with('success', 'Offer updated successfully!');
    }

    /**
     * Web Admin: Delete an offer.
     */
    public function destroy(Request $request, Offer $offer)
    {
        $offer->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Offer deleted successfully']);
        }

        return redirect()->route('offers')->with('success', 'Offer deleted successfully!');
    }

    /**
     * Web Admin: Toggle offer active state.
     */
    public function toggle(Offer $offer)
    {
        $offer->update(['is_active' => !$offer->is_active]);

        return redirect()->route('offers')->with('success', 'Offer status toggled successfully!');
    }
}
