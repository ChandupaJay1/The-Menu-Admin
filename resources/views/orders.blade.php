<x-app-layout>
    @php
        $statusStyles = [
            'pending'    => ['label' => 'Pending',    'class' => 'bg-amber-50 text-amber-600 border-amber-200'],
            'processing' => ['label' => 'Processing', 'class' => 'bg-blue-50 text-blue-600 border-blue-200'],
            'completed'  => ['label' => 'Completed',  'class' => 'bg-emerald-50 text-emerald-600 border-emerald-200'],
            'delivered'  => ['label' => 'Delivered',  'class' => 'bg-emerald-50 text-emerald-600 border-emerald-200'],
            'cancelled'  => ['label' => 'Cancelled',  'class' => 'bg-rose-50 text-rose-600 border-rose-200'],
        ];
        $totalAll = $counts->sum();
        $pendingCount = $counts->get('pending', 0);
        $processingCount = $counts->get('processing', 0);
        $completedCount = $counts->get('completed', 0) + $counts->get('delivered', 0);
        $todayStr = now()->toDateString();
        $tomorrowStr = now()->addDay()->toDateString();
    @endphp

    <div class="space-y-6" x-data="{ 
        showAssignModal: false, 
        selected: null, 
        assignOrderId: null, 
        assignDriverId: null,
        showAlertDetails: true
    }">
        <!-- Assign Driver Modal -->
        <template x-teleport="body">
            <div x-show="showAssignModal"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">

                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAssignModal = false"></div>

                <!-- Modal Content -->
                <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden transform transition-all"
                     x-show="showAssignModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                    <form method="POST" :action="'/orders/' + assignOrderId + '/assign-driver'">
                        @csrf
                        <div class="bg-[#0A2E2A] px-8 py-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-white">Assign Delivery Driver</h3>
                                <p class="text-xs text-white/60 mt-0.5">Order <span class="font-bold text-[#C9A050]" x-text="'#ORD-' + String(assignOrderId).padStart(4, '0')"></span></p>
                            </div>
                            <button type="button" @click="showAssignModal = false" class="p-2 bg-white/10 text-white/70 hover:text-white rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-6 sm:p-8">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Select available driver</p>
                            <div class="space-y-3 max-h-[340px] overflow-y-auto pr-2 custom-scrollbar">
                                <!-- Unassign option -->
                                <label class="group relative flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all"
                                       :class="assignDriverId == null ? 'border-[#C9A050] bg-[#C9A050]/5' : 'border-gray-100 hover:border-[#C9A050]/30 hover:bg-[#C9A050]/5'">
                                    <input type="radio" name="driver_id" value="" :checked="assignDriverId == null" class="w-5 h-5 text-[#C9A050] border-gray-300 focus:ring-[#C9A050]">
                                    <div class="ml-4 flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">Unassigned</p>
                                            <p class="text-xs text-gray-400">Order will have no assigned driver</p>
                                        </div>
                                    </div>
                                </label>

                                @foreach ($drivers as $driver)
                                    @php
                                        $driverColor = [
                                            'available'   => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                                            'on_delivery' => 'text-blue-700 bg-blue-50 border-blue-200',
                                            'offline'     => 'text-gray-500 bg-gray-100 border-gray-200',
                                        ][$driver->status] ?? 'text-gray-500 bg-gray-100 border-gray-200';
                                    @endphp
                                    <label class="group relative flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all"
                                           :class="assignDriverId == {{ $driver->id }} ? 'border-[#C9A050] bg-[#C9A050]/5' : 'border-gray-100 hover:border-[#C9A050]/30 hover:bg-[#C9A050]/5'">
                                        <input type="radio" name="driver_id" value="{{ $driver->id }}" :checked="assignDriverId == {{ $driver->id }}" class="w-5 h-5 text-[#C9A050] border-gray-300 focus:ring-[#C9A050]">
                                        <div class="ml-4 flex items-center justify-between flex-1">
                                            <div class="flex items-center space-x-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=0A2E2A&color=C9A050" class="w-10 h-10 rounded-xl" alt="">
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">{{ $driver->name }}</p>
                                                    <p class="text-xs text-gray-400">{{ $driver->phone ?? 'No phone' }} · {{ $driver->vehicle_type ?? 'Motorbike' }}</p>
                                                </div>
                                            </div>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $driverColor }} uppercase">
                                                {{ str_replace('_', ' ', $driver->status) }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="flex items-center space-x-3 mt-6">
                                <button type="button" @click="showAssignModal = false" class="flex-1 px-5 py-3 btn-ghost rounded-2xl font-bold text-sm">Cancel</button>
                                <button type="submit" class="flex-1 px-5 py-3 btn-gold rounded-2xl font-bold text-sm">Confirm Driver</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Orders Management</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Real-time order tracking, kitchen prep schedules, and driver dispatch</p>
            </div>
            <div class="flex items-center space-x-2.5 shrink-0">
                <a href="{{ route('orders', ['schedule' => 'today']) }}" 
                   class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center space-x-2 border {{ $scheduleFilter === 'today' ? 'bg-[#0A2E2A] text-[#C9A050] border-[#0A2E2A] shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                    <span>Today's Orders ({{ $todayScheduledCount }})</span>
                </a>
            </div>
        </div>

        <!-- UPCOMING ORDERS NOTIFICATION & ALERT BANNERS (Proactive Kitchen & Delivery Alert) -->
        @if ($todayUpcomingOrders->isNotEmpty() || $tomorrowUpcomingOrders->isNotEmpty())
            <div class="space-y-4">
                @if ($todayUpcomingOrders->isNotEmpty())
                    <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-white rounded-3xl p-5 sm:p-6 border border-amber-300 shadow-sm relative overflow-hidden">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center font-black text-lg shadow-md shadow-amber-500/30 shrink-0">
                                    ⚡
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-base font-bold text-gray-900">Orders Scheduled for Delivery Today!</h3>
                                        <span class="px-2 py-0.5 bg-amber-500 text-white text-[10px] font-black rounded-full uppercase tracking-wider animate-pulse">
                                            {{ $todayUpcomingOrders->count() }} Orders Today
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-0.5">Kitchen staff should prioritize preparing these scheduled meals immediately.</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <a href="{{ route('orders', ['schedule' => 'today']) }}" class="px-4 py-2 text-xs font-bold text-amber-800 bg-amber-100 hover:bg-amber-200 rounded-xl transition-colors">
                                    View All Today →
                                </a>
                            </div>
                        </div>

                        <!-- Mini cards for today's orders -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach ($todayUpcomingOrders->take(3) as $tOrder)
                                @php
                                    $todayMeals = $tOrder->items->filter(fn($i) => $i->scheduled_date && $i->scheduled_date->format('Y-m-d') === $todayStr);
                                    $mealsList = $todayMeals->map(fn($i) => ucfirst($i->meal_type ?? 'meal') . ': ' . ($i->food->name ?? 'Dish') . ' ×' . $i->quantity)->join(' · ');
                                @endphp
                                <div class="bg-white/90 backdrop-blur-xs rounded-2xl p-4 border border-amber-200 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <span class="text-xs font-black text-gray-900">#ORD-{{ str_pad($tOrder->id, 4, '0', STR_PAD_LEFT) }}</span>
                                            <p class="text-xs font-bold text-gray-800 mt-0.5">{{ $tOrder->customer_name ?: ($tOrder->user->name ?? 'Customer') }}</p>
                                            <a href="tel:{{ $tOrder->customer_phone ?: ($tOrder->user->phone ?? '') }}" class="text-[11px] text-amber-700 hover:underline font-medium flex items-center mt-0.5">
                                                📞 {{ $tOrder->customer_phone ?: ($tOrder->user->phone ?? 'No phone') }}
                                            </a>
                                        </div>
                                        <x-status-badge :status="$tOrder->status" />
                                    </div>
                                    <div class="bg-amber-50/70 rounded-xl p-2.5 border border-amber-100/80 mb-3 text-[11px] text-gray-700">
                                        <span class="font-bold text-amber-900 block mb-0.5">Scheduled for Today:</span>
                                        <span class="line-clamp-2">{{ $mealsList ?: 'All ordered items' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between pt-1 border-t border-gray-100">
                                        <span class="text-xs font-black text-gray-900">Rs. {{ number_format($tOrder->total_price, 2) }}</span>
                                        <button @click="selected = {{ $tOrder->id }}" class="text-xs font-bold text-[#C9A050] hover:text-[#B38E46]">
                                            Open Details →
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($tomorrowUpcomingOrders->isNotEmpty())
                    <div class="bg-gradient-to-r from-blue-500/10 via-blue-500/5 to-white rounded-3xl p-5 sm:p-6 border border-blue-200 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-lg shadow-md shadow-blue-500/30 shrink-0">
                                    📅
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-base font-bold text-gray-900">Upcoming Orders for Tomorrow ({{ now()->addDay()->format('M d, D') }})</h3>
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-full uppercase">
                                            {{ $tomorrowUpcomingOrders->count() }} Orders Tomorrow
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5">Prepare ingredients and allocate drivers in advance for seamless tomorrow dispatch.</p>
                                </div>
                            </div>
                            <a href="{{ route('orders', ['schedule' => 'tomorrow']) }}" class="px-4 py-2 text-xs font-bold text-blue-800 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors shrink-0">
                                View Tomorrow's Schedule →
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Order Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card-sm p-5 sm:p-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-[#0A2E2A]/5 rounded-xl">
                        <svg class="w-5 h-5 text-[#0A2E2A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <span class="text-[10px] font-bold text-[#0A2E2A] bg-[#0A2E2A]/5 px-2 py-0.5 rounded-full">All</span>
                </div>
                <h3 class="text-2xl sm:text-3xl font-black text-gray-900" id="total-orders-count">{{ $totalAll }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">Total Orders</p>
            </div>

            <div class="card-sm p-5 sm:p-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-amber-50 rounded-xl">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Awaiting</span>
                </div>
                <h3 class="text-2xl sm:text-3xl font-black text-gray-900" id="pending-orders-count">{{ $pendingCount }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">Pending Action</p>
            </div>

            <div class="card-sm p-5 sm:p-6 border-l-4 border-l-rose-500">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-rose-50 rounded-xl">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">Today</span>
                </div>
                <h3 class="text-2xl sm:text-3xl font-black text-gray-900">{{ $todayScheduledCount }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">Scheduled for Today</p>
            </div>

            <div class="card-sm p-5 sm:p-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-blue-50 rounded-xl">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Future</span>
                </div>
                <h3 class="text-2xl sm:text-3xl font-black text-gray-900">{{ $upcomingScheduledCount }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">Upcoming (Next 7d)</p>
            </div>
        </div>

        <!-- Orders Table & Filter Card -->
        <div class="card overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Orders List</h2>
                        <p class="text-xs text-gray-400">View customer identities, delivery schedules, and update statuses</p>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('orders') }}" class="relative w-full sm:w-72">
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                        <input type="hidden" name="schedule" value="{{ $scheduleFilter }}">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search customer, ID, phone, dish..."
                            class="input w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm"
                        >
                        <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </form>
                </div>

                <!-- Dual Filter Bar: Status & Schedule -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pt-2">
                    <!-- Schedule Filter Pills -->
                    <div class="flex items-center space-x-1 overflow-x-auto no-scrollbar pb-1">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mr-2 shrink-0">Schedule:</span>
                        @foreach ([
                            'all'       => 'All Dates',
                            'today'     => '🔴 Today (' . $todayScheduledCount . ')',
                            'tomorrow'  => '🟠 Tomorrow',
                            'upcoming'  => '📅 Future Days',
                            'immediate' => '⚡ Immediate Orders'
                        ] as $sKey => $sLabel)
                            <a href="{{ request()->fullUrlWithQuery(['schedule' => $sKey, 'page' => null]) }}"
                               class="px-3 py-1.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap {{ $scheduleFilter === $sKey ? 'bg-[#0A2E2A] text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                {{ $sLabel }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Status Filter Pills -->
                    <div class="flex items-center space-x-1 overflow-x-auto no-scrollbar pb-1">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mr-2 shrink-0">Status:</span>
                        @foreach (['all' => 'All', 'pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label)
                            <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => null]) }}"
                               class="px-3 py-1.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap {{ $statusFilter === $key ? 'bg-[#C9A050] text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Orders Table (Highly Informative) -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100">
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Customer & Contact</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Target Schedule & Meals</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Driver</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            @php
                                $code = '#ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
                                $scheduledDates = $order->items->pluck('scheduled_date')->filter()->unique();
                                $mealTypes = $order->items->pluck('meal_type')->filter()->unique();
                                
                                $isScheduledToday = $scheduledDates->contains(function($d) use ($todayStr) {
                                    return \Carbon\Carbon::parse($d)->toDateString() === $todayStr;
                                });
                                $isScheduledTomorrow = $scheduledDates->contains(function($d) use ($tomorrowStr) {
                                    return \Carbon\Carbon::parse($d)->toDateString() === $tomorrowStr;
                                });

                                $customerName = $order->customer_name ?: ($order->user->name ?? 'Guest User');
                                $customerPhone = $order->customer_phone ?: ($order->user->phone ?? '');
                                $deliveryAddress = $order->delivery_address ?: ($order->address ?: 'Standard Delivery');
                            @endphp
                            <tr id="order-row-{{ $order->id }}" class="hover:bg-[#0A2E2A]/[0.02] transition-colors {{ $isScheduledToday ? 'bg-amber-50/20' : '' }}">
                                <!-- Order Code & Timing -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-black text-gray-900">{{ $code }}</span>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $order->created_at->format('M d, Y · h:i A') }}</p>
                                    @if ($scheduledDates->isNotEmpty())
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-purple-50 text-purple-700 text-[9px] font-bold rounded-md border border-purple-200">
                                            📅 Meal Plan ({{ $scheduledDates->count() }} {{ \Illuminate\Support\Str::plural('day', $scheduledDates->count()) }})
                                        </span>
                                    @else
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-[9px] font-bold rounded-md">
                                            ⚡ Immediate
                                        </span>
                                    @endif
                                </td>

                                <!-- Customer Details -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($customerName) }}&background=0A2E2A&color=C9A050&bold=true" class="w-9 h-9 rounded-xl shadow-xs" alt="">
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ $customerName }}</p>
                                            @if ($customerPhone)
                                                <a href="tel:{{ $customerPhone }}" class="text-xs text-[#C9A050] hover:underline font-semibold flex items-center mt-0.5">
                                                    📞 {{ $customerPhone }}
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400 italic">No phone</span>
                                            @endif
                                            <p class="text-[11px] text-gray-400 truncate max-w-[200px]" title="{{ $deliveryAddress }}">
                                                📍 {{ $deliveryAddress }}
                                            </p>
                                            @if ($order->dinner_address)
                                                <p class="text-[10px] text-purple-600 truncate max-w-[200px] mt-0.5" title="Dinner: {{ $order->dinner_address }}">
                                                    🌙 Dinner: {{ $order->dinner_address }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Target Schedule & Meals Breakdown -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1.5">
                                        <!-- Schedule Tag -->
                                        @if ($isScheduledToday)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300 animate-pulse">
                                                🔴 Today ({{ now()->format('M d') }})
                                            </span>
                                        @elseif ($isScheduledTomorrow)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                                🟠 Tomorrow ({{ now()->addDay()->format('M d') }})
                                            </span>
                                        @elseif ($scheduledDates->isNotEmpty())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                📅 {{ \Carbon\Carbon::parse($scheduledDates->first())->format('M d, Y') }}
                                            </span>
                                        @endif

                                        <!-- Meals List -->
                                        <div class="space-y-0.5">
                                            @forelse ($order->items->take(3) as $item)
                                                <p class="text-xs text-gray-700 flex items-center space-x-1.5">
                                                    @if ($item->meal_type)
                                                        <span class="px-1.5 py-0.2 bg-gray-100 text-gray-600 text-[9px] font-bold rounded uppercase">
                                                            {{ substr($item->meal_type, 0, 1) }}
                                                        </span>
                                                    @endif
                                                    <span class="font-medium text-gray-900">{{ $item->food->name ?? 'Dish' }}</span>
                                                    <span class="text-gray-400 font-bold">×{{ $item->quantity }}</span>
                                                    @if ($item->scheduled_date)
                                                        <span class="text-[10px] text-gray-400">({{ \Carbon\Carbon::parse($item->scheduled_date)->format('d M') }})</span>
                                                    @endif
                                                </p>
                                            @empty
                                                <p class="text-xs text-gray-400">—</p>
                                            @endforelse
                                            @if ($order->items->count() > 3)
                                                <p class="text-[10px] text-[#C9A050] font-bold">+{{ $order->items->count() - 3 }} more dishes</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Total & Payment -->
                                <td class="px-6 py-4">
                                    <span class="text-sm font-black text-gray-900">Rs. {{ number_format($order->total_price, 2) }}</span>
                                    <p class="text-[10px] text-gray-500 mt-0.5 font-medium">{{ ucfirst($order->payment_method ?? 'Bank Transfer') }}</p>
                                    @if ($order->payment_status === 'verified' || $order->payment_status === 'paid')
                                        <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-emerald-50 text-emerald-700 text-[9px] font-bold rounded border border-emerald-200">
                                            ✓ Verified
                                        </span>
                                    @elseif ($order->payment_status === 'rejected')
                                        <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-rose-50 text-rose-700 text-[9px] font-bold rounded border border-rose-200">
                                            ✕ Rejected
                                        </span>
                                    @else
                                        <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-amber-50 text-amber-700 text-[9px] font-bold rounded border border-amber-200 animate-pulse">
                                            ⏳ Slip Pending
                                        </span>
                                    @endif
                                    @if ($order->is_subscription)
                                        <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-purple-50 text-purple-700 text-[9px] font-bold rounded border border-purple-200">
                                            🔄 Subscription
                                        </span>
                                    @endif
                                    @if ($order->payment_reference)
                                        <p class="text-[9px] font-mono text-gray-400 mt-0.5 truncate max-w-[130px]" title="{{ $order->payment_reference }}">{{ $order->payment_reference }}</p>
                                    @endif
                                </td>

                                <!-- Status Badge & Quick Change -->
                                <td class="px-6 py-4" id="order-status-badge-{{ $order->id }}">
                                    <div class="space-y-1.5">
                                        <x-status-badge :status="$order->status" />
                                        
                                        <!-- Inline Quick Status Form -->
                                        <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}" class="block">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                class="text-[10px] font-bold border border-gray-200 rounded-lg px-2 py-1 bg-white text-gray-700 hover:border-[#C9A050] focus:outline-none focus:ring-1 focus:ring-[#C9A050] cursor-pointer transition-colors w-full">
                                                @foreach(['pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $val => $lbl)
                                                    <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>
                                </td>

                                <!-- Driver -->
                                <td class="px-6 py-4" id="order-driver-info-{{ $order->id }}">
                                    @if ($order->driver)
                                        <div class="flex items-center space-x-2">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->driver->name) }}&background=0A2E2A&color=C9A050" class="w-7 h-7 rounded-lg" alt="">
                                            <div>
                                                <span class="text-xs font-bold text-gray-900 block leading-tight">{{ $driver->name ?? $order->driver->name }}</span>
                                                <span class="text-[10px] text-gray-400">{{ $order->driver->phone ?? '' }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic block mb-1">Unassigned</span>
                                        <button @click="showAssignModal = true; assignOrderId = {{ $order->id }}; assignDriverId = null" 
                                                class="px-2 py-0.5 text-[9px] font-bold btn-gold rounded-md uppercase">
                                            + Assign
                                        </button>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('orders.invoice', $order) }}" target="_blank" 
                                           class="p-2 text-gray-500 hover:text-[#0A2E2A] hover:bg-gray-100 rounded-xl transition-colors border border-gray-200" 
                                           title="Print / View Tax Invoice">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </a>
                                        <button @click="selected = {{ $order->id }}" 
                                                class="p-2 text-gray-500 hover:text-[#C9A050] hover:bg-[#C9A050]/10 rounded-xl transition-colors border border-gray-200" 
                                                title="View full schedule & order breakdown">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                        <button @click="showAssignModal = true; assignOrderId = {{ $order->id }}; assignDriverId = {{ $order->driver_id ?? 'null' }}" 
                                                class="px-3 py-1.5 text-xs font-bold btn-gold rounded-xl uppercase shadow-xs">
                                            Driver
                                        </button>
                                        <form method="POST" action="{{ route('orders.destroy', $order) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-colors border border-rose-200" title="Delete Order">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center">
                                    <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <h3 class="text-gray-900 font-bold text-base">No orders matching your criteria</h3>
                                    <p class="text-xs text-gray-500 mt-1">Try resetting your schedule or status filter to see all records</p>
                                    <a href="{{ route('orders') }}" class="inline-block mt-4 px-4 py-2 btn-gold text-xs font-bold rounded-xl">Clear Filters</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="p-6 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

        <!-- DETAILED ORDER BREAKDOWN MODAL (Teleported) -->
        <template x-teleport="body">
            @foreach ($orders as $order)
                @php
                    $vCode = '#ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
                    $cName = $order->customer_name ?: ($order->user->name ?? 'Guest User');
                    $cPhone = $order->customer_phone ?: ($order->user->phone ?? '—');
                    $cAddress = $order->delivery_address ?: ($order->address ?: 'Standard Delivery');

                    // Group order items by scheduled_date
                    $groupedItems = $order->items->groupBy(function($item) {
                        return $item->scheduled_date ? \Carbon\Carbon::parse($item->scheduled_date)->format('Y-m-d') : 'Immediate Delivery';
                    });
                @endphp
                <div x-show="selected === {{ $order->id }}"
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="display: none;">

                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="selected = null"></div>

                    <!-- Modal Window -->
                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-3xl w-full overflow-hidden transform transition-all my-8 max-h-[90vh] flex flex-col"
                         x-show="selected === {{ $order->id }}"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                        <!-- Modal Header -->
                        <div class="bg-[#0A2E2A] px-8 py-6 flex items-center justify-between shrink-0 border-b border-white/10">
                            <div class="flex items-center space-x-3.5">
                                <div class="bg-white/10 p-2.5 rounded-2xl">
                                    <svg class="w-6 h-6 text-[#C9A050]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-white/60 font-semibold">Order Details & Schedule</p>
                                    <h3 class="text-xl font-black text-white tracking-tight">{{ $vCode }}</h3>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div id="modal-order-status-badge-{{ $order->id }}">
                                    <x-status-badge :status="$order->status" />
                                </div>
                                <button @click="selected = null" class="p-2.5 bg-white/10 text-white/70 hover:text-white rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body (Scrollable) -->
                        <div class="p-6 sm:p-8 space-y-6 overflow-y-auto custom-scrollbar flex-1">
                            <!-- Customer & Delivery Information Card -->
                            <div class="bg-gray-50/80 rounded-3xl p-5 border border-gray-100">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Customer Information</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex items-center space-x-3.5">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($cName) }}&background=0A2E2A&color=C9A050&bold=true" class="w-12 h-12 rounded-2xl shadow-xs" alt="">
                                        <div>
                                            <p class="text-base font-bold text-gray-900">{{ $cName }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->user->email ?? 'Guest Checkout' }}</p>
                                            @if ($cPhone && $cPhone !== '—')
                                                <a href="tel:{{ $cPhone }}" class="inline-flex items-center text-xs font-bold text-[#C9A050] hover:underline mt-0.5">
                                                    📞 {{ $cPhone }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="space-y-1.5 text-xs text-gray-600 bg-white p-3 rounded-2xl border border-gray-100">
                                        <p><span class="font-bold text-gray-900">Delivery Address:</span></p>
                                        <p class="text-gray-700 leading-relaxed font-medium">📍 {{ $cAddress }}</p>
                                        @if ($order->dinner_address)
                                            <p class="pt-1 text-[11px] text-purple-700 font-bold border-t border-purple-100">
                                                🌙 Dinner Address: {{ $order->dinner_address }}
                                            </p>
                                        @endif
                                        <p class="pt-1 text-[11px] text-gray-400 border-t border-gray-100">
                                            Placed on: {{ $order->created_at->format('M d, Y · h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Target Schedule Timeline (Grouping by Date) -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Scheduled Dishes Timeline</h4>
                                    <span class="text-xs font-bold text-[#C9A050]">{{ $order->items->count() }} Items Ordered</span>
                                </div>

                                <div class="space-y-4">
                                    @foreach ($groupedItems as $dateStr => $itemsForDate)
                                        @php
                                            $dateLabel = $dateStr === 'Immediate Delivery' 
                                                ? 'Immediate Delivery' 
                                                : \Carbon\Carbon::parse($dateStr)->format('l, F d, Y');
                                            $isDateToday = ($dateStr === $todayStr);
                                            $isDateTomorrow = ($dateStr === $tomorrowStr);
                                        @endphp
                                        <div class="rounded-2xl border {{ $isDateToday ? 'border-amber-300 bg-amber-50/20' : 'border-gray-200 bg-white' }} overflow-hidden">
                                            <!-- Date Header -->
                                            <div class="px-5 py-3 {{ $isDateToday ? 'bg-amber-100/60' : 'bg-gray-50' }} border-b border-gray-200/80 flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm font-bold text-gray-900">📅 {{ $dateLabel }}</span>
                                                    @if ($isDateToday)
                                                        <span class="px-2 py-0.5 bg-amber-500 text-white text-[9px] font-black rounded-full uppercase animate-pulse">TODAY</span>
                                                    @elseif ($isDateTomorrow)
                                                        <span class="px-2 py-0.5 bg-blue-600 text-white text-[9px] font-black rounded-full uppercase">TOMORROW</span>
                                                    @endif
                                                </div>
                                                <span class="text-xs font-bold text-gray-500">{{ $itemsForDate->count() }} {{ \Illuminate\Support\Str::plural('dish', $itemsForDate->count()) }}</span>
                                            </div>

                                            <!-- Items for this specific Date -->
                                            <div class="divide-y divide-gray-100">
                                                @foreach ($itemsForDate as $item)
                                                    <div class="p-4 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                                                        <div class="flex items-center space-x-3.5">
                                                            @if ($item->food && $item->food->image_url)
                                                                <img src="{{ $item->food->image_url }}" class="w-12 h-12 rounded-xl object-cover shadow-xs shrink-0" alt="">
                                                            @else
                                                                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 font-bold shrink-0">🍽️</div>
                                                            @endif
                                                            <div>
                                                                <div class="flex items-center space-x-2">
                                                                    <p class="text-sm font-bold text-gray-900">{{ $item->food->name ?? 'Dish' }}</p>
                                                                    @if ($item->meal_type)
                                                                        <span class="px-2 py-0.5 text-[9px] font-black rounded-md uppercase tracking-wider
                                                                            @if(strtolower($item->meal_type) === 'breakfast') bg-amber-100 text-amber-800
                                                                            @elseif(strtolower($item->meal_type) === 'lunch') bg-blue-100 text-blue-800
                                                                            @else bg-purple-100 text-purple-800 @endif">
                                                                            {{ $item->meal_type }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <p class="text-xs text-gray-500 mt-0.5">Rs. {{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                                                            </div>
                                                        </div>
                                                        <span class="text-sm font-black text-gray-900">
                                                            Rs. {{ number_format($item->price * $item->quantity, 2) }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Financial Summary Breakdown -->
                            <div class="bg-[#0A2E2A]/5 rounded-3xl p-5 border border-[#0A2E2A]/10 space-y-2">
                                <div class="flex items-center justify-between text-xs text-gray-600">
                                    <span>Subtotal:</span>
                                    <span class="font-bold text-gray-900">Rs. {{ number_format($order->total_price + ($order->discount_amount ?? 0), 2) }}</span>
                                </div>
                                @if ($order->coupon_code && $order->discount_amount > 0)
                                    <div class="flex items-center justify-between text-xs text-emerald-700 font-semibold">
                                        <span>Coupon Discount ({{ $order->coupon_code }}):</span>
                                        <span>- Rs. {{ number_format($order->discount_amount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between text-base font-black text-gray-900 pt-2 border-t border-[#0A2E2A]/10">
                                    <span>Total Invoiced:</span>
                                    <span class="text-xl text-[#0A2E2A]">Rs. {{ number_format($order->total_price, 2) }}</span>
                                </div>
                            </div>

                            <!-- Payment & Bank Slip Verification Card -->
                            <div class="bg-gray-50/90 rounded-3xl p-5 border border-gray-200/80 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Payment & Verification</h4>
                                        <p class="text-xs text-gray-500 mt-0.5">Manage bank transfer slip and payment status</p>
                                    </div>
                                    @if ($order->payment_status === 'verified' || $order->payment_status === 'paid')
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg border border-emerald-200 flex items-center gap-1">
                                            ✓ Payment Verified
                                        </span>
                                    @elseif ($order->payment_status === 'rejected')
                                        <span class="px-2.5 py-1 bg-rose-100 text-rose-800 text-xs font-bold rounded-lg border border-rose-200 flex items-center gap-1">
                                            ✕ Verification Rejected
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-lg border border-amber-200 flex items-center gap-1 animate-pulse">
                                            ⏳ Awaiting Slip Verification
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                    <div class="space-y-2 bg-white p-4 rounded-2xl border border-gray-100">
                                        <div>
                                            <span class="text-gray-400 block text-[10px] uppercase font-bold">Payment Method</span>
                                            <span class="font-bold text-gray-900 text-sm">{{ ucfirst($order->payment_method ?? 'Direct Bank Transfer') }}</span>
                                        </div>
                                        @if ($order->payment_reference)
                                            <div>
                                                <span class="text-gray-400 block text-[10px] uppercase font-bold">Customer Reference</span>
                                                <span class="font-mono font-bold text-[#C9A050] text-sm">{{ $order->payment_reference }}</span>
                                            </div>
                                        @endif
                                        @if ($order->is_subscription)
                                            <div class="pt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-bold rounded border border-purple-200">
                                                    🔄 Recurring Monthly Plan
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-white p-4 rounded-2xl border border-gray-100 flex flex-col justify-between">
                                        <div>
                                            <span class="text-gray-400 block text-[10px] uppercase font-bold mb-1">Transfer Receipt Slip</span>
                                            @if ($order->bank_receipt_url)
                                                <a href="{{ $order->bank_receipt_url }}" target="_blank" class="inline-flex items-center gap-1 text-[#C9A050] hover:underline font-bold text-xs mb-2">
                                                    📄 View Full Uploaded Slip &rarr;
                                                </a>
                                                <div class="mt-1">
                                                    <a href="{{ $order->bank_receipt_url }}" target="_blank">
                                                        <img src="{{ $order->bank_receipt_url }}" class="h-28 w-auto max-w-full rounded-xl border border-gray-200 object-cover shadow-xs hover:opacity-90 transition-opacity" alt="Bank Slip">
                                                    </a>
                                                </div>
                                            @else
                                                <p class="text-gray-400 italic text-xs py-2">No bank transfer slip attached.</p>
                                            @endif
                                        </div>

                                        <!-- Verification Actions -->
                                        <div class="flex items-center space-x-2 pt-3 mt-3 border-t border-gray-100">
                                            <form method="POST" action="{{ route('orders.verifyPayment', $order->id) }}" class="inline-block">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-colors shadow-xs">
                                                    ✓ Verify & Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('orders.rejectPayment', $order->id) }}" class="inline-block" onsubmit="return confirm('Mark this transfer slip as rejected?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold rounded-xl text-xs transition-colors">
                                                    ✕ Reject Slip
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Assigned Driver & Status Changer -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-[#C9A050]/5 border border-[#C9A050]/20 rounded-2xl p-4 flex items-center space-x-3.5">
                                    <div class="bg-[#C9A050]/20 p-2.5 rounded-xl text-[#C9A050]">
                                        🛵
                                    </div>
                                    <div class="min-w-0">
                                        <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Assigned Driver</h5>
                                        @if ($order->driver)
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ $order->driver->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->driver->phone ?? 'No phone' }}</p>
                                        @else
                                            <p class="text-xs text-gray-400 italic">No driver assigned yet</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Status Changer in Modal -->
                                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col justify-between">
                                    <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Update Order Status</h5>
                                    <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-center space-x-2">
                                            <select name="status" class="input py-2 text-xs font-bold rounded-xl flex-1">
                                                @foreach(['pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $val => $lbl)
                                                    <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="px-4 py-2 btn-gold text-xs font-bold rounded-xl shrink-0">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="p-6 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3 shrink-0 flex-wrap">
                            <button @click="showAssignModal = true; assignOrderId = {{ $order->id }}; assignDriverId = {{ $order->driver_id ?? 'null' }}"
                                    class="px-5 py-2.5 btn-gold rounded-xl text-xs font-bold uppercase shadow-xs">
                                🛵 Assign / Change Driver
                            </button>

                            <div class="flex items-center space-x-2">
                                <a href="{{ route('orders.invoice', $order) }}" target="_blank"
                                   class="px-4 py-2.5 bg-[#0A2E2A] text-white hover:bg-black rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Print Tax Invoice
                                </a>
                                <button @click="selected = null" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl text-xs font-bold transition-colors">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </template>
    </div>

    <!-- Live Real-Time WebSockets Listener -->
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                window.Echo.channel('orders')
                    .listen('OrderStatusUpdated', (e) => {
                        if (e.stats) {
                            const totalEl = document.getElementById('total-orders-count');
                            const pendingEl = document.getElementById('pending-orders-count');
                            if (totalEl) totalEl.innerText = e.stats.total;
                            if (pendingEl) pendingEl.innerText = e.stats.pending;
                        }

                        const renderBadge = (status) => {
                            const map = {
                                'pending': { label: 'Pending', cls: 'bg-amber-50 text-amber-600 ring-amber-500/20' },
                                'processing': { label: 'Processing', cls: 'bg-blue-50 text-blue-600 ring-blue-500/20' },
                                'delivered': { label: 'Delivered', cls: 'bg-emerald-50 text-emerald-600 ring-emerald-500/20' },
                                'completed': { label: 'Completed', cls: 'bg-emerald-50 text-emerald-600 ring-emerald-500/20' },
                                'cancelled': { label: 'Cancelled', cls: 'bg-rose-50 text-rose-600 ring-rose-500/20' },
                            };
                            const info = map[status] || {
                                label: status.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()),
                                cls: 'bg-gray-100 text-gray-600 ring-gray-400/20'
                            };
                            return `
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold rounded-full ring-1 ring-inset shadow-sm ${info.cls}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                                    ${info.label}
                                </span>
                            `;
                        };

                        const tableBadge = document.getElementById(`order-status-badge-${e.id}`);
                        if (tableBadge) {
                            const badgeSpan = tableBadge.querySelector('span');
                            if (badgeSpan) badgeSpan.outerHTML = renderBadge(e.status);
                        }

                        const modalBadge = document.getElementById(`modal-order-status-badge-${e.id}`);
                        if (modalBadge) {
                            modalBadge.innerHTML = renderBadge(e.status);
                        }

                        const formattedId = '#ORD-' + String(e.id).padStart(4, '0');
                        const statusFormatted = e.status.replace(/_/g, ' ');
                        const toastMsg = e.action === 'created'
                            ? `New Order ${formattedId} received from ${e.customer_name}`
                            : `Order ${formattedId} is now ${statusFormatted}${e.driver ? ' (' + e.driver.name + ')' : ''}`;
                        showToast(toastMsg, e.status);
                    });

                window.Echo.channel('drivers')
                    .listen('DriverStatusUpdated', (e) => {
                        showToast(`Driver ${e.name} is now ${e.status.replace('_', ' ')}`, e.status);
                    });
            }
        });

        function showToast(message, status) {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const toast = document.createElement('div');
            let bgClass = 'bg-white border-l-4 border-[#C9A050] text-gray-800 shadow-xl';
            if (status === 'available' || status === 'completed' || status === 'delivered') {
                bgClass = 'bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 shadow-xl';
            } else if (status === 'on_delivery' || status === 'processing') {
                bgClass = 'bg-blue-50 border-l-4 border-blue-500 text-blue-800 shadow-xl';
            } else if (status === 'cancelled' || status === 'offline') {
                bgClass = 'bg-gray-100 border-l-4 border-gray-400 text-gray-800 shadow-xl';
            }

            toast.className = `px-5 py-4 rounded-2xl transform transition-all duration-300 translate-y-4 opacity-0 flex items-center justify-between min-w-[300px] border border-black/5 ${bgClass}`;
            toast.innerHTML = `
                <div class="flex items-center space-x-3">
                    <span class="w-2 h-2 rounded-full bg-current"></span>
                    <span class="font-bold text-sm">${message}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-4 opacity-40 hover:opacity-100 text-lg leading-none">&times;</button>
            `;
            
            container.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-y-4', 'opacity-0'), 10);
            setTimeout(() => {
                toast.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    </script>
</x-app-layout>
