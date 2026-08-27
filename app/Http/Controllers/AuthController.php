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
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->login;
        $user = null;

        // 1. Try to find by email (if login looks like an email)
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login)->first();
        }

        // 2. If not found, treat as phone number and normalize
        if (!$user) {
            $normalizedPhone = $this->normalizePhone($login);
            $user = User::where('phone', $normalizedPhone)->first();
        }

        // 3. If still not found, check if the normalized phone matches any user's phone
        // (already covered by step 2, but we keep it for clarity)

        // 4. Verify password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
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
     * Log the user out of the web session.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}