<x-settings-layout active="checkout" title="Checkout settings">
    <h2 class="text-2xl font-bold text-gray-900 mb-10">Checkout settings</h2>

    <div class="space-y-12">
        <!-- Payment History Section -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 mb-6">Payment history</h3>
            <div class="flex items-center justify-between p-6 bg-gray-50/50 rounded-3xl border border-gray-100">
                <span class="font-bold text-gray-700">Save payment history</span>
                <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out bg-[#C9A050] rounded-full cursor-pointer shadow-inner">
                    <span class="absolute right-1 top-1 w-4 h-4 transition duration-200 ease-in-out transform bg-white rounded-full shadow translate-x-0"></span>
                </div>
            </div>
            <button class="mt-4 text-sm font-bold text-blue-500 hover:text-blue-600 transition-colors">Clear history</button>
        </div>

        <!-- Payment Method Section -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 mb-6">Payment method</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-6 bg-gray-50/50 rounded-3xl border border-gray-100">
                    <span class="font-bold text-gray-700">Bank card</span>
                    <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out bg-[#C9A050] rounded-full cursor-pointer shadow-inner">
                        <span class="absolute right-1 top-1 w-4 h-4 transition duration-200 ease-in-out transform bg-white rounded-full shadow translate-x-0"></span>
                    </div>
                </div>
                <div class="flex items-center justify-between p-6 bg-gray-50/50 rounded-3xl border border-gray-100">
                    <span class="font-bold text-gray-700">Cash</span>
                    <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out bg-[#C9A050] rounded-full cursor-pointer shadow-inner">
                        <span class="absolute right-1 top-1 w-4 h-4 transition duration-200 ease-in-out transform bg-white rounded-full shadow translate-x-0"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-settings-layout>