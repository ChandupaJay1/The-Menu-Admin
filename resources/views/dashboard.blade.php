<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <nav class="flex text-sm text-gray-500 mb-2">
                <a href="#" class="hover:text-[#C9A050]">Dashboard</a>
                <span class="mx-2">></span>
                <span class="text-gray-900 font-semibold">Sales statistics</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="flex bg-white p-1 rounded-xl shadow-sm">
                <button class="px-4 py-1.5 text-xs font-bold text-gray-400">Yesterday</button>
                <button class="px-4 py-1.5 text-xs font-bold bg-[#C9A050] text-white rounded-lg shadow-md">Today</button>
                <button class="px-4 py-1.5 text-xs font-bold text-gray-400">Week</button>
                <button class="px-4 py-1.5 text-xs font-bold text-gray-400">Month</button>
                <button class="px-4 py-1.5 text-xs font-bold text-gray-400">Year</button>
            </div>
            <button class="p-2 bg-white rounded-xl shadow-sm text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Daily Sales Chart Card -->
        <div class="lg:col-span-1 bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-bold text-gray-900">Daily sales</h3>
                <div class="w-2 h-2 bg-gray-200 rounded-full"></div>
            </div>
            <div class="h-48 flex items-end justify-between space-x-2 mb-4">
                <!-- Simple representation of a line chart -->
                @foreach([30, 45, 35, 60, 40, 55, 45] as $height)
                    <div class="flex-grow bg-gray-50 rounded-t-lg relative group h-full">
                        <div class="absolute bottom-0 left-0 right-0 bg-[#C9A050]/20 rounded-t-lg transition-all group-hover:bg-[#C9A050]/40" style="height: {{ $height }}%"></div>
                        <div class="absolute bottom-[{{ $height }}%] left-1/2 -translate-x-1/2 w-2 h-2 bg-[#C9A050] rounded-full border-2 border-white shadow-sm"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                <span>9 AM</span>
                <span>12 PM</span>
                <span>3 PM</span>
                <span>6 PM</span>
                <span>9 PM</span>
            </div>
        </div>

        <!-- Total Revenue Donut Card -->
        <div class="lg:col-span-1 bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col items-center">
            <div class="w-full flex items-center justify-between mb-8">
                <h3 class="font-bold text-gray-900">Total Revenue</h3>
                <button class="text-xs font-bold text-gray-400 flex items-center">
                    Today
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
            <div class="relative w-48 h-48 flex items-center justify-center mb-8">
                <svg class="w-full h-full transform -rotate-90">
                    <circle cx="96" cy="96" r="80" stroke="#F3F4F6" stroke-width="24" fill="transparent" />
                    <circle cx="96" cy="96" r="80" stroke="#0A2E2A" stroke-width="24" stroke-dasharray="502" stroke-dashoffset="150" fill="transparent" stroke-linecap="round" />
                    <circle cx="96" cy="96" r="80" stroke="#C9A050" stroke-width="24" stroke-dasharray="502" stroke-dashoffset="400" fill="transparent" stroke-linecap="round" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-3xl font-black text-gray-900">$8,950</span>
                </div>
            </div>
            <div class="flex space-x-4 text-[10px] font-bold uppercase tracking-widest">
                <div class="flex items-center"><span class="w-2 h-2 bg-[#0A2E2A] rounded-full mr-2"></span> Dine-in</div>
                <div class="flex items-center"><span class="w-2 h-2 bg-[#C9A050] rounded-full mr-2"></span> Takeaway</div>
                <div class="flex items-center"><span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span> Delivery</div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Order</p>
                    <span class="text-[10px] font-bold text-red-500">-2.34%</span>
                </div>
                <h3 class="text-4xl font-black text-gray-900">278</h3>
            </div>
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">New Customers</p>
                    <span class="text-[10px] font-bold text-green-500">+23.65%</span>
                </div>
                <h3 class="text-4xl font-black text-gray-900">58</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Best Employees -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-bold text-gray-900">Best Employees</h3>
                <button class="text-xs font-bold text-gray-400 flex items-center">
                    Today
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
            <div class="space-y-6">
                @foreach([
                    ['name' => 'Maria Gonzalez', 'role' => 'Floor Supervisor', 'sales' => 3240, 'img' => 'https://ui-avatars.com/api/?name=Maria+Gonzalez&background=FBBC05&color=fff'],
                    ['name' => 'Daniel Okafor', 'role' => 'Waiter', 'sales' => 2980, 'img' => 'https://ui-avatars.com/api/?name=Daniel+Okafor&background=34A853&color=fff'],
                    ['name' => 'Emily Carter', 'role' => 'Waiter', 'sales' => 740, 'img' => 'https://ui-avatars.com/api/?name=Emily+Carter&background=4285F4&color=fff'],
                ] as $emp)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $emp['img'] }}" class="w-12 h-12 rounded-2xl shadow-sm" alt="">
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $emp['name'] }}</h4>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $emp['role'] }}</p>
                        </div>
                    </div>
                    <span class="text-lg font-black text-gray-900">${{ number_format($emp['sales']) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Trending Dishes -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-bold text-gray-900">Trending Dishes</h3>
                <button class="text-xs font-bold text-gray-400 flex items-center">
                    Today
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
            <div class="space-y-6">
                @foreach([
                    ['name' => 'Grilled Salmon', 'cat' => 'Food', 'orders' => 64, 'icon' => '🐟'],
                    ['name' => 'Iced Latte', 'cat' => 'Drinks', 'orders' => 74, 'icon' => '🥤'],
                    ['name' => 'Margherita Pizza', 'cat' => 'Food', 'orders' => 48, 'icon' => '🍕'],
                ] as $dish)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-gray-100">
                            {{ $dish['icon'] }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $dish['name'] }}</h4>
                            <span class="px-2 py-0.5 bg-[#0A2E2A] text-white text-[8px] font-bold rounded-md uppercase">{{ $dish['cat'] }}</span>
                        </div>
                    </div>
                    <span class="text-xl font-black text-gray-900">{{ $dish['orders'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>