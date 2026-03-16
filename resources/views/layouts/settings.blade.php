<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <nav class="flex text-sm text-gray-500 mb-2">
                <a href="#" class="hover:text-[#C9A050]">Settings</a>
                <span class="mx-2">></span>
                <span class="text-gray-900 font-semibold">{{ $title }}</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
        </div>
        
        <div class="flex items-center space-x-4">
            <button class="p-2 bg-white rounded-xl shadow-sm text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </button>
            <div class="flex space-x-1">
                <div class="w-1.5 h-1.5 bg-gray-200 rounded-full"></div>
                <div class="w-1.5 h-1.5 bg-gray-200 rounded-full"></div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 h-[calc(100vh-200px)]">
        <!-- Settings Sidebar -->
        <div class="lg:w-1/3 bg-white rounded-[2.5rem] p-8 flex flex-col shadow-sm border border-gray-100 overflow-hidden">
            <nav class="space-y-4">
                @php
                    $menus = [
                        ['id' => 'profile', 'label' => 'Profile', 'icon' => '👤', 'route' => '#'],
                        ['id' => 'notifications', 'label' => 'Notifications', 'icon' => '🔔', 'route' => '#'],
                        ['id' => 'appearance', 'label' => 'Appearance', 'icon' => '🎨', 'route' => '#'],
                        ['id' => 'checkout', 'label' => 'Checkout settings', 'icon' => '⚙️', 'route' => route('settings.checkout')],
                        ['id' => 'security', 'label' => 'Security', 'icon' => '🛡️', 'route' => route('settings.security')],
                        ['id' => 'language', 'label' => 'Language & Region', 'icon' => '🌍', 'route' => '#'],
                    ];
                @endphp

                @foreach($menus as $menu)
                    <a href="{{ $menu['route'] }}" class="flex items-center space-x-4 p-4 rounded-2xl transition-all {{ $active == $menu['id'] ? 'bg-gray-50 text-gray-900 shadow-sm border border-gray-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50/50' }}">
                        <span class="text-xl">{{ $menu['icon'] }}</span>
                        <span class="font-bold">{{ $menu['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Settings Content -->
        <div class="lg:w-2/3 bg-white rounded-[2.5rem] flex flex-col shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex-grow overflow-y-auto p-12 custom-scrollbar">
                {{ $slot }}
            </div>
            
            <div class="p-8 border-t border-gray-50 flex justify-end">
                <button class="px-12 py-4 bg-[#C9A050] text-white rounded-2xl font-bold text-lg hover:bg-[#B38E46] transition-all shadow-lg shadow-[#C9A050]/20">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #C9A050;
        }
    </style>
</x-app-layout>