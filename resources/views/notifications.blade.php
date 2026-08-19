<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <nav class="flex text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#C9A050]">Dashboard</a>
                <span class="mx-2">></span>
                <span class="text-gray-900 font-semibold">Notifications</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Unread summary badge -->
            <div class="flex items-center space-x-2 bg-white px-4 py-2.5 rounded-xl shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span x-show="unreadCount > 0" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#C9A050] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5" :class="unreadCount > 0 ? 'bg-[#C9A050]' : 'bg-gray-300'"></span>
                </span>
                <span class="text-xs font-bold text-gray-500">
                    <span x-text="unreadCount"></span> Unread
                </span>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto space-y-6" x-data="{
        activeType: 'all',
        showUnreadOnly: false,
        notifications: [
            { id: 1, type: 'order', title: 'New Regular Order!', description: 'Order #ORD-2024-001 has been placed by Kasun Perera.', time: '2 mins ago', unread: true },
            { id: 2, type: 'event', title: 'Event Reminder', description: 'Wedding event for Mr. Sunil is scheduled for tomorrow at Grand Regency Hall.', time: '1 hour ago', unread: true },
            { id: 3, type: 'driver', title: 'Driver Assigned', description: 'Saman Kumara has been assigned to order #ORD-2024-001.', time: '3 hours ago', unread: false },
            { id: 4, type: 'message', title: 'New Message', description: 'Amara Silva sent a message regarding Order #ORD-2024-002.', time: '4 hours ago', unread: true },
            { id: 5, type: 'order', title: 'Order Delivered', description: 'Order #ORD-2024-002 was successfully delivered to Amara Silva.', time: '5 hours ago', unread: false },
            { id: 6, type: 'event', title: 'New Event Inquiry', description: 'Birthday Party inquiry received for 50 pax on June 10.', time: 'Yesterday', unread: false },
            { id: 7, type: 'system', title: 'System Update', description: 'The Menu Admin has been updated to version 2.1.0 with new features.', time: '2 days ago', unread: false }
        ],
        get unreadCount() {
            return this.notifications.filter(n => n.unread).length;
        },
        get filteredNotifications() {
            return this.notifications.filter(n => {
                const typeMatch = this.activeType === 'all' || n.type === this.activeType;
                const unreadMatch = !this.showUnreadOnly || n.unread;
                return typeMatch && unreadMatch;
            });
        },
        markAllRead() {
            this.notifications.forEach(n => n.unread = false);
        },
        markRead(id) {
            const n = this.notifications.find(x => x.id === id);
            if (n) n.unread = false;
        },
        dismiss(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        },
        iconFor(type) {
            return {
                order: 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                event: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                driver: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                message: 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z',
                system: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            }[type] || 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
        }
    }">
        <!-- Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Type Filter -->
            <div class="flex bg-white p-1.5 rounded-[1.5rem] shadow-sm border border-gray-100 overflow-x-auto no-scrollbar">
                <button @click="activeType = 'all'" :class="activeType === 'all' ? 'bg-[#C9A050] text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-5 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap">All</button>
                <button @click="activeType = 'order'" :class="activeType === 'order' ? 'bg-[#C9A050] text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-5 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Orders</button>
                <button @click="activeType = 'event'" :class="activeType === 'event' ? 'bg-[#C9A050] text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-5 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Events</button>
                <button @click="activeType = 'driver'" :class="activeType === 'driver' ? 'bg-[#C9A050] text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-5 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Drivers</button>
                <button @click="activeType = 'message'" :class="activeType === 'message' ? 'bg-[#C9A050] text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-5 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap">Messages</button>
                <button @click="activeType = 'system'" :class="activeType === 'system' ? 'bg-[#C9A050] text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-5 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap">System</button>
            </div>

            <!-- Unread / All toggle + Mark all read -->
            <div class="flex items-center space-x-3">
                <div class="flex bg-white p-1 rounded-xl shadow-sm border border-gray-100">
                    <button @click="showUnreadOnly = false" :class="!showUnreadOnly ? 'bg-[#0A2E2A] text-white' : 'text-gray-500'" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all">All</button>
                    <button @click="showUnreadOnly = true" :class="showUnreadOnly ? 'bg-[#0A2E2A] text-white' : 'text-gray-500'" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all">Unread</button>
                </div>
                <button @click="markAllRead()" :disabled="unreadCount === 0" class="text-sm font-bold text-[#C9A050] hover:underline disabled:text-gray-300 disabled:no-underline">
                    Mark all as read
                </button>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="space-y-3">
            <template x-for="notification in filteredNotifications" :key="notification.id">
                <div
                    class="card p-5 rounded-[2rem] flex items-start space-x-4 transition-all hover:shadow-xl group relative overflow-hidden"
                    :class="notification.unread ? 'border-[#C9A050]/30 bg-[#C9A050]/[0.03]' : 'border-gray-100'"
                >
                    <!-- Unread Indicator -->
                    <div x-show="notification.unread" class="absolute left-0 top-0 bottom-0 w-1 bg-[#C9A050]"></div>

                    <!-- Type Icon -->
                    <div class="p-3 rounded-2xl flex-shrink-0" :class="{
                        'bg-blue-50 text-blue-500': notification.type === 'order',
                        'bg-amber-50 text-amber-500': notification.type === 'event',
                        'bg-green-50 text-green-500': notification.type === 'driver',
                        'bg-purple-50 text-purple-500': notification.type === 'message',
                        'bg-gray-100 text-gray-500': notification.type === 'system'
                    }">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconFor(notification.type)"></path>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center space-x-2">
                                <h4 class="text-base font-bold text-gray-900" x-text="notification.title"></h4>
                                <!-- Unread dot badge -->
                                <span x-show="notification.unread" class="w-2 h-2 rounded-full bg-[#C9A050]" title="Unread"></span>
                            </div>
                            <span class="text-xs text-gray-400 font-medium" x-text="notification.time"></span>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed" x-text="notification.description"></p>

                        <!-- Actions -->
                        <div class="mt-4 flex space-x-2 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                            <button @click="markRead(notification.id)" x-show="notification.unread" class="px-3 py-1.5 btn-soft text-[10px] font-bold rounded-lg uppercase hover:-translate-y-0.5 transition-all">Mark read</button>
                            <button class="px-3 py-1.5 bg-gray-50 text-gray-600 text-[10px] font-bold rounded-lg hover:bg-gray-100 hover:-translate-y-0.5 transition-all uppercase">View details</button>
                            <button @click="dismiss(notification.id)" class="px-3 py-1.5 bg-gray-50 text-gray-600 text-[10px] font-bold rounded-lg hover:bg-red-50 hover:text-red-500 hover:-translate-y-0.5 transition-all uppercase">Dismiss</button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
                <div x-show="filteredNotifications.length === 0" class="card py-20 text-center rounded-[2rem] border-dashed border-[#C9A050]/20">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="text-gray-900 font-bold">No notifications found</h3>
                <p class="text-sm text-gray-500 mt-1">Try changing your filter settings</p>
            </div>
        </div>
    </div>
</x-app-layout>
