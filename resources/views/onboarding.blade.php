<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome Back - The Menue</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#E5E7EB] min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden max-w-5xl w-full flex flex-col md:flex-row min-h-[600px]">
        <!-- Left Side: Illustration -->
        <div class="md:w-1/2 bg-[#F8F9FA] p-12 flex flex-col items-center justify-center text-center">
            <div class="mb-8 w-full max-w-[350px]">
                <img src="https://cdni.iconscout.com/illustration/premium/thumb/female-executive-working-on-sales-and-inventory-illustration-download-in-svg-png-gif-formats--management-business-analysis-revenue-growth-finance-pack-illustrations-5206260.png" alt="Sales and Inventory Illustration" class="w-full h-auto">
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Manage sales, inventory<br>and other transactions</h2>
            <div class="flex space-x-2">
                <div class="w-6 h-2 bg-[#C9A050] rounded-full"></div>
                <div class="w-2 h-2 bg-gray-200 rounded-full"></div>
                <div class="w-2 h-2 bg-gray-200 rounded-full"></div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="md:w-1/2 p-12 md:p-16 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back!</h1>
                <p class="text-gray-500 mb-8">Please sign in to continue</p>

                <form action="{{ route('dashboard') }}" method="GET" class="space-y-6">
                    <div>
                        <input type="text" placeholder="Sales ID number" class="w-full px-6 py-4 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#C9A050] transition-all" required>
                    </div>
                    <div class="relative">
                        <input type="password" placeholder="Password" class="w-full px-6 py-4 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#C9A050] transition-all" required>
                        <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#C9A050] text-white rounded-xl font-bold text-lg hover:bg-[#B38E46] transition-all shadow-lg shadow-[#C9A050]/20">
                        Sign in
                    </button>

                    <div class="relative flex items-center justify-center my-8">
                        <div class="border-t border-gray-200 w-full"></div>
                        <span class="bg-white px-4 text-gray-400 text-sm absolute">or</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" class="flex items-center justify-center space-x-2 py-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            <span class="text-xs font-semibold">Add Facebook</span>
                        </button>
                        <button type="button" class="flex items-center justify-center space-x-2 py-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                            <svg class="w-5 h-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                            <span class="text-xs font-semibold">Add Google</span>
                        </button>
                    </div>

                    <div class="text-center mt-8">
                        <a href="#" class="text-[#C9A050] font-semibold hover:underline">Forgot password?</a>
                        <p class="text-gray-500 mt-2">Don't have an account? <a href="#" class="text-[#C9A050] font-semibold hover:underline">Go to Registration</a></p>
                    </div>
                </form>
            </div>
            <p class="mt-auto text-center text-gray-400 text-xs">@ 2026 The Menue POS Setup</p>
        </div>
    </div>
</body>
</html>