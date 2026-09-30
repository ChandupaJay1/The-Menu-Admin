<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center text-sm text-gray-500 mb-2">
                <a href="#" class="hover:text-[#C9A050] transition-colors">Dashboard</a>
                <svg class="w-3.5 h-3.5 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-900 font-semibold">Sales statistics</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
        </div>
        
        <div class="flex items-center space-x-3 overflow-x-auto no-scrollbar pb-1 max-w-full">
            <div class="flex bg-white p-1 rounded-2xl shadow-xs border border-gray-100 flex-nowrap shrink-0">
                <button class="px-3.5 py-2 text-xs font-bold text-gray-400 hover:text-gray-700 rounded-xl hover:-translate-y-0.5 transition-all">Yesterday</button>
                <button class="px-4 py-2 text-xs font-bold btn-gold rounded-xl shadow-xs">Today</button>
                <button class="px-3.5 py-2 text-xs font-bold text-gray-400 hover:text-gray-700 rounded-xl hover:-translate-y-0.5 transition-all">Week</button>
                <button class="px-3.5 py-2 text-xs font-bold text-gray-400 hover:text-gray-700 rounded-xl hover:-translate-y-0.5 transition-all">Month</button>
                <button class="px-3.5 py-2 text-xs font-bold text-gray-400 hover:text-gray-700 rounded-xl hover:-translate-y-0.5 transition-all">Year</button>
            </div>
            <button class="p-2.5 bg-white rounded-2xl shadow-xs border border-gray-100 text-gray-400 hover:text-gray-600 hover:-translate-y-0.5 transition-all shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-8">
        <!-- Daily Sales Chart Card -->
        <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-bold text-gray-900">Daily sales</h3>
                <div class="w-2.5 h-2.5 bg-[#C9A050] rounded-full shadow-xs"></div>
            </div>
            <div class="h-48 flex items-end justify-between space-x-2 mb-4">
                <!-- Simple representation of a line chart -->
                @foreach([30, 45, 35, 60, 40, 55, 45] as $height)
                    <div class="flex-grow bg-gray-50 rounded-t-xl relative group h-full">
                        <div class="absolute bottom-0 left-0 right-0 bg-[#C9A050]/20 rounded-t-xl transition-all group-hover:bg-[#C9A050]/40" style="height: {{ $height }}%"></div>
                        <div class="absolute bottom-[{{ $height }}%] left-1/2 -translate-x-1/2 w-2.5 h-2.5 bg-[#C9A050] rounded-full border-2 border-white shadow-sm"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest pt-2 border-t border-gray-50">
                <span>9 AM</span>
                <span>12 PM</span>
                <span>3 PM</span>
                <span>6 PM</span>
                <span>9 PM</span>
            </div>
        </div>

        <!-- Total Revenue Donut Card -->
        <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all flex flex-col items-center">
            <div class="w-full flex items-center justify-between mb-6">
                <h3 class="font-bold text-gray-900">Total Revenue</h3>
                <button class="text-xs font-bold text-gray-400 hover:text-gray-600 flex items-center transition-colors">
                    Today
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
            <div class="relative w-44 h-44 sm:w-48 sm:h-48 flex items-center justify-center mb-6">
                <svg class="w-full h-full transform -rotate-90">
                    <circle cx="96" cy="96" r="80" stroke="#F3F4F6" stroke-width="22" fill="transparent" />
                    <circle cx="96" cy="96" r="80" stroke="#0A2E2A" stroke-width="22" stroke-dasharray="502" stroke-dashoffset="150" fill="transparent" stroke-linecap="round" />
                    <circle cx="96" cy="96" r="80" stroke="#C9A050" stroke-width="22" stroke-dasharray="502" stroke-dashoffset="400" fill="transparent" stroke-linecap="round" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Rs. 8,950</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Net Invoiced</span>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-3 text-[10px] font-bold uppercase tracking-widest pt-2 border-t border-gray-50 w-full">
                <div class="flex items-center"><span class="w-2.5 h-2.5 bg-[#0A2E2A] rounded-full mr-1.5"></span> Dine-in</div>
                <div class="flex items-center"><span class="w-2.5 h-2.5 bg-[#C9A050] rounded-full mr-1.5"></span> Takeaway</div>
                <div class="flex items-center"><span class="w-2.5 h-2.5 bg-rose-500 rounded-full mr-1.5"></span> Delivery</div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="space-y-6 md:col-span-2 lg:col-span-1">
            <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Orders</p>
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">+14.2%</span>
                </div>
                <h3 class="text-3xl sm:text-4xl font-black text-gray-900">278</h3>
                <p class="text-xs text-gray-400 mt-2 font-medium">Orders fulfilled today</p>
            </div>
            <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">New Customers</p>
                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full border border-green-100">+23.65%</span>
                </div>
                <h3 class="text-3xl sm:text-4xl font-black text-gray-900">58</h3>
                <p class="text-xs text-gray-400 mt-2 font-medium">Registered this week</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
        <!-- Best Employees -->
        <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Best Employees</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Top performing service staff today</p>
                </div>
                <button class="text-xs font-bold text-gray-400 hover:text-gray-600 flex items-center transition-colors">
                    Today
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
            <div class="space-y-5">
                @foreach([
                    ['name' => 'Maria Gonzalez', 'role' => 'Floor Supervisor', 'sales' => 3240, 'img' => 'https://ui-avatars.com/api/?name=Maria+Gonzalez&background=FBBC05&color=fff'],
                    ['name' => 'Daniel Okafor', 'role' => 'Waiter', 'sales' => 2980, 'img' => 'https://ui-avatars.com/api/?name=Daniel+Okafor&background=34A853&color=fff'],
                    ['name' => 'Emily Carter', 'role' => 'Waiter', 'sales' => 740, 'img' => 'https://ui-avatars.com/api/?name=Emily+Carter&background=4285F4&color=fff'],
                ] as $emp)
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50/70 transition-all">
                    <div class="flex items-center space-x-4 min-w-0">
                        <img src="{{ $emp['img'] }}" class="w-12 h-12 rounded-2xl shadow-xs shrink-0" alt="{{ $emp['name'] }}">
                        <div class="min-w-0">
                            <h4 class="font-bold text-gray-900 truncate">{{ $emp['name'] }}</h4>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $emp['role'] }}</p>
                        </div>
                    </div>
                    <span class="text-base sm:text-lg font-black text-gray-900 shrink-0 ml-4">Rs. {{ number_format($emp['sales']) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Trending Dishes -->
        <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Trending Dishes</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Most ordered items today</p>
                </div>
                <button class="text-xs font-bold text-gray-400 hover:text-gray-600 flex items-center transition-colors">
                    Today
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
            <div class="space-y-5">
                @php
                    $dishes = [
                        [
                            'name' => 'Grilled Salmon',
                            'cat' => 'Food',
                            'orders' => 64,
                            'theme' => 'text-emerald-700 bg-emerald-50 border-emerald-200/60',
                            'path' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5v-4c0-.28.22-.5.5-.5s.5.22.5.5v4zm-1-6c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z'
                        ],
                        [
                            'name' => 'Iced Latte',
                            'cat' => 'Drinks',
                            'orders' => 74,
                            'theme' => 'text-[#C9A050] bg-amber-50 border-amber-200/60',
                            'path' => 'M20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 5h-2V5h2v3zM4 19h16v2H4z'
                        ],
                        [
                            'name' => 'Margherita Pizza',
                            'cat' => 'Food',
                            'orders' => 48,
                            'theme' => 'text-rose-600 bg-rose-50 border-rose-200/60',
                            'path' => 'M12 2a10 10 0 1010 10A10.011 10.011 0 0012 2zm1 17.93V13h4.93A8.006 8.006 0 0113 19.93zM11 13H6.07A8.006 8.006 0 0111 4.07zm2-2V4.07A8.006 8.006 0 0117.93 11z'
                        ],
                    ];
                @endphp

                @foreach($dishes as $dish)
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50/70 transition-all">
                    <div class="flex items-center space-x-4 min-w-0">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center border shadow-xs shrink-0 {{ $dish['theme'] }}">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path d="{{ $dish['path'] }}" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-gray-900 truncate">{{ $dish['name'] }}</h4>
                            <span class="inline-block mt-0.5 px-2 py-0.5 bg-[#0A2E2A] text-white text-[9px] font-bold rounded-md uppercase tracking-wider">{{ $dish['cat'] }}</span>
                        </div>
                    </div>
                    <div class="text-right shrink-0 ml-4">
                        <span class="text-lg sm:text-xl font-black text-gray-900">{{ $dish['orders'] }}</span>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Orders</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>