<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ config('app.name', 'The Menu') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased bg-[#F8FAFC] text-slate-800">
    <div class="flex h-screen overflow-hidden" 
         x-data="{ sidebarOpen: window.innerWidth >= 1024, mobileMenuOpen: false }"
         @resize.window="if (window.innerWidth >= 1024) mobileMenuOpen = false">

        <!-- Mobile Drawer Backdrop -->
        <div x-show="mobileMenuOpen" 
             x-cloak
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 lg:hidden"
             @click="mobileMenuOpen = false"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Sidebar (Desktop Responsive Rail / Mobile Off-canvas Drawer) -->
        <aside 
            class="bg-[#0A2E2A] text-white flex flex-col shrink-0 transition-all duration-300 ease-in-out fixed inset-y-0 left-0 z-50 w-72 lg:static lg:z-auto border-r border-white/10"
            :class="{
                'translate-x-0': mobileMenuOpen,
                '-translate-x-full lg:translate-x-0': !mobileMenuOpen,
                'lg:w-64': sidebarOpen,
                'lg:w-20': !sidebarOpen
            }"
        >
            <!-- Brand Header -->
            <div class="p-5 sm:p-6 flex items-center justify-between border-b border-white/10">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 overflow-hidden">
                    <div class="bg-gradient-to-br from-[#C9A050] to-[#B38E46] p-2.5 rounded-2xl shadow-md shadow-black/20 flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="transition-opacity duration-200" :class="sidebarOpen ? 'opacity-100' : 'lg:hidden'">
                        <span class="text-xl font-black tracking-tight text-white block leading-tight">The Menu</span>
                        <span class="text-[10px] text-[#C9A050] uppercase font-bold tracking-widest block">Administration</span>
                    </div>
                </a>

                <!-- Mobile Close Drawer Button -->
                <button @click="mobileMenuOpen = false" class="lg:hidden p-2 text-white/60 hover:text-white rounded-xl hover:bg-white/10 transition-colors" aria-label="Close menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-grow px-3 mt-4 space-y-1.5 overflow-y-auto custom-scrollbar">
                @php
                    $navItems = [
                        ['route' => 'dashboard', 'label' => 'Dashboard', 'match' => 'dashboard', 'icon' => 'M4 6h16M4 12h16m-7 6h7'],
                        ['route' => 'orders', 'label' => 'Orders', 'match' => 'orders*', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                        ['route' => 'offers', 'label' => 'Special Offers', 'match' => 'offers*', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                        ['route' => 'drivers', 'label' => 'Drivers', 'match' => 'drivers*', 'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6 0a1 1 0 001 1h1'],
                        ['route' => 'events', 'label' => 'Events', 'match' => 'events*', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['route' => 'foods.index', 'label' => 'Food & Menu', 'match' => 'foods*', 'alt_match' => 'categories*', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['route' => 'messages', 'label' => 'Messages', 'match' => 'messages*', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                        ['route' => 'notifications', 'label' => 'Notifications', 'match' => 'notifications*', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                        ['route' => 'bills', 'label' => 'Bills & POS', 'match' => 'bills*', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['route' => 'users.index', 'label' => 'Users', 'match' => 'users*', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                        ['route' => 'settings.checkout', 'label' => 'Settings', 'match' => 'settings*', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php 
                        $isActive = request()->routeIs($item['match']) || (isset($item['alt_match']) && request()->routeIs($item['alt_match'])); 
                    @endphp
                    <a href="{{ route($item['route']) }}" 
                       class="flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl transition-all duration-200 group {{ $isActive ? 'bg-[#C9A050] text-white shadow-lg shadow-[#C9A050]/25 font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white font-medium' }}"
                       :title="sidebarOpen ? '' : '{{ $item['label'] }}'">
                        <div class="flex-shrink-0 flex items-center justify-center w-6 h-6">
                            <svg class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-white/70 group-hover:text-white group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                            </svg>
                        </div>
                        <span class="text-sm tracking-tight truncate transition-opacity duration-200" :class="sidebarOpen ? 'opacity-100' : 'lg:hidden'">
                            {{ $item['label'] }}
                        </span>
                    </a>
                @endforeach
            </nav>

            <!-- User Footer Card -->
            <div class="mt-auto p-4 border-t border-white/10">
                <div class="bg-white/5 rounded-2xl p-3.5 border border-white/10" :class="sidebarOpen ? '' : 'lg:p-2'">
                    <div class="flex items-center space-x-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=C9A050&color=fff&bold=true" 
                             class="w-10 h-10 rounded-xl flex-shrink-0 shadow-sm border border-white/20" 
                             alt="{{ auth()->user()->name ?? 'User' }}">
                        <div class="overflow-hidden min-w-0 flex-1" :class="sidebarOpen ? '' : 'lg:hidden'">
                            <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-xs text-white/50 truncate">{{ auth()->user()->email ?? 'admin@themenu.com' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" :class="sidebarOpen ? 'block' : 'lg:hidden'">
                        @csrf
                        <button type="submit" class="w-full mt-3 py-2 text-xs font-bold text-white/70 hover:text-white hover:bg-white/10 transition-colors border border-white/10 rounded-xl flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>Log out</span>
                        </button>
                    </form>
                </div>
                <p class="mt-3 text-[10px] text-white/30 text-center" :class="sidebarOpen ? '' : 'lg:hidden'">© 2026 The Menu Management</p>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto overflow-x-hidden">
            <!-- Modern Header Bar -->
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md px-4 sm:px-8 py-3.5 flex items-center justify-between border-b border-gray-100 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
                <div class="flex items-center space-x-3">
                    <!-- Mobile Hamburger Button -->
                    <button @click="mobileMenuOpen = true" 
                            class="lg:hidden p-2.5 bg-gray-50 hover:bg-gray-100 rounded-2xl text-gray-700 active:scale-95 transition-all"
                            aria-label="Open navigation">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    <!-- Desktop Sidebar Rail Toggle -->
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="hidden lg:flex p-2.5 bg-gray-50 hover:bg-gray-100 rounded-2xl text-gray-600 hover:text-[#C9A050] hover:shadow-xs active:scale-95 transition-all"
                            :title="sidebarOpen ? 'Collapse sidebar' : 'Expand sidebar'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    <!-- Mobile Title -->
                    <span class="text-base font-bold text-gray-900 lg:hidden truncate">The Menu Admin</span>
                </div>
                
                <div class="flex items-center space-x-3 sm:space-x-4" x-data="{ notificationsOpen: false }">
                    <!-- Global Search Box -->
                    <div class="relative w-36 sm:w-56 md:w-64">
                        <input type="text" 
                               placeholder="Search..." 
                               class="input pl-9 pr-3 py-2 bg-gray-50 hover:bg-gray-100/80 focus:bg-white rounded-2xl border border-transparent focus:border-[#C9A050]/40 w-full text-xs sm:text-sm font-medium transition-all">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5 sm:top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    
                    <!-- Notifications Dropdown -->
                    <div class="relative">
                        <button @click="notificationsOpen = !notificationsOpen" 
                                @click.away="notificationsOpen = false" 
                                class="p-2.5 bg-gray-50 hover:bg-gray-100 rounded-2xl text-gray-600 hover:text-[#C9A050] active:scale-95 transition-all relative"
                                aria-label="Notifications">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-orange-500 rounded-full border-2 border-white"></span>
                        </button>

                        <div x-show="notificationsOpen" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             class="absolute right-0 mt-3 w-[calc(100vw-2rem)] max-w-sm sm:w-80 bg-white rounded-3xl shadow-2xl border border-gray-100 z-[110] overflow-hidden">
                            
                            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                <h3 class="font-bold text-gray-900 text-sm">Notifications</h3>
                                <span class="px-2.5 py-1 bg-amber-50 text-[#C9A050] text-[10px] font-bold rounded-full uppercase border border-[#C9A050]/20">Active Updates</span>
                            </div>

                            <div class="max-h-[360px] overflow-y-auto custom-scrollbar divide-y divide-gray-50">
                                <a href="{{ route('orders') }}" class="flex items-start p-4 hover:bg-gray-50/80 transition-colors">
                                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-gray-900 truncate">Orders Live Sync</p>
                                        <p class="text-[11px] text-gray-500 truncate">Mobile orders and meal plan orders updated.</p>
                                        <p class="text-[10px] text-gray-400 mt-1">Real-time DB connection active</p>
                                    </div>
                                </a>

                                <a href="{{ route('drivers') }}" class="flex items-start p-4 hover:bg-gray-50/80 transition-colors">
                                    <div class="p-2.5 bg-green-50 text-green-600 rounded-xl mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-gray-900 truncate">Driver Fleet</p>
                                        <p class="text-[11px] text-gray-500 truncate">Fleet availability status is tracked live.</p>
                                        <p class="text-[10px] text-gray-400 mt-1">Ready for dispatch</p>
                                    </div>
                                </a>
                            </div>

                            <a href="{{ route('notifications') }}" class="block p-3.5 bg-gray-50 text-center text-xs font-bold text-[#C9A050] hover:bg-gray-100 transition-colors">
                                View Notification Center →
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Body Content -->
            <div class="p-4 sm:p-6 lg:p-8 flex-1">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col space-y-2 pointer-events-none"></div>
</body>
</html>
