<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of drivers with summary statistics.
     */
    public function index()
    {
        $drivers = Driver::latest()->get();

        $stats = [
            'total'        => $drivers->count(),
            'available'    => $drivers->where('status', 'available')->count(),
            'on_delivery'  => $drivers->where('status', 'on_delivery')->count(),
            'offline'      => $drivers->where('status', 'offline')->count(),
        ];

        return view('drivers', compact('drivers', 'stats'));
    }

    /**
     * Store a newly created driver.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['nullable', 'email', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'vehicle_type'   => ['nullable', 'string', 'max:50'],
            'vehicle_number' => ['nullable', 'string', 'max:50'],
            'status'         => ['required', 'in:available,on_delivery,offline'],
            'total_deliveries' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'Please enter the driver\'s name.',
            'email.email'   => 'Please enter a valid email address.',
            'status.required' => 'Please select a status.',
            'status.in'     => 'Please select a valid status.',
        ]);

        Driver::create([
            'name'             => $validated['name'],
            'email'            => $validated['email'] ?? null,
            'phone'            => $validated['phone'] ?? null,
            'vehicle_type'     => $validated['vehicle_type'] ?? null,
            'vehicle_number'   => $validated['vehicle_number'] ?? null,
            'status'           => $validated['status'],
            'total_deliveries' => $validated['total_deliveries'] ?? 0,
        ]);

        return redirect()->route('drivers')->with('success', 'Driver added successfully.');
    }

    /**
     * Update the status of the specified driver.
     */
    public function updateStatus(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:available,on_delivery,offline'],
        ]);

        $driver->update($validated);

        return redirect()->route('drivers')->with('success', 'Driver status updated.');
    }

    /**
     * Remove the specified driver.
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()->route('drivers')->with('success', 'Driver removed.');
    }
}
