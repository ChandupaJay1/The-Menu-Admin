<x-app-layout>
    <div class="space-y-6" x-data="{ activeTab: 'regular', showAssignModal: false, selectedOrder: null }">
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
                    
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Assign Driver</h3>
                                <p class="text-sm text-gray-500 mt-1">Order: <span class="font-bold text-[#C9A050]" x-text="selectedOrder"></span></p>
                            </div>
                            <button @click="showAssignModal = false" class="p-2 bg-gray-50 text-gray-400 hover:text-gray-600 rounded-xl transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            <!-- Driver 1 -->
                            <label class="group relative flex items-center p-4 border-2 border-gray-50 rounded-2xl cursor-pointer hover:border-[#C9A050]/30 hover:bg-[#C9A050]/5 transition-all">
                                <input type="radio" name="driver" class="w-5 h-5 text-[#C9A050] border-gray-300 focus:ring-[#C9A050]">
                                <div class="ml-4 flex items-center space-x-4">
                                    <div class="relative">
                                        <img src="https://ui-avatars.com/api/?name=Saman+Kumara&background=C9A050&color=fff" class="w-12 h-12 rounded-2xl shadow-sm" alt="">
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 group-hover:text-[#C9A050] transition-colors">Saman Kumara</p>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-[10px] font-bold text-green-500 uppercase tracking-wider">Available</span>
                                            <span class="text-[10px] text-gray-400">•</span>
                                            <span class="text-[10px] text-gray-400">Bike: BE-4521</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Driver 2 -->
                            <label class="group relative flex items-center p-4 border-2 border-gray-50 rounded-2xl cursor-pointer hover:border-[#C9A050]/30 hover:bg-[#C9A050]/5 transition-all">
                                <input type="radio" name="driver" class="w-5 h-5 text-[#C9A050] border-gray-300 focus:ring-[#C9A050]">
                                <div class="ml-4 flex items-center space-x-4">
                                    <div class="relative">
                                        <img src="https://ui-avatars.com/api/?name=Nimal+Siripala&background=C9A050&color=fff" class="w-12 h-12 rounded-2xl shadow-sm" alt="">
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-blue-500 border-2 border-white rounded-full"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 group-hover:text-[#C9A050] transition-colors">Nimal Siripala</p>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">On Delivery</span>
                                            <span class="text-[10px] text-gray-400">•</span>
                                            <span class="text-[10px] text-gray-400">Tuk: QH-8892</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="mt-10 flex space-x-4">
                            <button @click="showAssignModal = false" class="flex-1 px-6 py-4 bg-gray-50 text-gray-500 rounded-2xl font-bold text-sm hover:bg-gray-100 transition-all">Discard</button>
                            <button @click="showAssignModal = false" class="flex-1 px-6 py-4 bg-[#C9A050] text-white rounded-2xl font-bold text-sm hover:bg-[#B38E46] transition-all shadow-xl shadow-[#C9A050]/20">Confirm Assignment</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Orders Management</h1>
                <p class="text-sm text-gray-500">Track and manage your real-time food delivery orders</p>
            </div>
            <div class="flex space-x-3">
                <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all flex items-center space-x-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Filter</span>
                </button>
                <button class="px-4 py-2 bg-[#C9A050] text-white rounded-xl text-sm font-semibold hover:bg-[#B38E46] transition-all shadow-lg shadow-[#C9A050]/20 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Create New Order</span>
                </button>
            </div>
        </div>

        <!-- Order Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Active</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">12</h3>
                <p class="text-sm text-gray-500">New Orders</p>
            </div>
            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-orange-50 rounded-lg">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-orange-500 bg-orange-50 px-2 py-1 rounded-full">Preparing</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">8</h3>
                <p class="text-sm text-gray-500">In Kitchen</p>
            </div>
            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-purple-50 rounded-lg">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6 0a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-purple-500 bg-purple-50 px-2 py-1 rounded-full">Delivery</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">5</h3>
                <p class="text-sm text-gray-500">On the Way</p>
            </div>
            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-green-500 bg-green-50 px-2 py-1 rounded-full">Done</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">42</h3>
                <p class="text-sm text-gray-500">Completed Today</p>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Orders List</h2>
                    <p class="text-xs text-gray-400">Manage your regular and event-based orders</p>
                </div>
                <div class="flex bg-gray-100 p-1 rounded-xl">
                    <button 
                        @click="activeTab = 'regular'"
                        :class="activeTab === 'regular' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="px-4 py-2 text-xs font-bold rounded-lg transition-all"
                    >Regular Orders</button>
                    <button 
                        @click="activeTab = 'event'"
                        :class="activeTab === 'event' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="px-4 py-2 text-xs font-bold rounded-lg transition-all"
                    >Event Orders</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Order Details</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Total Price</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Regular Orders -->
                        <template x-if="activeTab === 'regular'">
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">#ORD-2024-001</span>
                                    <p class="text-[10px] text-gray-400">2 mins ago</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="https://ui-avatars.com/api/?name=Kasun+Perera&background=E5E7EB&color=4B5563" class="w-8 h-8 rounded-full" alt="">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">Kasun Perera</p>
                                            <p class="text-xs text-gray-400">Kandy</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-[10px] font-bold bg-gray-100 text-gray-600 rounded-md uppercase">Regular</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">Chicken Kottu (x2)</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">Rs. 2,450.00</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-[10px] font-bold bg-blue-50 text-blue-500 rounded-full border border-blue-100 uppercase">New</span>
                                </td>
                                <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button @click="selectedOrder = '#ORD-2024-001'; showAssignModal = true" class="px-3 py-1 text-[10px] font-bold bg-[#C9A050] text-white rounded-lg hover:bg-[#B38E46] transition-colors uppercase">Assign Driver</button>
                                    <button class="p-2 text-gray-400 hover:text-[#C9A050] transition-colors bg-gray-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                </div>
                            </td>
                            </tr>
                        </template>

                        <!-- Event Orders -->
                        <template x-if="activeTab === 'event'">
                            <tr class="hover:bg-amber-50/30 transition-colors bg-amber-50/10">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-amber-900">#EVT-2024-005</span>
                                    <p class="text-[10px] text-amber-600">Event Date: May 15</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-amber-200 rounded-full flex items-center justify-center text-amber-700 font-bold text-xs">W</div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">Wedding - Mr. Sunil</p>
                                            <p class="text-xs text-gray-400">Grand Regency Hall</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-[10px] font-bold bg-amber-100 text-amber-700 rounded-md uppercase">Event</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">Full Buffet (150 Pax)</p>
                                    <p class="text-[10px] text-amber-600 font-bold">Inquiry Pending</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">Rs. 185,000.00</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-[10px] font-bold bg-orange-50 text-orange-500 rounded-full border border-orange-100 uppercase">Discussion</span>
                                </td>
                                <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button @click="selectedOrder = '#EVT-2024-005'; showAssignModal = true" class="px-3 py-1 text-[10px] font-bold bg-[#C9A050] text-white rounded-lg hover:bg-[#B38E46] transition-colors uppercase">Assign Driver</button>
                                    <button class="p-2 text-gray-400 hover:text-[#C9A050] transition-colors bg-gray-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                </div>
                            </td>
                            </tr>
                        </template>

                        <!-- Additional Regular Orders for testing -->
                        <template x-if="activeTab === 'regular'">
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">#ORD-2024-002</span>
                                    <p class="text-[10px] text-gray-400">15 mins ago</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="https://ui-avatars.com/api/?name=Amara+Silva&background=E5E7EB&color=4B5563" class="w-8 h-8 rounded-full" alt="">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">Amara Silva</p>
                                            <p class="text-xs text-gray-400">Colombo 07</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-[10px] font-bold bg-gray-100 text-gray-600 rounded-md uppercase">Regular</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">Cheese Burger Meal</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">Rs. 1,850.00</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-[10px] font-bold bg-orange-50 text-orange-500 rounded-full border border-orange-100 uppercase">Preparing</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button class="p-2 text-gray-400 hover:text-[#C9A050] transition-colors bg-gray-50 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400 font-bold" x-text="activeTab === 'regular' ? 'Showing 2 regular orders' : 'Showing 1 event order'"></p>
                <div class="flex space-x-2">
                    <button class="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button class="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
