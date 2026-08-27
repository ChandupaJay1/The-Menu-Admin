<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in - {{ config('app.name', 'Menue') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0A2E2A] min-h-screen flex items-stretch font-sans antialiased">
    <div class="flex flex-col md:flex-row w-full min-h-screen">

        <!-- Left Panel: Branding -->
        <div class="md:w-1/2 bg-[#0A2E2A] text-white flex flex-col items-center justify-center p-12 relative overflow-hidden">
            <!-- Decorative glow -->
            <div class="absolute -top-24 -left-24 w-72 h-72 bg-[#C9A050]/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-16 w-80 h-80 bg-[#C9A050]/5 rounded-full blur-3xl"></div>

            <div class="relative z-10 max-w-md text-center">
                <div class="flex items-center justify-center space-x-3 mb-8">
                    <div class="bg-white/10 p-3 rounded-2xl ring-1 ring-[#C9A050]/40">
                        <svg class="w-8 h-8 text-[#C9A050]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <span class="text-3xl font-bold tracking-tight">Menue</span>
                </div>

                <h2 class="text-2xl font-semibold text-white mb-3">Restaurant Admin Console</h2>
                <p class="text-white/50 leading-relaxed">
                    Manage sales, inventory, orders and deliveries from one secure dashboard.
                </p>

                <div class="mt-10 flex items-center justify-center space-x-2">
                    <div class="w-8 h-1.5 bg-[#C9A050] rounded-full"></div>
                    <div class="w-1.5 h-1.5 bg-white/20 rounded-full"></div>
                    <div class="w-1.5 h-1.5 bg-white/20 rounded-full"></div>
                </div>
            </div>

            <p class="absolute bottom-6 text-[10px] text-white/20">@ 2026 Menue POS</p>
        </div>

        <!-- Right Panel: Login Form -->
        <div class="md:w-1/2 bg-[#F4F4F4] flex items-center justify-center p-6 md:p-12">
            <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl p-8 md:p-10">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Welcome Back</h1>
                    <p class="text-gray-500 mt-2">Please sign in to continue to your dashboard.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                        <div class="font-semibold mb-1">Unable to sign in</div>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autofocus
                            required
                            autocomplete="username"
                            placeholder="you@example.com"
                            class="w-full px-5 py-3.5 bg-[#F3F4F6] border border-transparent rounded-xl focus:ring-2 focus:ring-[#C9A050] focus:bg-white focus:border-[#C9A050]/40 transition-all outline-none text-sm @error('email') border-red-300 ring-1 ring-red-200 @enderror"
                        >
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <a href="#" class="text-xs font-semibold text-[#C9A050] hover:underline">Forgot password?</a>
                        </div>
                        <div class="relative" x-data="{ show: false }">
                            <input
                                id="password"
                                :type="show ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full px-5 py-3.5 bg-[#F3F4F6] border border-transparent rounded-xl focus:ring-2 focus:ring-[#C9A050] focus:bg-white focus:border-[#C9A050]/40 transition-all outline-none text-sm pr-12"
                            >
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center space-x-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" value="1" class="w-4 h-4 rounded border-gray-300 text-[#C9A050] focus:ring-[#C9A050]">
                        <span class="text-sm text-gray-600">Keep me signed in</span>
                    </label>

                    <button type="submit" class="w-full py-3.5 bg-[#C9A050] text-white rounded-xl font-bold text-lg hover:bg-[#B38E46] transition-all shadow-lg shadow-[#C9A050]/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C9A050]">
                        Sign in
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-gray-400">@ 2026 Menue POS &middot; Secure Admin Access</p>
            </div>
        </div>
    </div>
</body>
</html>
