<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Event;
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

    /**
     * Web listing of events (admin) with driver assignment support.
     */
    public function indexWeb()
    {
        $events = Event::with(['items.food', 'driver', 'user'])->latest('start_date')->get();

        $stats = [
            'total'      => $events->count(),
            'upcoming'   => $events->where('start_date', '>=', now()->startOfDay())->count(),
            'assigned'   => $events->whereNotNull('driver_id')->count(),
            'unassigned' => $events->whereNull('driver_id')->count(),
        ];

        $drivers = Driver::orderBy('name')->get();

        return view('events', compact('events', 'stats', 'drivers'));
    }

    /**
     * Assign (or unassign) a driver to an event.
     * Only "available" drivers (or the one already assigned to this event) may be assigned.
     */
    public function assignDriver(Request $request, Event $event)
    {
        $driverId = $request->input('driver_id') ?: null;
        $request->merge(['driver_id' => $driverId]);

        $request->validate([
            'driver_id' => ['nullable', 'exists:drivers,id'],
        ]);

        $previousDriver = $event->driver;

        if ($driverId) {
            $driver = Driver::findOrFail($driverId);

            if ($previousDriver && $previousDriver->id !== $driver->id) {
                $previousDriver->update(['status' => 'available']);
                \App\Events\DriverStatusUpdated::safeDispatch($previousDriver);
            }

            if ($driver->status !== 'available' && $driver->id !== ($previousDriver?->id)) {
                return redirect()->back()->with('error', 'The selected driver is not available.');
            }

            $driver->update(['status' => 'on_delivery']);
            $event->update(['driver_id' => $driver->id]);

            \App\Events\DriverStatusUpdated::safeDispatch($driver);

            return redirect()->route('events')->with('success', 'Driver assigned to event successfully.');
        }

        if ($previousDriver) {
            $previousDriver->update(['status' => 'available']);
            \App\Events\DriverStatusUpdated::safeDispatch($previousDriver);
        }
        $event->update(['driver_id' => null]);

        return redirect()->route('events')->with('success', 'Driver assignment removed.');
    }
}
