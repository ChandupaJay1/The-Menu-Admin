<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Normalize a phone number to stored format (without leading zero, no country code).
     * Examples: "0765917189", "+94765917189", "765917189" → "765917189"
     */
    private function normalizePhone($phone)
    {
        // Remove all non-digit characters
        $digits = preg_replace('/\D/', '', $phone);
        
        // If the number starts with '94' (country code), remove it
        if (substr($digits, 0, 2) === '94') {
            $digits = substr($digits, 2);
        }
        
        // Remove leading zeros
        $digits = ltrim($digits, '0');
        
        return $digits;
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Normalize phone number if provided
        if (!empty($validated['phone'])) {
            $validated['phone'] = $this->normalizePhone($validated['phone']);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
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
        $user = null;

        // 1. Try to find User by email
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login)->first();
        }

        // 2. If not found, treat as phone number and normalize
        if (!$user) {
            $normalizedPhone = $this->normalizePhone($login);
            $user = User::where('phone', $normalizedPhone)->first();
        }

        // 3. Fallback: try raw match on phone or email
        if (!$user) {
            $user = User::where('email', $login)->orWhere('phone', $login)->first();
        }

        // 4. If User found, verify password
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }

        // 5. If User not found or password mismatched, check if it's a Driver
        $driver = null;
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $driver = \App\Models\Driver::where('email', $login)->first();
        }
        if (!$driver) {
            $normalizedPhone = $this->normalizePhone($login);
            $driver = \App\Models\Driver::where('phone', $normalizedPhone)->first();
        }
        if (!$driver) {
            $driver = \App\Models\Driver::where('email', $login)->orWhere('phone', $login)->first();
        }

        if ($driver && Hash::check($request->password, $driver->password)) {
            $token = $driver->createToken('driver_auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'driver' => $driver,
                'user' => $driver,
                'role' => 'driver',
            ]);
        }

        return response()->json([
            'message' => 'Invalid login details'
        ], 401);
    }

    public function user(Request $request)
    {
        return response()->json($request->user()->load('addresses'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'profile_photo_url' => 'nullable|string',
        ]);

        if (!empty($validated['phone'])) {
            $validated['phone'] = $this->normalizePhone($validated['phone']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh(['addresses']),
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password does not match'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
        ]);

        $login = $request->login;

        // Try email first
        $user = null;
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login)->first();
        }

        // Try phone
        if (!$user) {
            $normalizedPhone = $this->normalizePhone($login);
            $user = User::where('phone', $normalizedPhone)->first();
        }

        // Fallback raw match
        if (!$user) {
            $user = User::where('email', $login)->orWhere('phone', $login)->first();
        }

        if (!$user) {
            return response()->json([
                'message' => 'User not found with this email or phone.'
            ], 404);
        }

        $otp = sprintf("%06d", mt_rand(1, 999999));
        $identifier = $user->email ?? $user->phone;

        \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $identifier)
            ->delete();

        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->insert([
            'email' => $identifier,
            'token' => Hash::make($otp),
            'created_at' => now(),
        ]);

        \Illuminate\Support\Facades\Log::info("Password reset OTP for user {$identifier}: {$otp}");

        return response()->json([
            'message' => 'Password reset OTP generated successfully.',
            'identifier' => $identifier,
            'dev_otp' => config('app.debug') ? $otp : null,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'otp' => 'required|string',
        ]);

        $tokenRecord = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->identifier)
            ->first();

        if (!$tokenRecord) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        if (!Hash::check($request->otp, $tokenRecord->token)) {
            return response()->json(['message' => 'Invalid OTP code.'], 400);
        }

        $createdAt = \Carbon\Carbon::parse($tokenRecord->created_at);
        if ($createdAt->addMinutes(15)->isPast()) {
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->identifier)->delete();
            return response()->json(['message' => 'OTP has expired.'], 400);
        }

        return response()->json([
            'message' => 'OTP verified successfully.',
            'verified' => true,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'otp' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $tokenRecord = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->identifier)
            ->first();

        if (!$tokenRecord) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        if (!Hash::check($request->otp, $tokenRecord->token)) {
            return response()->json(['message' => 'Invalid OTP code.'], 400);
        }

        $createdAt = \Carbon\Carbon::parse($tokenRecord->created_at);
        if ($createdAt->addMinutes(15)->isPast()) {
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->identifier)->delete();
            return response()->json(['message' => 'OTP has expired.'], 400);
        }

        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $this->normalizePhone($request->identifier))
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->identifier)->delete();

        return response()->json([
            'message' => 'Password has been successfully reset.'
        ]);
    }

    /**
     * Show the web login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle a web authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'These credentials do not match our records.',
                ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Log the user out of the web session or revoke API token.
     */
    public function logout(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            $user = $request->user();
            if ($user && $user->currentAccessToken()) {
                $user->currentAccessToken()->delete();
            }
            return response()->json(['message' => 'Successfully logged out']);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}