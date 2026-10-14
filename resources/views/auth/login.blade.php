<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>The Menu - Management Console Sign In</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(28px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        @keyframes subtlePulse {
            0%, 100% { opacity: 0.25; transform: scale(1); }
            50% { opacity: 0.45; transform: scale(1.08); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.75s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .animate-glow {
            animation: subtlePulse 7s ease-in-out infinite;
        }

        .logo-blend {
            mix-blend-mode: screen !important;
            aspect-ratio: 2048 / 1374 !important;
        }

        /* High-Contrast Glass Typography Overrides */
        .glass-card-title {
            color: #ffffff !important;
        }
        .glass-card-subtitle {
            color: #d1d5db !important;
        }
        .glass-label {
            color: #ffffff !important;
        }
        .glass-input {
            color: #ffffff !important;
        }
        .glass-input::placeholder {
            color: #9ca3af !important;
            opacity: 1 !important;
        }
        .glass-icon {
            color: rgba(255, 255, 255, 0.75) !important;
        }
        .glass-btn-text {
            color: #051411 !important;
            font-weight: 900 !important;
        }

        /* Chrome / Safari / Edge Autofill Fix for dark glass inputs */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-text-fill-color: #ffffff !important;
            -webkit-box-shadow: 0 0 0px 1000px #0b1a17 inset !important;
            box-shadow: 0 0 0px 1000px #0b1a17 inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="min-h-screen w-full relative overflow-x-hidden selection:bg-[#C9A050] selection:text-white font-sans antialiased bg-[#051E1A] text-white">

    <!-- Full-Screen Luxury Restaurant Background -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=2070" 
             alt="Luxury Restaurant Interior" 
             class="w-full h-full object-cover object-center filter brightness-[0.38] contrast-105 scale-105">
        
        <!-- Luxury Atmospheric Gradient Overlays -->
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-black/75"></div>
        <div class="absolute inset-0 bg-[#051E1A]/40 mix-blend-multiply"></div>
        
        <!-- Ambient Glowing Highlights Behind Center Card -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[650px] h-[650px] bg-[#C9A050]/15 rounded-full blur-[140px] animate-glow"></div>
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl animate-glow" style="animation-delay: 3s;"></div>
    </div>

    <!-- Centered Glassmorphism Login Container -->
    <main class="relative z-10 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        
        <!-- Single Elegant Glassmorphism Card -->
        <div class="w-full max-w-md bg-white/10 backdrop-blur-md rounded-3xl p-8 sm:p-10 border border-white/20 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.8)] relative overflow-hidden animate-fade-in-up text-white" 
             x-data="{ showPassword: false }">
            
            <!-- Subtle Top Rim Light Effect -->
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>

            <!-- Logo Section (Blended naturally with mix-blend-mode: screen, no modifications to source SVG) -->
            <div class="text-center mb-8">
                <div class="relative inline-flex items-center justify-center mb-3 transition-transform duration-300 hover:scale-105">
                    <!-- Ambient gold glow accent behind the blended logo -->
                    <div class="absolute inset-0 bg-[#C9A050]/20 rounded-full blur-xl pointer-events-none transform scale-90"></div>
                    <img src="{{ asset('images/logo.svg') }}" 
                         alt="The Menu Logo" 
                         class="h-20 sm:h-24 w-auto object-contain relative z-10 logo-blend mix-blend-screen filter drop-shadow-[0_8px_16px_rgba(0,0,0,0.6)]"
                         style="mix-blend-mode: screen; aspect-ratio: 2048 / 1374;">
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight drop-shadow-sm glass-card-title">
                    Welcome Back
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 mt-1 font-medium glass-card-subtitle">
                    The Menu Management &amp; Culinary Operations
                </p>
            </div>

            <!-- Error Alerts -->
            @if (isset($errors) && $errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-950/70 border border-rose-500/50 text-rose-100 text-xs sm:text-sm shadow-lg flex items-start space-x-3 backdrop-blur-md">
                    <svg class="w-5 h-5 text-rose-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="font-bold text-white">Authentication Failed</p>
                        <ul class="list-disc list-inside mt-1 space-y-0.5 text-xs text-rose-200">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-white mb-2 glass-label">
                        Email Address
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/70 group-focus-within:text-[#C9A050] transition-colors glass-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                            </svg>
                        </div>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="admin@themenu.com" 
                               class="glass-input w-full pl-11 pr-4 py-3.5 sm:py-4 bg-black/40 hover:bg-black/50 focus:bg-black/60 border border-white/20 focus:border-[#C9A050] rounded-2xl text-sm font-semibold text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#C9A050]/25 shadow-inner transition-all duration-200 @error('email') border-rose-400 ring-2 ring-rose-500/30 @enderror">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-white glass-label">
                            Password
                        </label>
                        <a href="#" class="text-xs font-semibold text-amber-300 hover:text-amber-200 hover:underline transition-colors">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/70 group-focus-within:text-[#C9A050] transition-colors glass-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="password" 
                                :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               placeholder="••••••••••••" 
                               class="glass-input w-full pl-11 pr-12 py-3.5 sm:py-4 bg-black/40 hover:bg-black/50 focus:bg-black/60 border border-white/20 focus:border-[#C9A050] rounded-2xl text-sm font-semibold text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#C9A050]/25 shadow-inner transition-all duration-200 @error('password') border-rose-400 ring-2 ring-rose-500/30 @enderror">
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/70 hover:text-white transition-colors focus:outline-none cursor-pointer glass-icon"
                                aria-label="Toggle password visibility">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center space-x-2.5 cursor-pointer select-none">
                        <input type="checkbox" 
                               name="remember" 
                               value="1" 
                               class="w-4 h-4 rounded-md border-white/30 bg-black/40 text-[#C9A050] focus:ring-[#C9A050] focus:ring-offset-0 transition-colors cursor-pointer">
                        <span class="text-xs font-semibold text-white glass-label">Remember on this device</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-4 px-6 bg-gradient-to-r from-[#C9A050] via-[#D4AF37] to-[#B38E46] hover:from-[#D4AF37] hover:to-[#C9A050] text-gray-950 hover:text-black font-black text-sm sm:text-base tracking-wide rounded-2xl shadow-[0_12px_28px_-6px_rgba(201,160,80,0.5)] hover:shadow-[0_18px_36px_-6px_rgba(201,160,80,0.7)] hover:brightness-105 active:scale-[0.98] transition-all duration-300 flex items-center justify-center space-x-2 group cursor-pointer border border-[#E2BD6D]/50">
                    <span class="glass-btn-text text-gray-950 font-black tracking-wide">Sign In to Dashboard</span>
                    <svg class="w-4 h-4 text-gray-950 group-hover:translate-x-1 transition-transform glass-btn-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>

            <!-- Roles Info Badges -->
            <div class="mt-8 pt-6 border-t border-white/10">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-300 text-center mb-3">
                    Configured Staff Access
                </p>
                <div class="flex flex-wrap items-center justify-center gap-1.5 text-[10px] font-semibold">
                    <span class="px-2.5 py-1 rounded-full bg-white/10 text-white border border-white/20">Super Admin</span>
                    <span class="px-2.5 py-1 rounded-full bg-white/10 text-white border border-white/20">System Admin</span>
                    <span class="px-2.5 py-1 rounded-full bg-white/10 text-white border border-white/20">HR</span>
                    <span class="px-2.5 py-1 rounded-full bg-white/10 text-white border border-white/20">Chef</span>
                </div>
            </div>

            <p class="mt-6 text-center text-[10px] text-gray-300 font-medium">
                © 2026 The Menu Management Console &middot; Confidential
            </p>
        </div>
    </main>

</body>
</html>
