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

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 min-h-[600px] lg:h-[calc(100vh-190px)]"
         x-data="{
            activeChannel: 'Front of House',
            activeAvatar: 'https://ui-avatars.com/api/?name=Front+of+House&background=C9A050&color=fff',
            activeStatus: '4 members online',
            newMessage: '',
            threads: {
                'Front of House': [
                    { sender: 'Maria Gomez', isMe: false, text: 'Table 12 is asking for the bill, and also confirm the order please.', time: '8:14 AM' },
                    { sender: 'You', isMe: true, text: 'Send it through POS now.', time: '8:15 AM' },
                    { sender: 'Sandra James', isMe: false, text: 'Split payment or single?', time: '8:24 AM' }
                ],
                'Kitchen Staff': [
                    { sender: 'Chef Daniel', isMe: false, text: 'Two Chicken Biriyani orders for Table 7 ready in 5 mins.', time: '8:30 AM' },
                    { sender: 'You', isMe: true, text: 'Driver assigned, packaging for pickup.', time: '8:31 AM' }
                ],
                'Maria Gomez': [
                    { sender: 'Maria Gomez', isMe: false, text: 'Can you approve the void on Order #284?', time: '8:10 AM' },
                    { sender: 'You', isMe: true, text: 'Approved.', time: '8:12 AM' }
                ],
                'Daniel Okafor': [
                    { sender: 'Daniel Okafor', isMe: false, text: 'I will handle the catering event delivery at 6 PM.', time: '8:05 AM' }
                ]
            },
            sendMessage() {
                const text = this.newMessage.trim();
                if (!text) return;
                const now = new Date();
                const hours = now.getHours();
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const formattedTime = (hours % 12 || 12) + ':' + minutes + ' ' + ampm;

                if (!this.threads[this.activeChannel]) {
                    this.threads[this.activeChannel] = [];
                }
                this.threads[this.activeChannel].push({
                    sender: 'You',
                    isMe: true,
                    text: text,
                    time: formattedTime
                });
                this.newMessage = '';
                this.$nextTick(() => {
                    const el = document.getElementById('chat-scroll-container');
                    if (el) el.scrollTop = el.scrollHeight;
                });
            }
         }">
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
                        <div @click="activeChannel = 'Front of House'; activeAvatar = 'https://ui-avatars.com/api/?name=Front+of+House&background=C9A050&color=fff'; activeStatus = '4 members online'"
                             :class="activeChannel === 'Front of House' ? 'bg-[#C9A050]/10 border-[#C9A050]/40' : 'hover:bg-gray-50/80 border-transparent'"
                             class="flex items-center space-x-3.5 p-3 rounded-2xl transition-all cursor-pointer group border">
                            <img src="https://ui-avatars.com/api/?name=Front+of+House&background=C9A050&color=fff" class="w-12 h-12 rounded-xl shadow-xs group-hover:scale-105 transition-transform shrink-0" alt="FOH">
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <h4 class="font-bold text-gray-900 truncate text-sm">Front of House</h4>
                                    <span class="text-[10px] text-gray-400 shrink-0 ml-2">Active</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate font-medium">Sandra: Table 12 is ready for checkout.</p>
                            </div>
                        </div>

                        <div @click="activeChannel = 'Kitchen Staff'; activeAvatar = 'https://ui-avatars.com/api/?name=Kitchen&background=0A2E2A&color=fff'; activeStatus = 'Chef Daniel & 3 cooks online'"
                             :class="activeChannel === 'Kitchen Staff' ? 'bg-[#C9A050]/10 border-[#C9A050]/40' : 'hover:bg-gray-50/80 border-transparent'"
                             class="flex items-center space-x-3.5 p-3 rounded-2xl transition-all cursor-pointer group border">
                            <img src="https://ui-avatars.com/api/?name=Kitchen&background=0A2E2A&color=fff" class="w-12 h-12 rounded-xl shadow-xs group-hover:scale-105 transition-transform shrink-0" alt="Kitchen">
                            <div class="flex-grow min-w-0 relative">
                                <div class="flex items-center justify-between mb-0.5">
                                    <h4 class="font-bold text-gray-900 truncate text-sm">Kitchen Staff</h4>
                                    <span class="text-[10px] text-gray-400 shrink-0 ml-2">Just Now</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate font-medium">Chef Daniel: Two Biriyani ready.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">Direct</h3>
                    <div class="space-y-4">
                        <div @click="activeChannel = 'Maria Gomez'; activeAvatar = 'https://ui-avatars.com/api/?name=Maria+Gomez&background=FBBC05&color=fff'; activeStatus = 'Online'"
                             :class="activeChannel === 'Maria Gomez' ? 'bg-[#C9A050]/10 border-[#C9A050]/40' : 'hover:bg-gray-50/80 border-transparent'"
                             class="flex items-center space-x-3.5 p-3 rounded-2xl transition-all cursor-pointer group border">
                            <img src="https://ui-avatars.com/api/?name=Maria+Gomez&background=FBBC05&color=fff" class="w-12 h-12 rounded-xl shadow-xs group-hover:scale-105 transition-transform shrink-0" alt="Maria">
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <h4 class="font-bold text-gray-900 truncate text-sm">Maria Gomez</h4>
                                    <span class="text-[10px] text-gray-400 shrink-0 ml-2">12m ago</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate font-medium">Can you approve the void on Order #284?</p>
                            </div>
                        </div>

                        <div @click="activeChannel = 'Daniel Okafor'; activeAvatar = 'https://ui-avatars.com/api/?name=Daniel+Okafor&background=34A853&color=fff'; activeStatus = 'On Delivery'"
                             :class="activeChannel === 'Daniel Okafor' ? 'bg-[#C9A050]/10 border-[#C9A050]/40' : 'hover:bg-gray-50/80 border-transparent'"
                             class="flex items-center space-x-3.5 p-3 rounded-2xl transition-all cursor-pointer group border">
                            <img src="https://ui-avatars.com/api/?name=Daniel+Okafor&background=34A853&color=fff" class="w-12 h-12 rounded-xl shadow-xs group-hover:scale-105 transition-transform shrink-0" alt="Daniel">
                            <div class="flex-grow min-w-0 relative">
                                <div class="flex items-center justify-between mb-0.5">
                                    <h4 class="font-bold text-gray-900 truncate text-sm">Daniel Okafor</h4>
                                    <span class="text-[10px] text-gray-400 shrink-0 ml-2">20m ago</span>
                                </div>
                                <p class="text-xs text-gray-500 truncate font-medium">I will handle the catering event.</p>
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
                    <img :src="activeAvatar" class="w-11 h-11 rounded-xl shadow-xs shrink-0" alt="">
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900 text-sm sm:text-base truncate" x-text="activeChannel"></h3>
                        <p class="text-xs text-green-500 font-bold flex items-center mt-0.5">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                            <span x-text="activeStatus"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="chat-scroll-container" class="flex-grow overflow-y-auto p-5 sm:p-6 space-y-4 custom-scrollbar bg-gray-50/50">
                <div class="flex justify-center mb-2">
                    <span class="px-3.5 py-1 bg-white rounded-full text-[10px] font-bold text-gray-400 uppercase tracking-widest shadow-xs border border-gray-100">Today</span>
                </div>

                <template x-for="(msg, idx) in (threads[activeChannel] || [])" :key="idx">
                    <div>
                        <!-- Sent by Me -->
                        <template x-if="msg.isMe">
                            <div class="flex items-start justify-end space-x-3">
                                <div class="space-y-1.5 flex flex-col items-end max-w-lg">
                                    <div class="bg-[#C9A050] p-3.5 rounded-2xl rounded-tr-xs shadow-xs text-white">
                                        <p class="text-xs font-bold text-white/70 mb-0.5">You</p>
                                        <p class="text-xs sm:text-sm font-medium leading-relaxed" x-text="msg.text"></p>
                                    </div>
                                    <div class="flex items-center space-x-1.5 mr-1">
                                        <span class="text-[10px] font-bold text-gray-400" x-text="msg.time"></span>
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Received from Team/User -->
                        <template x-if="!msg.isMe">
                            <div class="flex items-start space-x-3 max-w-lg">
                                <img :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(msg.sender) + '&background=0A2E2A&color=fff'" class="w-8 h-8 rounded-xl shrink-0 mt-1" alt="">
                                <div class="space-y-1.5 min-w-0">
                                    <div class="bg-white p-3.5 rounded-2xl rounded-tl-xs shadow-xs border border-[#C9A050]/10">
                                        <p class="text-xs font-bold text-[#C9A050] mb-0.5" x-text="msg.sender"></p>
                                        <p class="text-xs sm:text-sm font-medium text-gray-700 leading-relaxed" x-text="msg.text"></p>
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-400 block ml-1" x-text="msg.time"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Message Input -->
            <div class="p-4 sm:p-5 border-t border-gray-100 bg-white">
                <form @submit.prevent="sendMessage()" class="flex items-center space-x-2 sm:space-x-3 bg-gray-50 rounded-2xl p-1.5 pr-2 border border-gray-200">
                    <input type="text" x-model="newMessage" placeholder="Write a message..." 
                           class="flex-grow bg-transparent border-none focus:ring-0 text-xs sm:text-sm font-medium py-2 px-3 outline-none">
                    <button type="submit" class="p-2.5 bg-[#C9A050] hover:bg-[#b58f43] text-white rounded-xl shadow-xs shrink-0 min-h-[40px] min-w-[40px] flex items-center justify-center transition-all">
                        <svg class="w-4 h-4 transform rotate-90" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
                    </button>
                </form>
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