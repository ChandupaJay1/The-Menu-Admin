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
        ['route' => 'logs.index', 'label' => 'History & Logs', 'match' => 'logs*', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['route' => 'settings.checkout', 'label' => 'Settings', 'match' => 'settings*', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
    ];
@endphp

<nav class="flex-grow px-3 mt-4 space-y-1.5 overflow-y-auto custom-scrollbar">
    @foreach($navItems as $item)
        @php
            $isRestrictedForChef = in_array($item['label'], [
                'Special Offers', 'Drivers', 'Events', 'Bills & POS', 'Users', 'History & Logs', 'Settings'
            ]);
        @endphp
        @if(auth()->user()?->role === 'Chef' && $isRestrictedForChef)
            @continue
        @endif
        @php 
            $isActive = request()->routeIs($item['match']) || (isset($item['alt_match']) && request()->routeIs($item['alt_match'])); 
        @endphp
        <a href="{{ route($item['route']) }}" 
           class="flex items-center space-x-3.5 px-3.5 py-3 rounded-2xl transition-all duration-200 group {{ $isActive ? 'bg-[#C9A050] text-white shadow-lg shadow-[#C9A050]/25 font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white font-medium' }}">
            <div class="flex-shrink-0 flex items-center justify-center w-6 h-6">
                <svg class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-white/70 group-hover:text-white group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                </svg>
            </div>
            <span class="text-sm tracking-tight truncate">
                {{ $item['label'] }}
            </span>
        </a>
    @endforeach
</nav>
