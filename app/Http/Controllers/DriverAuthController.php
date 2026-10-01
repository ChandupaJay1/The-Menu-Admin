<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Driver;

class DriverAuthController extends Controller
{
    /**
     * Normalize a phone number to stored format (without leading zero, no country code).
     */
    private function normalizePhone($phone)
    {
        $digits = preg_replace('/\D/', '', $phone);
        if (substr($digits, 0, 2) === '94') {
            $digits = substr($digits, 2);
        }
        return ltrim($digits, '0');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:drivers',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|unique:drivers',
            'vehicle_type' => 'nullable|string',
            'vehicle_number' => 'nullable|string',
        ]);

        $validated['phone'] = $this->normalizePhone($validated['phone']);

        $driver = Driver::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'vehicle_type' => $validated['vehicle_type'] ?? null,
            'vehicle_number' => $validated['vehicle_number'] ?? null,
            'status' => 'offline', // Default status upon registration (offline until switched on)
        ]);

        $token = $driver->createToken('driver_auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'driver' => $driver
        ]);
    }

    public function login(Request $request)
    {
        $loginInput = $request->input('login') 
            ?? $request->input('email') 
            ?? $request->input('phone') 
            ?? $request->input('username');

        if (!$loginInput) {
            return response()->json([
                'message' => 'The login field is required.',
                'errors' => [
                    'login' => ['The login, email, or phone field is required.']
                ]
            ], 422);
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        $login = $loginInput;
        $driver = null;

        // Try email first
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $driver = Driver::where('email', $login)->first();
        }

        // Try phone
        if (!$driver) {
            $normalizedPhone = $this->normalizePhone($login);
            $driver = Driver::where('phone', $normalizedPhone)->first();
        }

        // Fallback: raw match on email or phone
        if (!$driver) {
            $driver = Driver::where('email', $login)->orWhere('phone', $login)->first();
        }

        if (!$driver || !Hash::check($request->password, $driver->password)) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $token = $driver->createToken('driver_auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'driver' => $driver,
            'user' => $driver
        ]);
    }

    public function user(Request $request)
    {
        $driver = $request->user();
        return response()->json([
            'driver' => $driver,
            'user' => $driver,
            'status' => $driver->status,
            'is_online' => $driver->status !== 'offline',
        ]);
    }

    public function getStatus(Request $request)
    {
        $driver = $request->user();
        return response()->json([
            'status' => $driver->status,
            'is_online' => $driver->status !== 'offline',
            'driver' => $driver,
            'user' => $driver,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $driver = $request->user();
        $rawStatus = $request->input('status');
        $isOnlineInput = $request->input('is_online') ?? $request->input('online');

        if ($isOnlineInput !== null) {
            $isOnline = filter_var($isOnlineInput, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($isOnline === null) {
                $isOnline = (bool) $isOnlineInput;
            }
            $targetStatus = $isOnline ? 'available' : 'offline';
        } elseif ($rawStatus !== null) {
            $normalized = strtolower(trim((string)$rawStatus));
            if (in_array($normalized, ['available', 'online', 'ready', 'active'])) {
                $targetStatus = 'available';
            } elseif (in_array($normalized, ['offline', 'inactive', 'idle'])) {
                $targetStatus = 'offline';
            } elseif (in_array($normalized, ['on_delivery', 'busy', 'delivering'])) {
                $targetStatus = 'on_delivery';
            } else {
                $targetStatus = 'available';
            }
        } else {
            // Default toggle
            $targetStatus = $driver->status === 'offline' ? 'available' : 'offline';
        }

        // Prevent changing status if currently on delivery, unless forced offline
        if ($driver->status === 'on_delivery' && $targetStatus === 'available') {
            // still keep on delivery if driver has active orders
        }

        $driver->update(['status' => $targetStatus]);

        // Safely broadcast the event
        \App\Events\DriverStatusUpdated::safeDispatch($driver);

        return response()->json([
            'message' => 'Status updated successfully',
            'status' => $driver->status,
            'is_online' => $driver->status !== 'offline',
            'driver' => $driver,
            'user' => $driver,
        ]);
    }

    public function orders(Request $request)
    {
        $driver = $request->user();
        $orders = \App\Models\Order::with(['items.food', 'user'])
            ->where('driver_id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $driver = $request->user();
        $order = \App\Models\Order::with(['user', 'driver', 'items.food'])->where('id', $id)
            ->where('driver_id', $driver->id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found or not assigned to you'], 404);
        }

        $order->update(['status' => $request->status]);

        if ($request->status === 'delivered') {
            $driver->increment('total_deliveries');
            $driver->update(['status' => 'available']);
            \App\Events\DriverStatusUpdated::safeDispatch($driver);
        } elseif (in_array($request->status, ['on_delivery', 'on_the_way', 'picked_up'])) {
            $driver->update(['status' => 'on_delivery']);
            \App\Events\DriverStatusUpdated::safeDispatch($driver);
        }

        // Broadcast OrderStatusUpdated to admin panel
        \App\Events\OrderStatusUpdated::safeDispatch($order->fresh(['user', 'driver', 'items.food']));

        return response()->json(['message' => 'Order status updated successfully', 'order' => $order]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // can be email or phone
        ]);

        $login = $request->login;
        
        // Find driver by email or phone
        $driver = Driver::where('email', $login)
            ->orWhere('phone', $this->normalizePhone($login))
            ->orWhere('phone', $login)
            ->first();

        if (!$driver) {
            return response()->json(['message' => 'Driver not found with this email or phone.'], 404);
        }

        // Generate 6 digit OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));

        // Delete any existing tokens for this login (we'll use the existing password_reset_tokens table, 
        // storing the login in the email column)
        $identifier = $driver->email ?? $driver->phone;
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $identifier)->delete();

        // Insert new OTP
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->insert([
            'email' => $identifier,
            'token' => \Illuminate\Support\Facades\Hash::make($otp),
            'created_at' => now(),
        ]);

        // TODO: Send OTP via Email or SMS here.
        // For development/testing purposes, we'll return it in the response (remove this in production)
        \Illuminate\Support\Facades\Log::info("Password reset OTP for driver {$identifier}: {$otp}");

        return response()->json([
            'message' => 'Password reset OTP generated successfully.',
            'identifier' => $identifier, // send back the identifier to be used in the reset request
            'dev_otp' => config('app.debug') ? $otp : null, // Only return in debug mode
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string', // The email or phone returned from forgotPassword
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed', // Requires password_confirmation field in request
        ]);

        $tokenRecord = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->identifier)
            ->first();

        if (!$tokenRecord) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        // Check if OTP is valid and not expired (e.g., 15 minutes)
        if (!\Illuminate\Support\Facades\Hash::check($request->otp, $tokenRecord->token)) {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }

        $createdAt = \Carbon\Carbon::parse($tokenRecord->created_at);
        if ($createdAt->addMinutes(15)->isPast()) {
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->identifier)->delete();
            return response()->json(['message' => 'OTP has expired.'], 400);
        }

        // Find driver and update password
        $driver = Driver::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$driver) {
            return response()->json(['message' => 'Driver not found.'], 404);
        }

        $driver->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete the token
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->identifier)->delete();

        return response()->json(['message' => 'Password has been successfully reset.']);
    }
}
