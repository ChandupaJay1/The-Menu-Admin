<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DriverAppAuthController extends Controller
{
    // ─── Register ──────────────────────────────────────────────────────
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|string|email|max:255|unique:drivers',
            'phone'                 => 'required|string|max:20',
            'password'              => 'required|string|min:8|confirmed',
            'vehicle_type'          => 'required|string|max:50',
            'vehicle_number'        => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $driver = Driver::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'password'       => Hash::make($request->password),
            'vehicle_type'   => $request->vehicle_type,
            'vehicle_number' => $request->vehicle_number,
            'is_active'      => true,
        ]);

        return response()->json([
            'message' => 'Account created successfully. Please login.',
            'driver'  => $driver->makeHidden('password'),
        ], 201);
    }

    // ─── Login ─────────────────────────────────────────────────────────
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $driver = Driver::where('email', $request->email)->first();

        if (!$driver || !Hash::check($request->password, $driver->password)) {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401);
        }

        // Revoke all previous tokens
        $driver->tokens()->delete();

        $token = $driver->createToken('driver-app-token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully.',
            'token'   => $token,
            'driver'  => $driver->makeHidden('password'),
        ]);
    }

    // ─── Logout ────────────────────────────────────────────────────────
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    // ─── Get current user ──────────────────────────────────────────────
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'driver' => $request->user()->makeHidden('password'),
        ]);
    }

    // ─── Change Password ──────────────────────────────────────────────
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password'      => 'required|string',
            'new_password'          => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $driver = $request->user();

        if (!Hash::check($request->current_password, $driver->password)) {
            return response()->json([
                'message' => 'The provided password does not match your current password.',
            ], 422);
        }

        $driver->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }

    // ─── Reset Password (Forgot Password) ──────────────────────────────
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email|exists:drivers,email',
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'email.exists' => 'No driver account found with this email address.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $driver = Driver::where('email', $request->email)->first();

        if (!$driver) {
            return response()->json([
                'message' => 'Driver account not found.',
            ], 404);
        }

        $driver->update([
            'password' => Hash::make($request->password),
        ]);

        // Revoke all previous tokens for security
        $driver->tokens()->delete();

        return response()->json([
            'message' => 'Password has been reset successfully. Please login with your new password.',
        ]);
    }

    // ─── Update Driver Status (Online / Offline) ──────────────────────
    public function updateDriverStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status'    => 'required|string|in:available,offline,busy,on_delivery',
            'driver_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        /** @var Driver|null $driver */
        $driver = $request->user();
        if (!$driver && $request->has('driver_id')) {
            $driver = Driver::find($request->driver_id);
        }

        if (!$driver) {
            return response()->json([
                'message' => 'Driver not found or unauthenticated.',
            ], 404);
        }

        $isOnline = in_array($request->status, ['available', 'busy', 'on_delivery']);

        $driver->update([
            'status'    => $request->status,
            'is_active' => $isOnline,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Driver status updated successfully.',
            'status'  => $driver->status,
            'is_active' => $driver->is_active,
        ]);
    }
}
