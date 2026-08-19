<x-app-layout>
    @php
        $statusColor = [
            'available'   => 'text-green-600 bg-green-50 border-green-100',
            'on_delivery' => 'text-blue-600 bg-blue-50 border-blue-100',
            'offline'     => 'text-gray-500 bg-gray-100 border-gray-200',
        ];
    @endphp

    <div class="space-y-6" x-data="{ showAssignModal: false, assignEventId: null, assignDriverId: null }">

        @if (session('success'))
            <div class="flex items-center justify-between bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-2xl text-sm font-medium">
                <span>{{ session('success') }}</span>
                <button @click="window.location.reload()" class="text-green-500 hover:text-green-700">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center justify-between bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-2xl text-sm font-medium">
                <span>{{ session('error') }}</span>
                <button @click="window.location.reload()" class="text-red-500 hover:text-red-700">&times;</button>
            </div>
        @endif

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Events Management</h1>
                <p class="text-sm text-gray-500">Manage catering events and assign delivery drivers</p>
            </div>
            <button class="px-4 py-2 bg-[#C9A050] text-white rounded-xl text-sm font-semibold hover:bg-[#B38E46] transition-all shadow-lg shadow-[#C9A050]/20 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Create Event</span>
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-[#0A2E2A]/5 rounded-lg">
                        <svg class="w-6 h-6 text-[#0A2E2A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-[#0A2E2A] bg-[#0A2E2A]/5 px-2 py-1 rounded-full">All</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</h3>
                <p class="text-sm text-gray-500">Total Events</p>
            </div>
            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Soon</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['upcoming'] }}</h3>
                <p class="text-sm text-gray-500">Upcoming</p>
            </div>
            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6 0a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-green-500 bg-green-50 px-2 py-1 rounded-full">Assigned</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['assigned'] }}</h3>
                <p class="text-sm text-gray-500">With Driver</p>
            </div>
            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-amber-500 bg-amber-50 px-2 py-1 rounded-full">Pending</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['unassigned'] }}</h3>
                <p class="text-sm text-gray-500">Needs Driver</p>
            </div>
        </div>

        <!-- Events Table -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Event List</h2>
                <p class="text-xs text-gray-400">Catering events and assigned delivery drivers</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Host</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Cost</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Driver</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($events as $event)
                            @php
                                $sameDay = $event->start_date->equalTo($event->end_date);
                                $dateLabel = $sameDay
                                    ? $event->start_date->format('M d, Y')
                                    : $event->start_date->format('M d') . ' – ' . $event->end_date->format('M d, Y');
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-[#0A2E2A]/5 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-[#0A2E2A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $event->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $event->items->count() }} menu items</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $event->user->name ?? '—' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $dateLabel }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">Rs. {{ number_format($event->total_cost, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($event->driver)
                                        <div class="flex items-center space-x-2">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($event->driver->name) }}&background=0A2E2A&color=C9A050" class="w-7 h-7 rounded-lg" alt="">
                                            <span class="text-sm font-medium text-gray-900">{{ $event->driver->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button @click="showAssignModal = true; assignEventId = {{ $event->id }}; assignDriverId = {{ $event->driver_id ?? 'null' }}" class="px-3 py-1 text-[10px] font-bold bg-[#C9A050] text-white rounded-lg hover:bg-[#B38E46] transition-colors uppercase">Assign Driver</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <h3 class="text-gray-900 font-bold">No events found</h3>
                                    <p class="text-sm text-gray-500 mt-1">Create your first event to get started</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

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

                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAssignModal = false"></div>

                <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden transform transition-all"
                     x-show="showAssignModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                    <form method="POST" :action="'/events/' + assignEventId + '/assign-driver'">
                        @csrf
                        <div class="bg-[#0A2E2A] px-8 py-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-white">Assign Driver</h3>
                                <p class="text-sm text-white/50 mt-1">Event <span class="font-bold text-[#C9A050]" x-text="assignEventId"></span></p>
                            </div>
                            <button type="button" @click="showAssignModal = false" class="p-2 bg-white/10 text-white/70 hover:text-white rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-8">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Select an available driver</p>
                            <div class="space-y-3 max-h-[360px] overflow-y-auto pr-2 custom-scrollbar">
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
                                <button type="button" @click="showAssignModal = false" class="flex-1 px-6 py-4 bg-gray-50 text-gray-500 rounded-2xl font-bold text-sm hover:bg-gray-100 transition-all">Cancel</button>
                                <button type="submit" class="flex-1 px-6 py-4 bg-[#C9A050] text-white rounded-2xl font-bold text-sm hover:bg-[#B38E46] transition-all shadow-xl shadow-[#C9A050]/20">Confirm Assignment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
