<x-app-layout>
    @php
        $statusStyles = [
            'available'   => ['label' => 'Available',   'class' => 'bg-green-50 text-green-600 border-green-100', 'dot' => 'bg-green-500'],
            'busy'        => ['label' => 'Busy',        'class' => 'bg-amber-50 text-amber-600 border-amber-100', 'dot' => 'bg-amber-500'],
            'on_delivery' => ['label' => 'On Delivery', 'class' => 'bg-blue-50 text-blue-600 border-blue-100',   'dot' => 'bg-blue-500'],
            'offline'     => ['label' => 'Offline',     'class' => 'bg-gray-100 text-gray-500 border-gray-200',   'dot' => 'bg-gray-400'],
        ];
    @endphp

    <div class="space-y-6" x-data="{ showAddModal: false }">

        @if (session('success'))
            <div class="flex items-center justify-between bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-2xl text-sm font-medium">
                <span>{{ session('success') }}</span>
                <button @click="window.location.reload()" class="text-green-500 hover:text-green-700">&times;</button>
            </div>
        @endif

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Drivers Management</h1>
                <p class="text-sm text-gray-500">Manage your delivery team and track their availability</p>
            </div>
            <button @click="showAddModal = true" class="px-4 py-2 btn-gold rounded-xl text-sm font-semibold flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add New Driver</span>
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-[#0A2E2A]/5 rounded-lg">
                        <svg class="w-6 h-6 text-[#0A2E2A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-[#0A2E2A] bg-[#0A2E2A]/5 px-2 py-1 rounded-full">All</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900" id="total-drivers-count">{{ $stats['total'] }}</h3>
                <p class="text-sm text-gray-500">Total Drivers</p>
            </div>
            <div class="card-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-green-500 bg-green-50 px-2 py-1 rounded-full">Ready</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900" id="available-drivers-count">{{ $stats['available'] }}</h3>
                <p class="text-sm text-gray-500">Available</p>
            </div>
            <div class="card-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Busy</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900" id="on_delivery-drivers-count">{{ $stats['on_delivery'] }}</h3>
                <p class="text-sm text-gray-500">On Delivery</p>
            </div>
            <div class="card-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-full">Idle</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900" id="offline-drivers-count">{{ $stats['offline'] }}</h3>
                <p class="text-sm text-gray-500">Offline</p>
            </div>
        </div>

        <!-- Drivers Table -->
        <div class="card overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Delivery Team</h2>
                <p class="text-xs text-gray-400">Manage drivers, vehicles and live status</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Driver</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Deliveries</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($drivers as $driver)
                            @php $s = $statusStyles[$driver->status] ?? $statusStyles['offline']; @endphp
                            <tr id="driver-row-{{ $driver->id }}" class="hover:bg-[#0A2E2A]/[0.02] transition-all duration-300">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=0A2E2A&color=C9A050" class="w-10 h-10 rounded-xl shadow-sm" alt="">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $driver->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $driver->email ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $driver->phone ?? '—' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div id="driver-status-badge-{{ $driver->id }}">
                                        <x-status-badge :status="$driver->status" />
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($driver->vehicle_type || $driver->vehicle_number)
                                        <p class="text-sm text-gray-600">{{ $driver->vehicle_type }} <span class="text-gray-400">· {{ $driver->vehicle_number }}</span></p>
                                    @else
                                        <p class="text-sm text-gray-400">—</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">{{ $driver->total_deliveries }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <!-- Update status -->
                                        <form method="POST" action="{{ route('drivers.updateStatus', $driver) }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" aria-label="Change status" id="driver-status-select-{{ $driver->id }}"
                                                @change="$el.closest('form').submit()"
                                                class="text-[11px] font-bold rounded-lg border border-gray-200 bg-gray-50 px-2 py-1.5 text-gray-600 outline-none focus:ring-2 focus:ring-[#C9A050] cursor-pointer transition-all hover:border-[#C9A050]/40">
                                                <option value="available" @selected($driver->status === 'available')>Set Available</option>
                                                <option value="busy" @selected($driver->status === 'busy')>Set Busy</option>
                                                <option value="on_delivery" @selected($driver->status === 'on_delivery')>Set On Delivery</option>
                                                <option value="offline" @selected($driver->status === 'offline')>Set Offline</option>
                                            </select>
                                        </form>

                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('drivers.destroy', $driver) }}" onsubmit="return confirm('Remove this driver?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:-translate-y-0.5 transition-all bg-gray-50 hover:bg-red-50 rounded-lg" title="Remove driver">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <h3 class="text-gray-900 font-bold">No drivers found</h3>
                                    <p class="text-sm text-gray-500 mt-1">Add your first driver to get started</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add New Driver Modal -->
        <template x-teleport="body">
            <div x-show="showAddModal"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">

                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = false"></div>

                <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden transform transition-all"
                     x-show="showAddModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                    <div class="bg-[#0A2E2A] px-8 py-6 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/10 p-2.5 rounded-xl">
                                <svg class="w-6 h-6 text-[#C9A050]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-white">Add New Driver</h3>
                        </div>
                        <button @click="showAddModal = false" class="p-2 bg-white/10 text-white/70 hover:text-white rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('drivers.store') }}" class="p-8 space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-5 py-3.5 input @error('name') border-red-300 ring-1 ring-red-200 @enderror"
                                placeholder="e.g. Saman Kumara">
                            @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full px-5 py-3.5 input @error('email') border-red-300 ring-1 ring-red-200 @enderror"
                                    placeholder="driver@example.com">
                                @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    class="w-full px-5 py-3.5 input"
                                    placeholder="+94 76 123 4567">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Vehicle Type</label>
                                <input type="text" name="vehicle_type" value="{{ old('vehicle_type') }}"
                                    class="w-full px-5 py-3.5 input"
                                    placeholder="Bike / Tuk / Van">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Vehicle Number</label>
                                <input type="text" name="vehicle_number" value="{{ old('vehicle_number') }}"
                                    class="w-full px-5 py-3.5 input"
                                    placeholder="e.g. BE-4521">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Initial Status</label>
                                <select name="status"
                                    class="w-full px-5 py-3.5 input @error('status') border-red-300 ring-1 ring-red-200 @enderror">
                                    <option value="available" @selected(old('status', 'available') === 'available')>Available</option>
                                    <option value="on_delivery" @selected(old('status') === 'on_delivery')>On Delivery</option>
                                    <option value="offline" @selected(old('status') === 'offline')>Offline</option>
                                </select>
                                @error('status')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Total Deliveries</label>
                                <input type="number" name="total_deliveries" value="{{ old('total_deliveries', 0) }}" min="0"
                                    class="w-full px-5 py-3.5 input">
                            </div>
                        </div>

                        <div class="flex space-x-4 pt-2">
                            <button type="button" @click="showAddModal = false" class="flex-1 px-6 py-4 btn-ghost rounded-2xl font-bold text-sm">Cancel</button>
                            <button type="submit" class="flex-1 px-6 py-4 btn-gold rounded-2xl font-bold text-sm">Save Driver</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                window.Echo.channel('drivers')
                    .listen('DriverStatusUpdated', (e) => {
                        // Update stats
                        const stats = e.stats;
                        document.getElementById('total-drivers-count').innerText = stats.total;
                        document.getElementById('available-drivers-count').innerText = stats.available;
                        document.getElementById('on_delivery-drivers-count').innerText = stats.on_delivery;
                        document.getElementById('offline-drivers-count').innerText = stats.offline;

                        // Update driver status badge if it exists
                        const badgeContainer = document.getElementById(`driver-status-badge-${e.id}`);
                        if (badgeContainer) {
                            // Status mapping for badge classes
                            const statusStyles = {
                                'available': { label: 'Available', class: 'bg-green-50 text-green-600 border-green-100', dot: 'bg-green-500' },
                                'busy': { label: 'Busy', class: 'bg-amber-50 text-amber-600 border-amber-100', dot: 'bg-amber-500' },
                                'on_delivery': { label: 'On Delivery', class: 'bg-blue-50 text-blue-600 border-blue-100', dot: 'bg-blue-500' },
                                'offline': { label: 'Offline', class: 'bg-gray-100 text-gray-500 border-gray-200', dot: 'bg-gray-400' }
                            };
                            const style = statusStyles[e.status] || statusStyles['offline'];
                            
                            badgeContainer.innerHTML = `
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border ${style.class}">
                                    <span class="w-1.5 h-1.5 rounded-full ${style.dot} mr-1.5"></span>
                                    ${style.label}
                                </span>
                            `;
                            
                            // Also update the select dropdown value if present
                            const select = document.getElementById(`driver-status-select-${e.id}`);
                            if (select) {
                                select.value = e.status;
                            }
                        }

                        // Show Toast Notification
                        showToast(`${e.name} is now ${e.status.replace('_', ' ')}`, e.status);
                    });
            }
        });

        function showToast(message, status) {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const toast = document.createElement('div');
            
            // Set styles based on status
            let bgClass = 'bg-white border-l-4 border-gray-400';
            if (status === 'available') bgClass = 'bg-green-50 border-l-4 border-green-500 text-green-700';
            else if (status === 'on_delivery') bgClass = 'bg-blue-50 border-l-4 border-blue-500 text-blue-700';
            else if (status === 'offline') bgClass = 'bg-gray-100 border-l-4 border-gray-500 text-gray-700';

            toast.className = `px-4 py-3 rounded shadow-lg transform transition-all duration-300 translate-y-4 opacity-0 flex items-center justify-between min-w-[250px] ${bgClass}`;
            toast.innerHTML = `
                <span class="font-medium text-sm">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 opacity-50 hover:opacity-100 text-lg leading-none">&times;</button>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            }, 10);
            
            // Remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    </script>
    </div>
</x-app-layout>
