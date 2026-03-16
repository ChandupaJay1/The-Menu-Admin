<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <nav class="flex text-sm text-gray-500 mb-2">
                <a href="#" class="hover:text-[#C9A050]">Bills</a>
                <span class="mx-2">></span>
                <span class="text-gray-900 font-semibold">Payment history</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Bills</h1>
        </div>
        
        <div class="flex space-x-2">
            <button class="px-6 py-2 bg-[#C9A050] rounded-xl shadow-md text-sm font-semibold text-white hover:bg-[#B38E46] transition-all">
                Create new bill +
            </button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 h-[calc(100vh-200px)]">
        <!-- Bills List -->
        <div class="lg:w-1/2 bg-white rounded-[2.5rem] p-8 flex flex-col shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between mb-8">
                <div class="flex space-x-4">
                    <button class="px-4 py-2 text-sm font-bold text-gray-900 border-b-2 border-[#C9A050]">All orders</button>
                    <button class="px-4 py-2 text-sm font-semibold text-gray-400 hover:text-gray-600 transition-colors">Paid</button>
                    <button class="px-4 py-2 text-sm font-semibold text-gray-400 hover:text-gray-600 transition-colors">Pending</button>
                </div>
                <div class="text-sm font-semibold text-gray-900 flex items-center cursor-pointer hover:text-[#C9A050]">
                    March 23, 2026
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <div class="flex-grow overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                @php
                    $bills = [
                        ['id' => '#48', 'table' => '12', 'guests' => 2, 'amount' => 28.40, 'time' => '18:22', 'status' => 'Active', 'status_color' => 'bg-green-100 text-green-600'],
                        ['id' => '#38', 'table' => '7', 'guests' => 4, 'amount' => 86.75, 'time' => '18:41', 'status' => 'Cancelled', 'status_color' => 'bg-red-100 text-red-600'],
                        ['id' => '#125', 'table' => '24', 'guests' => 3, 'amount' => 42.30, 'time' => '18:54', 'status' => 'Active', 'status_color' => 'bg-green-100 text-green-600'],
                        ['id' => '#39', 'table' => '3', 'guests' => 2, 'amount' => 31.90, 'time' => '19:12', 'status' => 'Paid', 'status_color' => 'bg-blue-100 text-blue-600'],
                        ['id' => '#123', 'table' => '1', 'guests' => 1, 'amount' => 14.80, 'time' => '19:34', 'status' => 'Active', 'status_color' => 'bg-green-100 text-green-600'],
                    ];
                @endphp

                @foreach($bills as $bill)
                    <div class="p-6 rounded-3xl border border-gray-100 hover:border-[#C9A050]/30 hover:shadow-md transition-all cursor-pointer group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <h3 class="text-lg font-bold text-gray-900">Order {{ $bill['id'] }}</h3>
                                <span class="px-3 py-1 text-[10px] font-bold rounded-full {{ $bill['status_color'] }}">{{ $bill['status'] }}</span>
                            </div>
                            <span class="text-xl font-bold text-gray-900">${{ number_format($bill['amount'], 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-400 font-medium">
                            <span>Table {{ $bill['table'] }} · {{ $bill['guests'] }} guests</span>
                            <span>{{ $bill['time'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 relative">
                <input type="text" placeholder="Search for order..." class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#C9A050] transition-all">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <!-- Order Details -->
        <div class="lg:w-1/2 bg-white rounded-[2.5rem] p-8 flex flex-col shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-2 text-sm font-semibold text-gray-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Payment history</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <div class="flex space-x-2">
                    <div class="w-1.5 h-1.5 bg-gray-200 rounded-full"></div>
                    <div class="w-1.5 h-1.5 bg-gray-200 rounded-full"></div>
                </div>
            </div>

            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Order #48</h2>
                <span class="px-6 py-2 bg-[#C9A050]/10 text-[#C9A050] text-sm font-bold rounded-full">Active</span>
            </div>

            <div class="grid grid-cols-4 gap-4 mb-10 pb-10 border-b border-gray-100">
                <div>
                    <p class="text-xs text-gray-400 font-bold mb-2">Table</p>
                    <p class="text-xl font-bold text-gray-900">12</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold mb-2">Guests</p>
                    <p class="text-xl font-bold text-gray-900">2</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold mb-2">Customers</p>
                    <p class="text-xl font-bold text-gray-900 truncate">Jojo Kate</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold mb-2">Payment</p>
                    <p class="text-xl font-bold text-gray-900">Pending</p>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-900 mb-6">Order info</h3>
            <div class="flex-grow overflow-y-auto space-y-6 mb-10 pr-2 custom-scrollbar">
                @php
                    $items = [
                        ['name' => 'Classic Burger', 'price' => 8.90, 'image' => '🍔'],
                        ['name' => 'BBQ Bacon Burger', 'price' => 10.50, 'image' => '🍔'],
                        ['name' => 'Fries (2x)', 'price' => 7.00, 'image' => '🍟'],
                        ['name' => 'Soda (2x)', 'price' => 5.00, 'image' => '🥤'],
                    ];
                @endphp

                @foreach($items as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 flex items-center justify-center bg-gray-50 rounded-2xl text-2xl shadow-sm border border-gray-100">
                                {{ $item['image'] }}
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $item['name'] }}</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">${{ number_format($item['price'], 2) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="pt-8 border-t border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <span class="text-2xl font-bold text-gray-900">Total</span>
                    <span class="text-3xl font-black text-gray-900">$31.40</span>
                </div>
                <button class="w-full py-5 bg-[#C9A050] text-white rounded-[2rem] font-bold text-xl hover:bg-[#B38E46] transition-all shadow-xl shadow-[#C9A050]/20 flex items-center justify-center">
                    Charge customer $31.40
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