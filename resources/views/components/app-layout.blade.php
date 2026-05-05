<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'The Menue') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#F4F4F4]">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <aside 
            class="bg-[#0A2E2A] text-white w-64 flex-shrink-0 flex flex-col transition-all duration-300 ease-in-out"
            :class="sidebarOpen ? 'ml-0' : '-ml-64'"
        >
            <div class="p-6 flex items-center space-x-2">
                <div class="bg-white/10 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-[#C9A050]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight">The Menue</span>
            </div>

            <nav class="flex-grow px-4 mt-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#C9A050] text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('orders') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('orders') ? 'bg-[#C9A050] text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span class="font-medium">Orders</span>
                </a>
                <a href="{{ route('drivers') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('drivers') ? 'bg-[#C9A050] text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6 0a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                    <span class="font-medium">Drivers</span>
                </a>
                <a href="{{ route('categories') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('categories') ? 'bg-[#C9A050] text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="font-medium">Food & Drinks</span>
                </a>
                <a href="{{ route('messages') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('messages') ? 'bg-[#C9A050] text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    <span class="font-medium">Messages</span>
                </a>
                <a href="{{ route('bills') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('bills') ? 'bg-[#C9A050] text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="font-medium">Bills</span>
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('users.*') ? 'bg-[#C9A050] text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-medium">Users</span>
                </a>
                <a href="{{ route('settings.checkout') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('settings.*') ? 'bg-[#C9A050] text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="font-medium">Settings</span>
                </a>
            </nav>

            <div class="px-6 py-8 mt-auto">
                <div class="bg-white/5 rounded-2xl p-4">
                    <div class="flex items-center space-x-3">
                        <img src="https://ui-avatars.com/api/?name=Joy+Ezechukwu&background=C9A050&color=fff" class="w-10 h-10 rounded-xl" alt="User">
                        <div class="overflow-hidden">
                            <p class="text-sm font-semibold truncate">Joy Ezechukwu</p>
                            <p class="text-xs text-white/40 truncate">Supervisor</p>
                        </div>
                    </div>
                    <button class="w-full mt-4 py-2 text-xs font-medium text-white/60 hover:text-white transition-colors border border-white/10 rounded-lg">
                        Open profile
                    </button>
                </div>
                <p class="mt-6 text-[10px] text-white/20 text-center">@ 2026 The Menue POS</p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-grow overflow-y-auto">
            <header class="p-6 flex items-center justify-between">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-white rounded-xl shadow-sm text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                
                <div class="flex items-center space-x-4" x-data="{ notificationsOpen: false }">
                    <div class="relative">
                        <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 bg-white border-none rounded-xl shadow-sm focus:ring-2 focus:ring-[#C9A050] w-64 text-sm">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    
                    <!-- Notifications Dropdown -->
                    <div class="relative">
                        <button @click="notificationsOpen = !notificationsOpen" @click.away="notificationsOpen = false" class="p-2 bg-white rounded-xl shadow-sm text-gray-400 hover:text-gray-600 relative transition-all active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-orange-500 rounded-full border-2 border-white"></span>
                        </button>

                        <div x-show="notificationsOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             class="absolute right-0 mt-3 w-80 bg-white rounded-[2rem] shadow-2xl border border-gray-100 z-[110] overflow-hidden"
                             style="display: none;">
                            
                            <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                                <h3 class="font-bold text-gray-900">Notifications</h3>
                                <span class="px-2 py-1 bg-orange-50 text-orange-500 text-[10px] font-bold rounded-full uppercase">3 New</span>
                            </div>

                            <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                                <!-- Notification 1 -->
                                <a href="#" class="flex items-start p-4 hover:bg-gray-50 transition-colors border-b border-gray-50">
                                    <div class="p-2 bg-blue-50 rounded-xl mr-3">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <p class="text-sm font-bold text-gray-900 truncate">New Regular Order!</p>
                                        <p class="text-xs text-gray-500 truncate">Order #ORD-2024-001 has been placed.</p>
                                        <p class="text-[10px] text-gray-400 mt-1">2 mins ago</p>
                                    </div>
                                </a>

                                <!-- Notification 2 -->
                                <a href="#" class="flex items-start p-4 hover:bg-gray-50 transition-colors border-b border-gray-50">
                                    <div class="p-2 bg-amber-50 rounded-xl mr-3">
                                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <p class="text-sm font-bold text-gray-900 truncate">Event Reminder</p>
                                        <p class="text-xs text-gray-500 truncate">Wedding event for Mr. Sunil is tomorrow.</p>
                                        <p class="text-[10px] text-gray-400 mt-1">1 hour ago</p>
                                    </div>
                                </a>

                                <!-- Notification 3 -->
                                <a href="#" class="flex items-start p-4 hover:bg-gray-50 transition-colors">
                                    <div class="p-2 bg-green-50 rounded-xl mr-3">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <p class="text-sm font-bold text-gray-900 truncate">Driver Assigned</p>
                                        <p class="text-xs text-gray-500 truncate">Saman has been assigned to #ORD-2024-001.</p>
                                        <p class="text-[10px] text-gray-400 mt-1">3 hours ago</p>
                                    </div>
                                </a>
                            </div>

                            <a href="{{ route('notifications') }}" class="block p-4 bg-gray-50 text-center text-xs font-bold text-[#C9A050] hover:bg-gray-100 transition-colors">
                                View All Notifications
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <div class="px-6 pb-6">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>