<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = \App\Models\Event::with('items.food.ingredients')
                                   ->where('user_id', $request->user()->id)
                                   ->orderBy('start_date', 'asc')
                                   ->get();

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'total_cost' => 'required|numeric',
            'daily_menus' => 'required|array',
        ]);

        $event = \App\Models\Event::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_cost' => $request->total_cost,
        ]);

        foreach ($request->daily_menus as $date => $items) {
            foreach ($items as $item) {
                \App\Models\EventItem::create([
                    'event_id' => $event->id,
                    'date' => $date,
                    'food_id' => $item['food_id'],
                    'meal_type' => $item['meal_type'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        return response()->json($event->load('items.food.ingredients'), 201);
    }
}
