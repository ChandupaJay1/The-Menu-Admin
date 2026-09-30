<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->orderBy('is_default', 'desc')->latest()->get();
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:50',
            'address' => 'required|string',
            'details' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'nullable|boolean',
        ]);

        $user = $request->user();

        // If this is the user's first address or requested as default, make it default
        $isFirst = $user->addresses()->count() === 0;
        $makeDefault = $isFirst || !empty($validated['is_default']);

        if ($makeDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $address = $user->addresses()->create([
            'label' => $validated['label'] ?? 'Home',
            'address' => $validated['address'],
            'details' => $validated['details'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'is_default' => $makeDefault,
        ]);

        // Also sync to user's main address field if default
        if ($makeDefault) {
            $user->update(['address' => $address->address]);
        }

        return response()->json($address, 201);
    }

    public function update(Request $request, $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);

        $validated = $request->validate([
            'label' => 'nullable|string|max:50',
            'address' => 'required|string',
            'details' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'nullable|boolean',
        ]);

        if (!empty($validated['is_default'])) {
            $request->user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
            $request->user()->update(['address' => $validated['address']]);
        }

        $address->update($validated);

        return response()->json($address);
    }

    public function setDefault(Request $request, $id)
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        $user->update(['address' => $address->address]);

        return response()->json([
            'message' => 'Default address updated successfully',
            'address' => $address
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        // If the deleted address was default, set the latest remaining as default
        if ($wasDefault) {
            $next = $request->user()->addresses()->latest()->first();
            if ($next) {
                $next->update(['is_default' => true]);
                $request->user()->update(['address' => $next->address]);
            }
        }

        return response()->json(['message' => 'Address deleted successfully']);
    }
}
