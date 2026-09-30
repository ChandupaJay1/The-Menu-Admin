<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center text-sm text-gray-500 mb-2">
                <a href="#" class="hover:text-[#C9A050] transition-colors">Bills</a>
                <svg class="w-3.5 h-3.5 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-900 font-semibold">Payment history</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Bills & Billing</h1>
        </div>
        
        <div class="flex items-center space-x-2 shrink-0">
            <button class="px-5 py-2.5 btn-gold rounded-xl text-sm font-semibold shadow-xs flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Create new bill</span>
            </button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 min-h-[600px] lg:h-[calc(100vh-190px)]">
        <!-- Bills List -->
        <div class="w-full lg:w-1/2 bg-white rounded-[2rem] sm:rounded-[2.5rem] p-6 sm:p-8 flex flex-col shadow-sm border border-[#C9A050]/10 overflow-hidden min-h-[460px] lg:min-h-0">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-2 border-b border-gray-100">
                <div class="flex space-x-2 overflow-x-auto no-scrollbar pb-1">
                    <button class="px-4 py-2 text-xs sm:text-sm font-bold text-gray-900 border-b-2 border-[#C9A050] whitespace-nowrap">All orders</button>
                    <button class="px-4 py-2 text-xs sm:text-sm font-semibold text-gray-400 hover:text-gray-600 transition-colors whitespace-nowrap">Paid</button>
                    <button class="px-4 py-2 text-xs sm:text-sm font-semibold text-gray-400 hover:text-gray-600 transition-colors whitespace-nowrap">Pending</button>
                </div>
                <div class="text-xs sm:text-sm font-semibold text-gray-700 flex items-center cursor-pointer hover:text-[#C9A050] transition-colors shrink-0">
                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>March 23, 2026</span>
                </div>
            </div>

            <div class="flex-grow overflow-y-auto space-y-3.5 pr-1 custom-scrollbar">
                @php
                    $bills = [
                        ['id' => '#48', 'table' => '12', 'guests' => 2, 'amount' => 28.40, 'time' => '18:22', 'status' => 'Active', 'status_color' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60'],
                        ['id' => '#38', 'table' => '7', 'guests' => 4, 'amount' => 86.75, 'time' => '18:41', 'status' => 'Cancelled', 'status_color' => 'bg-rose-50 text-rose-700 border border-rose-200/60'],
                        ['id' => '#125', 'table' => '24', 'guests' => 3, 'amount' => 42.30, 'time' => '18:54', 'status' => 'Active', 'status_color' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60'],
                        ['id' => '#39', 'table' => '3', 'guests' => 2, 'amount' => 31.90, 'time' => '19:12', 'status' => 'Paid', 'status_color' => 'bg-blue-50 text-blue-700 border border-blue-200/60'],
                        ['id' => '#123', 'table' => '1', 'guests' => 1, 'amount' => 14.80, 'time' => '19:34', 'status' => 'Active', 'status_color' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60'],
                    ];
                @endphp

                @foreach($bills as $bill)
                    <div class="p-5 rounded-2xl border border-gray-100 hover:border-[#C9A050]/40 hover:bg-[#C9A050]/[0.02] transition-all cursor-pointer group">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-[#C9A050] transition-colors">Order {{ $bill['id'] }}</h3>
                                <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full {{ $bill['status_color'] }}">{{ $bill['status'] }}</span>
                            </div>
                            <span class="text-base sm:text-lg font-black text-gray-900">Rs. {{ number_format($bill['amount'], 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-400 font-medium">
                            <span>Table {{ $bill['table'] }} · {{ $bill['guests'] }} guests</span>
                            <span>{{ $bill['time'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 relative">
                <input type="text" placeholder="Search for order..." class="w-full pl-11 pr-4 py-3.5 input bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#C9A050] transition-all">
                <svg class="w-4 h-4 text-gray-400 absolute left-4 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <!-- Order Details -->
        <div class="w-full lg:w-1/2 bg-white rounded-[2rem] sm:rounded-[2.5rem] p-6 sm:p-8 flex flex-col shadow-sm border border-[#C9A050]/10 overflow-hidden min-h-[460px] lg:min-h-0">
            <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-100">
                <div class="flex items-center space-x-2 text-xs sm:text-sm font-semibold text-gray-900">
                    <svg class="w-4 h-4 text-[#C9A050]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Payment history</span>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
                <div class="flex space-x-1.5">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-200 rounded-full"></div>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Order #48</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Dine-in receipt</p>
                </div>
                <span class="px-4 py-1.5 bg-[#C9A050]/10 text-[#C9A050] text-xs font-bold rounded-full border border-[#C9A050]/20">Active</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6 pb-6 border-b border-gray-100">
                <div class="bg-gray-50/60 p-3 rounded-xl border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Table</p>
                    <p class="text-lg font-bold text-gray-900">12</p>
                </div>
                <div class="bg-gray-50/60 p-3 rounded-xl border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Guests</p>
                    <p class="text-lg font-bold text-gray-900">2</p>
                </div>
                <div class="bg-gray-50/60 p-3 rounded-xl border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Customer</p>
                    <p class="text-lg font-bold text-gray-900 truncate">Jojo Kate</p>
                </div>
                <div class="bg-gray-50/60 p-3 rounded-xl border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Payment</p>
                    <p class="text-lg font-bold text-amber-600">Pending</p>
                </div>
            </div>

            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Order Items</h3>
            <div class="flex-grow overflow-y-auto space-y-3.5 mb-6 pr-1 custom-scrollbar">
                @php
                    $items = [
                        [
                            'name' => 'Classic Burger',
                            'price' => 8.90,
                            'theme' => 'text-amber-700 bg-amber-50 border-amber-200/60',
                            'icon' => 'M12 2a9 9 0 00-9 9c0 1.1.9 2 2 2h14a2 2 0 002-2 9 9 0 00-9-9zm-7 13h14v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2z'
                        ],
                        [
                            'name' => 'BBQ Bacon Burger',
                            'price' => 10.50,
                            'theme' => 'text-amber-700 bg-amber-50 border-amber-200/60',
                            'icon' => 'M12 2a9 9 0 00-9 9c0 1.1.9 2 2 2h14a2 2 0 002-2 9 9 0 00-9-9zm-7 13h14v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2z'
                        ],
                        [
                            'name' => 'Fries (2x)',
                            'price' => 7.00,
                            'theme' => 'text-orange-700 bg-orange-50 border-orange-200/60',
                            'icon' => 'M6 3h2v12H6zm5-2h2v14h-2zm5 4h2v10h-2zM4 17h16l-2 5H6z'
                        ],
                        [
                            'name' => 'Soda (2x)',
                            'price' => 5.00,
                            'theme' => 'text-[#C9A050] bg-amber-50 border-amber-200/60',
                            'icon' => 'M20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 5h-2V5h2v3zM4 19h16v2H4z'
                        ],
                    ];
                @endphp

                @foreach($items as $item)
                    <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-gray-50/80 transition-all">
                        <div class="flex items-center space-x-3 min-w-0">
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl border shadow-xs shrink-0 {{ $item['theme'] }}">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="{{ $item['icon'] }}" /></svg>
                            </div>
                            <span class="text-sm sm:text-base font-bold text-gray-900 truncate">{{ $item['name'] }}</span>
                        </div>
                        <span class="text-sm sm:text-base font-bold text-gray-900 shrink-0 ml-4">Rs. {{ number_format($item['price'], 2) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="pt-5 border-t border-gray-100 mt-auto">
                <div class="flex items-center justify-between mb-5">
                    <span class="text-lg sm:text-xl font-bold text-gray-900">Total</span>
                    <span class="text-2xl sm:text-3xl font-black text-gray-900">Rs. 31.40</span>
                </div>
                <button class="w-full py-4 btn-gold rounded-2xl font-bold text-base sm:text-lg flex items-center justify-center shadow-lg shadow-[#C9A050]/20 min-h-[48px]">
                    Charge customer Rs. 31.40
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