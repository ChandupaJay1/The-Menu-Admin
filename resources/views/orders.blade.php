<x-app-layout>
    @php
        $statusStyles = [
            'pending'    => ['label' => 'Pending',    'class' => 'bg-amber-50 text-amber-600 border-amber-100'],
            'processing' => ['label' => 'Processing', 'class' => 'bg-blue-50 text-blue-600 border-blue-100'],
            'completed'  => ['label' => 'Completed',  'class' => 'bg-green-50 text-green-600 border-green-100'],
            'cancelled'  => ['label' => 'Cancelled',  'class' => 'bg-red-50 text-red-600 border-red-100'],
        ];
        $totalAll = $counts->sum();
        $pendingCount = $counts->get('pending', 0);
        $processingCount = $counts->get('processing', 0);
        $completedCount = $counts->get('completed', 0);
    @endphp

    <div class="space-y-6" x-data="{ showAssignModal: false, selected: null, assignOrderId: null, assignDriverId: null }">
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
                                <h3 class="text-lg font-bold text-white">Assign Driver</h3>
                                <p class="text-sm text-white/50 mt-1">Order <span class="font-bold text-[#C9A050]" x-text="'#ORD-' + String(assignOrderId).padStart(4, '0')"></span></p>
                            </div>
                            <button type="button" @click="showAssignModal = false" class="p-2 bg-white/10 text-white/70 hover:text-white rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-8">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Select an available driver</p>
                            <div class="space-y-3 max-h-[360px] overflow-y-auto pr-2 custom-scrollbar">
                                <!-- Unassign option -->
                                <label class="group relative flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all"
                                       :class="assignDriverId == null ? 'border-[#C9A050] bg-[#C9A050]/5' : 'border-gray-50 hover:border-[#C9A050]/30 hover:bg-[#C9A050]/5'">
                                    <input type="radio" name="driver_id" value="" :checked="assignDriverId == null" class="w-5 h-5 text-[#C9A050] border-gray-300 focus:ring-[#C9A050]">
                                    <div class="ml-4 flex items-center space-x-4">
                                        <div class="p-3 bg-gray-100 rounded-2xl">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">No driver / Remove assignment</p>
                                            <p class="text-[10px] text-gray-400">Clears the current driver (sets them back to Available)</p>
                                        </div>
                                    </div>
                                </label>

                                @foreach ($drivers as $driver)
                                    @php $dStatus = $driver->status; @endphp
                                    <label class="group relative flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all"
                                           :class="assignDriverId == {{ $driver->id }} ? 'border-[#C9A050] bg-[#C9A050]/5' : 'border-gray-50 hover:border-[#C9A050]/30 hover:bg-[#C9A050]/5'"
                                           :class-disabled="('{{ $dStatus }}' !== 'available') && ({{ $driver->id }} != assignDriverId)"
                                           :disabled="('{{ $dStatus }}' !== 'available') && ({{ $driver->id }} != assignDriverId)">
                                        <input type="radio" name="driver_id" value="{{ $driver->id }}" :checked="assignDriverId == {{ $driver->id }}" class="w-5 h-5 text-[#C9A050] border-gray-300 focus:ring-[#C9A050]">
                                        <div class="ml-4 flex items-center space-x-4">
                                            <div class="relative">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=0A2E2A&color=C9A050" class="w-12 h-12 rounded-2xl shadow-sm" alt="">
                                                @if ($dStatus === 'available')
                                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                                @elseif ($dStatus === 'on_delivery')
                                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-blue-500 border-2 border-white rounded-full"></div>
                                                @else
                                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-gray-400 border-2 border-white rounded-full"></div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900 group-hover:text-[#C9A050] transition-colors">{{ $driver->name }}</p>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-[10px] font-bold uppercase tracking-wider
                                                        @if ($dStatus === 'available') text-green-500
                                                        @elseif ($dStatus === 'on_delivery') text-blue-500
                                                        @else text-gray-400 @endif">{{ $driver->status === 'on_delivery' ? 'On Delivery' : ucfirst($driver->status) }}</span>
                                                    <span class="text-[10px] text-gray-400">•</span>
                                                    <span class="text-[10px] text-gray-400">{{ $driver->phone ?? 'No phone' }}</span>
                                                    @if ($driver->vehicle_type || $driver->vehicle_number)
                                                        <span class="text-[10px] text-gray-400">•</span>
                                                        <span class="text-[10px] text-gray-400">{{ $driver->vehicle_type }} {{ $driver->vehicle_number }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="mt-8 flex space-x-4">
                                <button type="button" @click="showAssignModal = false" class="flex-1 px-6 py-4 btn-ghost rounded-2xl font-bold text-sm">Cancel</button>
                                <button type="submit" class="flex-1 px-6 py-4 btn-gold rounded-2xl font-bold text-sm">Confirm Assignment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Orders Management</h1>
                <p class="text-sm text-gray-500">Track and manage your real-time food delivery orders</p>
            </div>
            <div class="flex space-x-3">
                <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:-translate-y-0.5 transition-all flex items-center space-x-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Filter</span>
                </button>
                <button class="px-4 py-2 btn-gold rounded-xl text-sm font-semibold flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Create New Order</span>
                </button>
            </div>
        </div>

        <!-- Order Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-[#0A2E2A]/5 rounded-lg">
                        <svg class="w-6 h-6 text-[#0A2E2A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-[#0A2E2A] bg-[#0A2E2A]/5 px-2 py-1 rounded-full">All</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ $totalAll }}</h3>
                <p class="text-sm text-gray-500">Total Orders</p>
            </div>
            <div class="card-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-amber-500 bg-amber-50 px-2 py-1 rounded-full">Pending</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ $pendingCount }}</h3>
                <p class="text-sm text-gray-500">Awaiting</p>
            </div>
            <div class="card-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Active</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ $processingCount }}</h3>
                <p class="text-sm text-gray-500">In Progress</p>
            </div>
            <div class="card-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-green-500 bg-green-50 px-2 py-1 rounded-full">Done</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ $completedCount }}</h3>
                <p class="text-sm text-gray-500">Completed</p>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between space-y-4 lg:space-y-0 gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Orders List</h2>
                    <p class="text-xs text-gray-400">Manage and filter all customer orders</p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <!-- Search -->
                    <form method="GET" action="{{ route('orders') }}" class="relative">
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search customer, ID, address..."
                            class="input w-full sm:w-64 pl-10 pr-4 py-2.5 text-sm"
                        >
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </form>

                    <!-- Status Filter -->
                    <div class="flex bg-gray-100 p-1 rounded-xl">
                        @foreach (['all' => 'All', 'pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label)
                            <a
                                href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => null]) }}"
                                class="px-3 py-2 text-xs font-bold rounded-lg transition-all whitespace-nowrap {{ $statusFilter === $key ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                            >{{ $label }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Driver</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            @php
                                $s = $statusStyles[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-gray-100 text-gray-600 border-gray-200'];
                                $code = '#ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
                            @endphp
                            <tr class="hover:bg-[#0A2E2A]/[0.02] transition-all duration-300">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">{{ $code }}</span>
                                    <p class="text-[10px] text-gray-400">{{ $order->created_at->format('M d, Y · h:i A') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'Guest') }}&background=E5E7EB&color=4B5563" class="w-8 h-8 rounded-full" alt="">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $order->user->name ?? 'Guest' }}</p>
                                            <p class="text-xs text-gray-400">{{ $order->address }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-0.5">
                                        @forelse ($order->items as $item)
                                            <p class="text-sm text-gray-600">{{ $item->food->name ?? 'Item' }} <span class="text-gray-400">×{{ $item->quantity }}</span></p>
                                        @empty
                                            <p class="text-sm text-gray-400">—</p>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">Rs. {{ number_format($order->total_price, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <x-status-badge :status="$order->status" />
                                </td>
                                <td class="px-6 py-4">
                                    @if ($order->driver)
                                        <div class="flex items-center space-x-2">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->driver->name) }}&background=0A2E2A&color=C9A050" class="w-7 h-7 rounded-lg" alt="">
                                            <span class="text-sm font-medium text-gray-900">{{ $order->driver->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button @click="selected = {{ $order->id }}" class="p-2 text-gray-400 hover:text-[#C9A050] hover:-translate-y-0.5 transition-all bg-gray-50 hover:bg-[#C9A050]/10 rounded-lg" title="View order">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                        <button @click="showAssignModal = true; assignOrderId = {{ $order->id }}; assignDriverId = {{ $order->driver_id ?? 'null' }}" class="px-3 py-1 text-[10px] font-bold btn-gold rounded-lg uppercase">Assign Driver</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center">
                                    <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <h3 class="text-gray-900 font-bold">No orders found</h3>
                                    <p class="text-sm text-gray-500 mt-1">Try adjusting your search or filters</p>
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

        <!-- View Order Modals -->
        <template x-teleport="body">
            @foreach ($orders as $order)
                @php
                    $vCode = '#ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
                    $vStatus = $statusStyles[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-gray-100 text-gray-600 border-gray-200'];
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

                    <!-- Modal Content -->
                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-2xl w-full overflow-hidden transform transition-all"
                         x-show="selected === {{ $order->id }}"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                        <!-- Header -->
                        <div class="bg-[#0A2E2A] px-8 py-6 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-white/10 p-2.5 rounded-xl">
                                    <svg class="w-6 h-6 text-[#C9A050]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-white/50 font-medium">Order</p>
                                    <h3 class="text-lg font-bold text-white">{{ $vCode }}</h3>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <x-status-badge :status="$order->status" />
                                <button @click="selected = null" class="p-2 bg-white/10 text-white/70 hover:text-white rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-8 space-y-6">
                            <!-- Meta row -->
                            <div class="flex flex-wrap items-center gap-x-8 gap-y-2 text-sm">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Order Date</p>
                                    <p class="font-semibold text-gray-900">{{ $order->created_at->format('M d, Y') }} · {{ $order->created_at->format('h:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Payment</p>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                                </div>
                            </div>

                            <!-- Customer -->
                            <div class="bg-gray-50 rounded-2xl p-5">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Customer Details</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'Guest') }}&background=0A2E2A&color=C9A050" class="w-10 h-10 rounded-xl" alt="">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $order->user->name ?? 'Guest' }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->user->email ?? '—' }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-xs text-gray-500"><span class="font-semibold text-gray-700">Phone:</span> {{ $order->user->phone ?? '—' }}</p>
                                        <p class="text-xs text-gray-500"><span class="font-semibold text-gray-700">Address:</span> {{ $order->address }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Items -->
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Ordered Items</h4>
                                <div class="overflow-hidden rounded-2xl border border-gray-100">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Item</th>
                                                <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider text-center">Qty</th>
                                                <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider text-right">Unit</th>
                                                <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $item->food->name ?? 'Item' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ $item->quantity }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-600 text-right">Rs. {{ number_format($item->price, 2) }}</td>
                                                    <td class="px-4 py-3 text-sm font-bold text-gray-900 text-right">Rs. {{ number_format($item->price * $item->quantity, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-[#0A2E2A]/5">
                                                <td colspan="3" class="px-4 py-3 text-sm font-bold text-gray-700 text-right">Total Amount</td>
                                                <td class="px-4 py-3 text-sm font-black text-[#0A2E2A] text-right">Rs. {{ number_format($order->total_price, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- Driver -->
                            <div class="bg-[#C9A050]/5 border border-[#C9A050]/20 rounded-2xl p-5 flex items-center space-x-4">
                                <div class="bg-[#C9A050]/15 p-3 rounded-xl">
                                    <svg class="w-6 h-6 text-[#C9A050]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6 0a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Assigned Driver</h4>
                                    @if ($order->driver)
                                        <p class="text-sm font-bold text-gray-900">{{ $order->driver->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->driver->phone ?? 'No phone' }}@if($order->driver->vehicle_type || $order->driver->vehicle_number) · {{ $order->driver->vehicle_type }} {{ $order->driver->vehicle_number }}@endif</p>
                                    @else
                                        <p class="text-sm font-medium text-gray-500">No driver assigned yet</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button @click="selected = null" class="px-6 py-3 btn-gold rounded-2xl font-bold text-sm">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </template>
    </div>
</x-app-layout>
