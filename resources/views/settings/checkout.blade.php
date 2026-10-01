<x-settings-layout active="checkout" title="Checkout settings">
    <div x-data="{
        saveHistory: true,
        bankCard: true,
        cash: true,
        deliveryFee: '150.00',
        minOrder: '500.00',
        savedNotice: false,
        saveSettings() {
            this.savedNotice = true;
            setTimeout(() => this.savedNotice = false, 4000);
        }
    }">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Checkout Settings</h2>
                <p class="text-xs text-gray-500 mt-1">Configure checkout preferences, payment gateways, and delivery thresholds</p>
            </div>
            
            <button @click="saveSettings()" class="px-5 py-2.5 bg-[#C9A050] hover:bg-[#b58f43] text-white rounded-xl font-bold text-xs sm:text-sm shadow-md shadow-[#C9A050]/20 transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Save Preferences</span>
            </button>
        </div>

        <!-- Success Toast -->
        <div x-show="savedNotice" x-cloak 
             class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-bold flex items-center space-x-2 shadow-xs transition-all">
            <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs">✓</span>
            <span>Checkout preferences saved successfully!</span>
        </div>

        <div class="space-y-8">
            <!-- Payment History Section -->
            <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-gray-100 shadow-xs">
                <h3 class="text-base font-bold text-gray-900 mb-2">Order & Payment Archiving</h3>
                <p class="text-xs text-gray-500 mb-6">Automatically preserve historical checkout invoices and customer receipts</p>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <div>
                        <span class="font-bold text-gray-800 text-sm block">Save Customer Transaction History</span>
                        <span class="text-xs text-gray-400">Allows users and staff to review past itemized bills</span>
                    </div>
                    <button type="button" @click="saveHistory = !saveHistory" 
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden"
                            :class="saveHistory ? 'bg-[#C9A050]' : 'bg-gray-200'">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                              :class="saveHistory ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                </div>
            </div>

            <!-- Payment Method Section -->
            <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-gray-100 shadow-xs">
                <h3 class="text-base font-bold text-gray-900 mb-2">Enabled Payment Methods</h3>
                <p class="text-xs text-gray-500 mb-6">Select which payment options are accepted during mobile and web checkout</p>
                
                <div class="space-y-4">
                    <!-- Bank Card / Online -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">💳</span>
                            <div>
                                <span class="font-bold text-gray-800 text-sm block">Credit / Debit Card (Online Gateway)</span>
                                <span class="text-xs text-gray-400">Accept Visa, Mastercard, and digital payments</span>
                            </div>
                        </div>
                        <button type="button" @click="bankCard = !bankCard" 
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden"
                                :class="bankCard ? 'bg-[#C9A050]' : 'bg-gray-200'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                  :class="bankCard ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                    </div>

                    <!-- Cash on Delivery -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">💵</span>
                            <div>
                                <span class="font-bold text-gray-800 text-sm block">Cash on Delivery (COD)</span>
                                <span class="text-xs text-gray-400">Customer pays upon physical meal arrival</span>
                            </div>
                        </div>
                        <button type="button" @click="cash = !cash" 
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden"
                                :class="cash ? 'bg-[#C9A050]' : 'bg-gray-200'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                  :class="cash ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Delivery & Pricing Thresholds -->
            <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-gray-100 shadow-xs">
                <h3 class="text-base font-bold text-gray-900 mb-2">Delivery & Order Thresholds</h3>
                <p class="text-xs text-gray-500 mb-6">Default delivery surcharge and minimum order amount</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Standard Delivery Fee (Rs.)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-xs font-bold text-gray-400">Rs.</span>
                            <input type="number" step="10" x-model="deliveryFee" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-bold text-gray-800 focus:ring-0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Minimum Order Amount (Rs.)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3 text-xs font-bold text-gray-400">Rs.</span>
                            <input type="number" step="50" x-model="minOrder" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-bold text-gray-800 focus:ring-0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-settings-layout>