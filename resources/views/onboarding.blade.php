<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Menu - Welcome Back</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#E5E7EB] min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden max-w-5xl w-full flex flex-col md:flex-row min-h-[600px]">
        <!-- Left Side: Illustration -->
        <div class="hidden lg:block w-1/2 bg-cover bg-center rounded-l-2xl" style="background-image: url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&q=80&w=800');"></div>

        <!-- Right Side: Login Form -->
        <div class="md:w-1/2 p-12 md:p-16 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back!</h1>
                <p class="text-gray-500 mb-8">Please sign in to continue</p>

                <form action="{{ url('/login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" class="w-full px-6 py-4 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#C9A050] transition-all" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative">
                        <input id="password-field" type="password" name="password" placeholder="Password" class="w-full px-6 py-4 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#C9A050] transition-all" required>
                        <button type="button" onclick="const p = document.getElementById('password-field'); p.type = p.type === 'password' ? 'text' : 'password';" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl text-lg shadow-lg bg-green-600 hover:bg-green-700 text-white font-bold transition-all">
                        Sign in
                    </button>

                    <div class="text-center mt-12">
                        <a href="#" class="text-[#C9A050] font-semibold hover:underline">Forgot password?</a>
                        <p class="text-gray-500 mt-2">Don't have an account? <a href="#" class="text-[#C9A050] font-semibold hover:underline">Go to Registration</a></p>
                    </div>
                </form>
            </div>
            <p class="mt-auto text-center text-gray-400 text-xs">@ 2026 The Menu POS Setup</p>
        </div>
    </div>
</body>
</html>