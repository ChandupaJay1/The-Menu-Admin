<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MealPlannerController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = \App\Models\PlannedMeal::with('food.ingredients')
            ->where('user_id', $request->user()->id);

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $meals = $query->orderBy('date', 'asc')->get();

        return response()->json($meals);
    }

    public function store(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:food,id',
            'meal_type' => 'required|string',
            'date' => 'required|date',
            'total_price' => 'required|numeric',
            'selected_extras' => 'nullable|array',
        ]);

        $meal = \App\Models\PlannedMeal::create([
            'user_id' => $request->user()->id,
            'food_id' => $request->food_id,
            'meal_type' => $request->meal_type,
            'date' => $request->date,
            'total_price' => $request->total_price,
            'selected_extras' => $request->selected_extras ?? [],
        ]);

        return response()->json($meal->load('food.ingredients'), 201);
    }
}
