<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center text-sm text-gray-500 mb-2">
                <a href="#" class="hover:text-[#C9A050] transition-colors">Messages</a>
                <svg class="w-3.5 h-3.5 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-900 font-semibold">Front of House Team</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Team Messages</h1>
        </div>
        
        <div class="flex items-center space-x-2">
            <button class="p-2.5 bg-white rounded-2xl shadow-xs border border-gray-100 text-gray-400 hover:text-gray-600 hover:-translate-y-0.5 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 min-h-[600px] lg:h-[calc(100vh-190px)]">
        <!-- Teams/Personal List -->
        <div class="w-full lg:w-1/3 bg-white rounded-[2rem] sm:rounded-[2.5rem] p-5 sm:p-8 flex flex-col shadow-sm border border-[#C9A050]/10 overflow-hidden min-h-[380px] lg:min-h-0">
            <div class="relative mb-6">
                <input type="text" placeholder="Search conversations..." class="w-full pl-11 pr-4 py-3.5 input bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#C9A050] transition-all">
                <svg class="w-4 h-4 text-gray-400 absolute left-4 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div class="flex-grow overflow-y-auto space-y-6 pr-1 custom-scrollbar">
                <div>
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">Teams</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3.5 p-3 rounded-2xl hover:bg-gray-50/80 transition-all cursor-pointer group bg-[#C9A050]/5 border border-[#C9A050]/20">
                            <img src="https://ui-avatars.com/api/?name=Front+of+House&background=C9A050&color=fff" class="w-12 h-12 rounded-xl shadow-xs group-hover:scale-105 transition-transform shrink-0" alt="FOH">
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <h4 class="font-bold text-gray-900 truncate text-sm">Front of House</h4>
                                    <span class="text-[10px] text-gray-400 shrink-0 ml-2">2 min ago</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate font-medium">Sandra: Table 12 is ready for checkout.</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3.5 p-3 rounded-2xl hover:bg-gray-50/80 transition-all cursor-pointer group">
                            <img src="https://ui-avatars.com/api/?name=Kitchen&background=0A2E2A&color=fff" class="w-12 h-12 rounded-xl shadow-xs group-hover:scale-105 transition-transform shrink-0" alt="Kitchen">
                            <div class="flex-grow min-w-0 relative">
                                <div class="flex items-center justify-between mb-0.5">
                                    <h4 class="font-bold text-gray-900 truncate text-sm">Kitchen Staff</h4>
                                    <span class="text-[10px] text-gray-400 shrink-0 ml-2">Just Now</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate font-medium">Chef Daniel: Two medium steaks for Table 7.</p>
                                <span class="absolute right-0 bottom-0.5 w-4 h-4 bg-[#C9A050] text-white text-[9px] font-bold flex items-center justify-center rounded-full shadow-xs">8</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">Direct</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3.5 p-3 rounded-2xl hover:bg-gray-50/80 transition-all cursor-pointer group">
                            <img src="https://ui-avatars.com/api/?name=Maria+Gomez&background=FBBC05&color=fff" class="w-12 h-12 rounded-xl shadow-xs group-hover:scale-105 transition-transform shrink-0" alt="Maria">
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <h4 class="font-bold text-gray-900 truncate text-sm">Maria Gomez</h4>
                                    <span class="text-[10px] text-gray-400 shrink-0 ml-2">12 min ago</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate font-medium">Can you approve the void on Order #284?</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3.5 p-3 rounded-2xl hover:bg-gray-50/80 transition-all cursor-pointer group">
                            <img src="https://ui-avatars.com/api/?name=Daniel+Okafor&background=34A853&color=fff" class="w-12 h-12 rounded-xl shadow-xs group-hover:scale-105 transition-transform shrink-0" alt="Daniel">
                            <div class="flex-grow min-w-0 relative">
                                <div class="flex items-center justify-between mb-0.5">
                                    <h4 class="font-bold text-gray-900 truncate text-sm">Daniel Okafor</h4>
                                    <span class="text-[10px] text-gray-400 shrink-0 ml-2">20 min ago</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate font-medium">I'll handle the large party at 6 PM.</p>
                                <span class="absolute right-0 bottom-0.5 w-4 h-4 bg-[#C9A050] text-white text-[9px] font-bold flex items-center justify-center rounded-full shadow-xs">2</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="w-full lg:w-2/3 bg-white rounded-[2rem] sm:rounded-[2.5rem] flex flex-col shadow-sm border border-[#C9A050]/10 overflow-hidden min-h-[480px] lg:min-h-0">
            <!-- Chat Header -->
            <div class="p-5 sm:p-6 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center space-x-3.5 min-w-0">
                    <img src="https://ui-avatars.com/api/?name=Front+of+House&background=C9A050&color=fff" class="w-11 h-11 rounded-xl shadow-xs shrink-0" alt="FOH">
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900 text-sm sm:text-base truncate">Front of House Team</h3>
                        <p class="text-xs text-green-500 font-bold flex items-center mt-0.5">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                            4 members online
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <button class="p-2.5 bg-gray-50 rounded-xl text-gray-400 hover:text-[#C9A050] transition-colors hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 5.716V5z"></path></svg>
                    </button>
                    <button class="p-2.5 bg-gray-50 rounded-xl text-gray-400 hover:text-[#C9A050] transition-colors hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="flex-grow overflow-y-auto p-5 sm:p-6 space-y-6 custom-scrollbar bg-gray-50/50">
                <div class="flex justify-center">
                    <span class="px-3.5 py-1 bg-white rounded-full text-[10px] font-bold text-gray-400 uppercase tracking-widest shadow-xs border border-gray-100">Today, Feb 10</span>
                </div>

                <!-- Received Message -->
                <div class="flex items-start space-x-3 max-w-lg">
                    <img src="https://ui-avatars.com/api/?name=Maria+Gomez&background=FBBC05&color=fff" class="w-8 h-8 rounded-xl shrink-0 mt-1" alt="Maria">
                    <div class="space-y-1.5 min-w-0">
                        <div class="bg-white p-3.5 rounded-2xl rounded-tl-xs shadow-xs border border-[#C9A050]/10">
                            <p class="text-xs font-bold text-[#C9A050] mb-0.5">Maria</p>
                            <p class="text-xs sm:text-sm font-medium text-gray-700 leading-relaxed">Table 12 is asking for the bill, and also confirm the order please.</p>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 block ml-1">8:14 AM</span>
                    </div>
                </div>

                <!-- Sent Message -->
                <div class="flex items-start justify-end space-x-3">
                    <div class="space-y-1.5 flex flex-col items-end max-w-lg">
                        <div class="bg-[#C9A050] p-3.5 rounded-2xl rounded-tr-xs shadow-xs">
                            <p class="text-xs font-bold text-white/70 mb-0.5">You</p>
                            <p class="text-xs sm:text-sm font-medium text-white leading-relaxed">Send it through POS now.</p>
                        </div>
                        <div class="flex items-center space-x-1.5 mr-1">
                            <span class="text-[10px] font-bold text-gray-400">8:15 AM</span>
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                        </div>
                    </div>
                </div>

                <!-- Another Message -->
                <div class="flex items-start space-x-3 max-w-lg">
                    <img src="https://ui-avatars.com/api/?name=Sandra+James&background=0A2E2A&color=fff" class="w-8 h-8 rounded-xl shrink-0 mt-1" alt="Sandra">
                    <div class="space-y-1.5 min-w-0">
                        <div class="bg-white p-3.5 rounded-2xl rounded-tl-xs shadow-xs border border-[#C9A050]/10">
                            <p class="text-xs font-bold text-[#C9A050] mb-0.5">Sandra</p>
                            <p class="text-xs sm:text-sm font-medium text-gray-700 leading-relaxed">Split payment or single?</p>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 block ml-1">8:24 AM</span>
                    </div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="p-4 sm:p-5 border-t border-gray-100 bg-white">
                <div class="flex items-center space-x-2 sm:space-x-3 bg-gray-50 rounded-2xl p-1.5 pr-2 border border-gray-200">
                    <button class="p-2 text-gray-400 hover:text-[#C9A050] transition-colors shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>
                    <input type="text" placeholder="Write a message..." class="flex-grow bg-transparent border-none focus:ring-0 text-xs sm:text-sm font-medium py-2 outline-none">
                    <button class="p-2 text-gray-400 hover:text-[#C9A050] transition-colors shrink-0 hidden sm:block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                    </button>
                    <button class="p-2.5 btn-gold text-white rounded-xl shadow-xs shrink-0 min-h-[40px] min-w-[40px] flex items-center justify-center">
                        <svg class="w-4 h-4 transform rotate-90" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
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