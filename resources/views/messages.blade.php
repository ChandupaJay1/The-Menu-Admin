<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <nav class="flex text-sm text-gray-500 mb-2">
                <a href="#" class="hover:text-[#C9A050]">Messages</a>
                <span class="mx-2">></span>
                <span class="text-gray-900 font-semibold">Front of House Team</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
        </div>
        
        <div class="flex space-x-2">
            <button class="p-2 bg-white rounded-xl shadow-sm text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 h-[calc(100vh-200px)]">
        <!-- Teams/Personal List -->
        <div class="lg:w-1/3 bg-white rounded-[2.5rem] p-8 flex flex-col shadow-sm border border-gray-100 overflow-hidden">
            <div class="relative mb-8">
                <input type="text" placeholder="Search..." class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#C9A050] transition-all">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div class="flex-grow overflow-y-auto space-y-8 pr-2 custom-scrollbar">
                <div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Teams</h3>
                    <div class="space-y-6">
                        <div class="flex items-center space-x-4 cursor-pointer group">
                            <img src="https://ui-avatars.com/api/?name=Front+of+House&background=C9A050&color=fff" class="w-14 h-14 rounded-[1.2rem] shadow-md group-hover:scale-105 transition-transform" alt="FOH">
                            <div class="flex-grow overflow-hidden">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold text-gray-900 truncate">Front of House</h4>
                                    <span class="text-xs text-gray-400">2 min ago</span>
                                </div>
                                <p class="text-sm text-gray-500 truncate font-medium">Sandra James: Table 12 is ready for checkout.</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 cursor-pointer group">
                            <img src="https://ui-avatars.com/api/?name=Kitchen&background=0A2E2A&color=fff" class="w-14 h-14 rounded-[1.2rem] shadow-md group-hover:scale-105 transition-transform" alt="Kitchen">
                            <div class="flex-grow overflow-hidden relative">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold text-gray-900 truncate">Kitchen</h4>
                                    <span class="text-xs text-gray-400">Just Now</span>
                                </div>
                                <p class="text-sm text-gray-500 truncate font-medium">Chef Daniel: Two medium steaks for Table 7.</p>
                                <span class="absolute right-0 bottom-1 w-5 h-5 bg-[#C9A050] text-white text-[10px] font-bold flex items-center justify-center rounded-full shadow-lg shadow-[#C9A050]/40">8</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Personal</h3>
                    <div class="space-y-6">
                        <div class="flex items-center space-x-4 cursor-pointer group">
                            <img src="https://ui-avatars.com/api/?name=Maria+Gomez&background=FBBC05&color=fff" class="w-14 h-14 rounded-[1.2rem] shadow-md group-hover:scale-105 transition-transform" alt="Maria">
                            <div class="flex-grow overflow-hidden">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold text-gray-900 truncate">Maria Gomez</h4>
                                    <span class="text-xs text-gray-400">12 min ago</span>
                                </div>
                                <p class="text-sm text-gray-500 truncate font-medium">Can you approve the void on Order #284?</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 cursor-pointer group">
                            <img src="https://ui-avatars.com/api/?name=Daniel+Okafor&background=34A853&color=fff" class="w-14 h-14 rounded-[1.2rem] shadow-md group-hover:scale-105 transition-transform" alt="Daniel">
                            <div class="flex-grow overflow-hidden relative">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold text-gray-900 truncate">Daniel Okafor</h4>
                                    <span class="text-xs text-gray-400">20 min ago</span>
                                </div>
                                <p class="text-sm text-gray-500 truncate font-medium">I'll handle the large party at 6 PM.</p>
                                <span class="absolute right-0 bottom-1 w-5 h-5 bg-[#C9A050] text-white text-[10px] font-bold flex items-center justify-center rounded-full shadow-lg shadow-[#C9A050]/40">2</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:w-2/3 bg-white rounded-[2.5rem] flex flex-col shadow-sm border border-gray-100 overflow-hidden">
            <!-- Chat Header -->
            <div class="p-8 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="https://ui-avatars.com/api/?name=Front+of+House&background=C9A050&color=fff" class="w-12 h-12 rounded-[1rem]" alt="FOH">
                    <div>
                        <h3 class="font-bold text-gray-900">Front of House Team</h3>
                        <p class="text-xs text-green-500 font-bold flex items-center">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                            4 members online
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="p-3 bg-gray-50 rounded-xl text-gray-400 hover:text-[#C9A050] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 5.716V5z"></path></svg>
                    </button>
                    <button class="p-3 bg-gray-50 rounded-xl text-gray-400 hover:text-[#C9A050] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </button>
                    <button class="p-3 bg-gray-50 rounded-xl text-gray-400 hover:text-[#C9A050] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="flex-grow overflow-y-auto p-8 space-y-8 custom-scrollbar bg-gray-50/50">
                <div class="flex justify-center">
                    <span class="px-4 py-1 bg-white rounded-full text-[10px] font-bold text-gray-400 uppercase tracking-widest shadow-sm">Today, Feb 10</span>
                </div>

                <!-- Received Message -->
                <div class="flex items-start space-x-4 max-w-xl">
                    <img src="https://ui-avatars.com/api/?name=Maria+Gomez&background=FBBC05&color=fff" class="w-10 h-10 rounded-[0.8rem]" alt="Maria">
                    <div class="space-y-2">
                        <div class="bg-white p-4 rounded-[1.2rem] rounded-tl-none shadow-sm border border-gray-100">
                            <p class="text-xs font-bold text-[#C9A050] mb-1">Maria:</p>
                            <p class="text-sm font-medium text-gray-700 leading-relaxed">Table 12 is asking for the bill, and also confirm the order please.</p>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400">8:14 AM</span>
                    </div>
                </div>

                <!-- Sent Message -->
                <div class="flex items-start justify-end space-x-4">
                    <div class="space-y-2 flex flex-col items-end max-w-xl">
                        <div class="bg-[#C9A050] p-4 rounded-[1.2rem] rounded-tr-none shadow-lg shadow-[#C9A050]/20">
                            <p class="text-xs font-bold text-white/60 mb-1">You:</p>
                            <p class="text-sm font-medium text-white leading-relaxed">Send it through POS now.</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-[10px] font-bold text-gray-400">8:15 AM</span>
                            <img src="https://ui-avatars.com/api/?name=Joy+Ezechukwu&background=C9A050&color=fff" class="w-4 h-4 rounded-full" alt="Me">
                        </div>
                    </div>
                </div>

                <!-- Another Message -->
                <div class="flex items-start space-x-4 max-w-xl">
                    <img src="https://ui-avatars.com/api/?name=Sandra+James&background=0A2E2A&color=fff" class="w-10 h-10 rounded-[0.8rem]" alt="Sandra">
                    <div class="space-y-2">
                        <div class="bg-white p-4 rounded-[1.2rem] rounded-tl-none shadow-sm border border-gray-100">
                            <p class="text-xs font-bold text-[#C9A050] mb-1">Sandra:</p>
                            <p class="text-sm font-medium text-gray-700 leading-relaxed">Split payment or single?</p>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400">8:24 AM</span>
                    </div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="p-8 border-t border-gray-100 bg-white">
                <div class="flex items-center space-x-4 bg-gray-50 rounded-[1.5rem] p-2 pr-4">
                    <button class="p-3 text-gray-400 hover:text-[#C9A050] transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>
                    <input type="text" placeholder="Write a message..." class="flex-grow bg-transparent border-none focus:ring-0 text-sm font-medium">
                    <button class="p-3 text-gray-400 hover:text-[#C9A050] transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                    </button>
                    <button class="p-4 bg-[#C9A050] text-white rounded-2xl shadow-lg shadow-[#C9A050]/20 hover:bg-[#B38E46] transition-all">
                        <svg class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
                    </button>
                </div>
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