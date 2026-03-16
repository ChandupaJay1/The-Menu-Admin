<x-settings-layout active="security" title="Security">
    <h2 class="text-2xl font-bold text-gray-900 mb-10">Security</h2>

    <div class="space-y-12">
        <!-- Security Alert Section -->
        <div class="flex items-start space-x-6 p-8 bg-gray-50/50 rounded-[2.5rem] border border-gray-100">
            <div class="p-4 bg-white rounded-2xl shadow-sm text-[#C9A050]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Security alert</h3>
                <p class="text-sm text-gray-500 font-medium leading-relaxed">Review recent activity and protect your account from unauthorized access.</p>
            </div>
        </div>

        <!-- Logged In Devices Section -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 mb-6">Where You're logged in</h3>
            <div class="space-y-6">
                <div class="flex items-center space-x-6 p-6 bg-white rounded-3xl border border-gray-100 hover:shadow-md transition-all cursor-pointer group">
                    <div class="p-3 bg-gray-50 rounded-2xl text-gray-400 group-hover:text-[#C9A050] transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Windows - NY, United State</h4>
                        <p class="text-xs font-bold text-green-500 mt-1 uppercase">Active</p>
                    </div>
                </div>

                <div class="flex items-center space-x-6 p-6 bg-white rounded-3xl border border-gray-100 hover:shadow-md transition-all cursor-pointer group">
                    <div class="p-3 bg-gray-50 rounded-2xl text-gray-400 group-hover:text-[#C9A050] transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Samsung - CA, United State</h4>
                        <p class="text-xs font-bold text-gray-400 mt-1 uppercase">Offline</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-settings-layout>