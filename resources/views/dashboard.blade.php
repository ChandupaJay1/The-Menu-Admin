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
            <a href="{{ route('dashboard.salesExport') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow flex items-center gap-2 transition duration-200 shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span class="text-white">Export Report</span>
            </a>
            
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

    <!-- Upcoming Orders Attention Banner -->
    @if (isset($todayUpcomingOrders) && $todayUpcomingOrders->isNotEmpty())
        <div class="mb-8 bg-gradient-to-r from-amber-500/15 via-amber-500/5 to-white rounded-[2rem] p-6 border border-amber-300 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center font-black text-xl shadow-md shadow-amber-500/30 shrink-0">
                    ⚡
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h3 class="text-base font-bold text-gray-900">Attention: Orders Scheduled for Delivery Today</h3>
                        <span class="px-2.5 py-0.5 bg-amber-500 text-white text-[10px] font-black rounded-full uppercase tracking-wider animate-pulse">
                            {{ $todayScheduledCount ?? $todayUpcomingOrders->count() }} Orders
                        </span>
                    </div>
                    <p class="text-xs text-gray-600 mt-0.5">Meal plans and scheduled deliveries for today are awaiting kitchen preparation and dispatch.</p>
                </div>
            </div>
            <a href="{{ route('orders', ['schedule' => 'today']) }}" class="px-5 py-2.5 btn-gold rounded-xl text-xs font-bold uppercase shadow-xs shrink-0 self-start md:self-auto">
                Manage Today's Deliveries →
            </a>
        </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-8">
        <!-- Daily Sales Chart Card (Real 7-day data) -->
        <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-bold text-gray-900">Daily Orders (7 days)</h3>
                <div class="w-2.5 h-2.5 bg-[#C9A050] rounded-full shadow-xs"></div>
            </div>
            @php
                $maxOrders = $last7Days->max('count') ?: 1;
            @endphp
            <div class="h-48 flex items-end justify-between space-x-2 mb-4">
                @foreach($last7Days as $day)
                    @php $pct = max(5, round($day['count'] / $maxOrders * 100)); @endphp
                    <div class="flex-grow bg-gray-50 rounded-t-xl relative group h-full" title="{{ $day['label'] }}: {{ $day['count'] }} orders">
                        <div class="absolute bottom-0 left-0 right-0 bg-[#C9A050]/20 rounded-t-xl transition-all group-hover:bg-[#C9A050]/40" style="height: {{ $pct }}%"></div>
                        <div class="absolute left-1/2 -translate-x-1/2 w-2.5 h-2.5 bg-[#C9A050] rounded-full border-2 border-white shadow-sm" style="bottom: {{ $pct }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest pt-2 border-t border-gray-50">
                @foreach($last7Days as $day)
                    <span>{{ $day['label'] }}</span>
                @endforeach
            </div>
        </div>

        <!-- Total Revenue Donut Card (Real data) -->
        <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all flex flex-col items-center">
            <div class="w-full flex items-center justify-between mb-6">
                <h3 class="font-bold text-gray-900">Total Revenue</h3>
                <span class="text-xs font-bold text-gray-400">All time</span>
            </div>
            @php
                $totalOrders = $pendingCount + $processingCount + $completedCount;
                $completedPct = $totalOrders > 0 ? round($completedCount / $totalOrders * 502) : 0;
                $pendingOffset = 502 - $completedPct;
            @endphp
            <div class="relative w-44 h-44 sm:w-48 sm:h-48 flex items-center justify-center mb-6">
                <svg class="w-full h-full transform -rotate-90">
                    <circle cx="96" cy="96" r="80" stroke="#F3F4F6" stroke-width="22" fill="transparent" />
                    <circle cx="96" cy="96" r="80" stroke="#0A2E2A" stroke-width="22" stroke-dasharray="502" stroke-dashoffset="{{ 502 - $completedPct }}" fill="transparent" stroke-linecap="round" />
                    <circle cx="96" cy="96" r="80" stroke="#C9A050" stroke-width="22" stroke-dasharray="502" stroke-dashoffset="{{ 502 - max(10, round($pendingCount / max(1,$totalOrders) * 502)) }}" fill="transparent" stroke-linecap="round" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Rs. {{ number_format($totalRevenue, 0) }}</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Net Invoiced</span>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-3 text-[10px] font-bold uppercase tracking-widest pt-2 border-t border-gray-50 w-full">
                <div class="flex items-center"><span class="w-2.5 h-2.5 bg-[#0A2E2A] rounded-full mr-1.5"></span> Completed ({{ $completedCount }})</div>
                <div class="flex items-center"><span class="w-2.5 h-2.5 bg-[#C9A050] rounded-full mr-1.5"></span> Pending ({{ $pendingCount }})</div>
                <div class="flex items-center"><span class="w-2.5 h-2.5 bg-blue-500 rounded-full mr-1.5"></span> Processing ({{ $processingCount }})</div>
            </div>
        </div>

        <!-- Summary Cards (Real data) -->
        <div class="space-y-6 md:col-span-2 lg:col-span-1">
            <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Today's Orders</p>
                    <a href="{{ route('orders') }}" class="text-[10px] font-bold text-[#C9A050] hover:underline">View all →</a>
                </div>
                <h3 class="text-3xl sm:text-4xl font-black text-gray-900">{{ $totalOrdersToday }}</h3>
                <p class="text-xs text-gray-400 mt-2 font-medium">Orders placed today</p>
            </div>
            <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">New Customers</p>
                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full border border-green-100">This week</span>
                </div>
                <h3 class="text-3xl sm:text-4xl font-black text-gray-900">{{ $newCustomersThisWeek }}</h3>
                <p class="text-xs text-gray-400 mt-2 font-medium">Registered this week</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 mb-8">
        <!-- Upcoming Delivery Summary (Real data) -->
        <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Delivery Forecast</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Top delivery days in next 20 days</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-[#C9A050]/10 flex items-center justify-center text-[#C9A050] shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div class="space-y-4">
                @forelse($topUpcomingDays as $day)
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50/70 transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col items-center justify-center shadow-xs shrink-0">
                            <span class="text-[10px] font-bold text-gray-400 uppercase leading-none">{{ \Carbon\Carbon::parse($day->date)->format('M') }}</span>
                            <span class="text-base font-black text-[#C9A050] leading-tight">{{ \Carbon\Carbon::parse($day->date)->format('d') }}</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">{{ \Carbon\Carbon::parse($day->date)->format('l') }}</h4>
                            <p class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($day->date)->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0 ml-4">
                        <span class="text-lg sm:text-xl font-black text-gray-900">{{ $day->total }}</span>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Scheduled</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">No scheduled deliveries in the next 20 days.</p>
                </div>
                @endforelse
            </div>
        </div>
        <!-- Recent Orders (Real data) -->
        <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Recent Orders</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Latest 5 orders placed</p>
                </div>
                <a href="{{ route('orders') }}" class="text-xs font-bold text-[#C9A050] hover:underline transition-colors">See all →</a>
            </div>
            <div class="space-y-4">
                @forelse($recentOrders as $order)
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50/70 transition-all">
                    <div class="flex items-center space-x-4 min-w-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'Guest') }}&background=0A2E2A&color=C9A050" class="w-10 h-10 rounded-2xl shadow-xs shrink-0" alt="">
                        <div class="min-w-0">
                            <h4 class="font-bold text-gray-900 truncate text-sm">{{ $order->user->name ?? 'Guest' }}</h4>
                            <p class="text-[10px] text-gray-400">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} · {{ $order->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0 ml-4">
                        <span class="font-black text-gray-900 text-sm">Rs. {{ number_format($order->total_price, 0) }}</span>
                        <p class="text-[10px] font-bold uppercase tracking-wider
                            @if($order->status === 'pending') text-amber-500
                            @elseif($order->status === 'processing') text-blue-500
                            @elseif(in_array($order->status, ['completed','delivered'])) text-emerald-600
                            @else text-rose-500 @endif">{{ ucfirst($order->status) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-8">No orders yet today.</p>
                @endforelse
            </div>
        </div>

        <!-- Trending Dishes (Real data from DB) -->
        <div class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-[#C9A050]/10 hover:border-[#C9A050]/20 transition-all">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Top Ordered Dishes</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Most ordered food items all-time</p>
                </div>
            </div>
            <div class="space-y-5">
                @php
                    $topDishes = \App\Models\OrderItem::with('food')
                        ->select('food_id', \Illuminate\Support\Facades\DB::raw('sum(quantity) as total_qty'))
                        ->groupBy('food_id')
                        ->orderByDesc('total_qty')
                        ->take(5)
                        ->get();
                    $colors = ['text-emerald-700 bg-emerald-50 border-emerald-200/60', 'text-[#C9A050] bg-amber-50 border-amber-200/60', 'text-rose-600 bg-rose-50 border-rose-200/60', 'text-blue-600 bg-blue-50 border-blue-200/60', 'text-violet-600 bg-violet-50 border-violet-200/60'];
                @endphp
                @forelse($topDishes as $i => $item)
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50/70 transition-all">
                    <div class="flex items-center space-x-4 min-w-0">
                        @if($item->food && $item->food->image_url)
                            <img src="{{ $item->food->image_url }}" class="w-12 h-12 rounded-2xl shadow-xs shrink-0 object-cover" alt="" onerror="this.style.display='none'">
                        @else
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center border shadow-xs shrink-0 {{ $colors[$i % 5] }}">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V1h-1.97v4.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v8.05zM1 21.99V21h15.03v.99c0 .55-.45 1-1.01 1H2.01c-.56 0-1.01-.45-1.01-1zm15.03-7c0-8-15.03-8-15.03 0h15.03zM1.02 17h15v2h-15z"/></svg>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <h4 class="font-bold text-gray-900 truncate text-sm">{{ $item->food->name ?? 'Unknown Dish' }}</h4>
                            <span class="inline-block mt-0.5 px-2 py-0.5 bg-[#0A2E2A] text-white text-[9px] font-bold rounded-md uppercase tracking-wider">{{ ucfirst($item->food->type ?? 'food') }}</span>
                        </div>
                    </div>
                    <div class="text-right shrink-0 ml-4">
                        <span class="text-lg sm:text-xl font-black text-gray-900">{{ $item->total_qty }}</span>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Ordered</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-8">No orders recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>