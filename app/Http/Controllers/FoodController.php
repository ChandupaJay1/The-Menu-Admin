<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::with('ingredients');

        // Filter by meal type (Breakfast, Lunch, Dinner)
        if ($request->filled('type') && strtolower($request->type) !== 'all') {
            $query->where('type', 'like', $request->type);
        }

        // Filter by available day (Mon, Tue, Wed, Thu, Fri, Sat, Sun)
        if ($request->filled('day')) {
            $day = ucfirst(substr(trim($request->day), 0, 3));
            $query->where(function ($q) use ($day) {
                $q->whereJsonContains('available_days', $day)
                  ->orWhereNull('available_days');
            });
        }

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $foods = $query->orderBy('name')->get();

        return response()->json($foods);
    }

    public function show($id)
    {
        $food = Food::with('ingredients')->findOrFail($id);
        return response()->json($food);
    }
}
