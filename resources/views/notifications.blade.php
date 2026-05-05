<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6" x-data="{ 
        activeFilter: 'all',
        notifications: [
            { id: 1, type: 'order', title: 'New Regular Order!', description: 'Order #ORD-2024-001 has been placed by Kasun Perera.', time: '2 mins ago', unread: true },
            { id: 2, type: 'event', title: 'Event Reminder', description: 'Wedding event for Mr. Sunil is scheduled for tomorrow at Grand Regency Hall.', time: '1 hour ago', unread: true },
            { id: 3, type: 'driver', title: 'Driver Assigned', description: 'Saman Kumara has been assigned to order #ORD-2024-001.', time: '3 hours ago', unread: false },
            { id: 4, type: 'order', title: 'Order Delivered', description: 'Order #ORD-2024-002 was successfully delivered to Amara Silva.', time: '5 hours ago', unread: false },
            { id: 5, type: 'event', title: 'New Event Inquiry', description: 'Birthday Party inquiry received for 50 pax on June 10.', time: 'Yesterday', unread: false },
            { id: 6, type: 'system', title: 'System Update', description: 'The Menu Admin has been updated to version 2.1.0 with new features.', time: '2 days ago', unread: false }
        ],
        get filteredNotifications() {
            if (this.activeFilter === 'all') return this.notifications;
            return this.notifications.filter(n => n.type === this.activeFilter);
        }
    }">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifications Center</h1>
                <p class="text-sm text-gray-500">Stay updated with all activities across your platform</p>
            </div>
            <button class="text-sm font-bold text-[#C9A050] hover:underline">Mark all as read</button>
        </div>

        <!-- Filters -->
        <div class="flex bg-white p-1.5 rounded-[1.5rem] shadow-sm border border-gray-100 overflow-x-auto no-scrollbar">
            <button @click="activeFilter = 'all'" :class="activeFilter === 'all' ? 'bg-[#C9A050] text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap">All Activities</button>
            <button @click="activeFilter = 'order'" :class="activeFilter === 'order' ? 'bg-[#C9A050] text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Orders</button>
            <button @click="activeFilter = 'event'" :class="activeFilter === 'event' ? 'bg-[#C9A050] text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Events</button>
            <button @click="activeFilter = 'driver'" :class="activeFilter === 'driver' ? 'bg-[#C9A050] text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Drivers</button>
            <button @click="activeFilter = 'system'" :class="activeFilter === 'system' ? 'bg-[#C9A050] text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap">System</button>
        </div>

        <!-- Notifications List -->
        <div class="space-y-3">
            <template x-for="notification in filteredNotifications" :key="notification.id">
                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-gray-100 flex items-start space-x-4 transition-all hover:shadow-md group relative overflow-hidden">
                    <!-- Unread Indicator -->
                    <div x-show="notification.unread" class="absolute left-0 top-0 bottom-0 w-1 bg-[#C9A050]"></div>

                    <div class="p-3 rounded-2xl flex-shrink-0" :class="{
                        'bg-blue-50 text-blue-500': notification.type === 'order',
                        'bg-amber-50 text-amber-500': notification.type === 'event',
                        'bg-green-50 text-green-500': notification.type === 'driver',
                        'bg-purple-50 text-purple-500': notification.type === 'system'
                    }">
                        <template x-if="notification.type === 'order'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </template>
                        <template x-if="notification.type === 'event'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </template>
                        <template x-if="notification.type === 'driver'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </template>
                        <template x-if="notification.type === 'system'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </template>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="text-base font-bold text-gray-900" x-text="notification.title"></h4>
                            <span class="text-xs text-gray-400 font-medium" x-text="notification.time"></span>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed" x-text="notification.description"></p>
                        
                        <div class="mt-4 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="px-3 py-1.5 bg-gray-50 text-gray-600 text-[10px] font-bold rounded-lg hover:bg-gray-100 transition-colors uppercase">View Details</button>
                            <button class="px-3 py-1.5 bg-gray-50 text-gray-600 text-[10px] font-bold rounded-lg hover:bg-gray-100 transition-colors uppercase">Dismiss</button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="filteredNotifications.length === 0" class="py-20 text-center bg-white rounded-[2rem] border border-dashed border-gray-200">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="text-gray-900 font-bold">No notifications found</h3>
                <p class="text-sm text-gray-500 mt-1">Try changing your filter settings</p>
            </div>
        </div>
    </div>
</x-app-layout>
