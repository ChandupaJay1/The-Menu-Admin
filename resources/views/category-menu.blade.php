<x-app-layout>
    <div x-data="{ openModal: false, selectedItem: null }">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('categories') }}" class="hover:text-[#C9A050] transition-colors">Categories</a>
                    <svg class="w-3.5 h-3.5 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <span class="text-gray-900 font-semibold capitalize">{{ $slug }}</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 capitalize tracking-tight">{{ $slug }} Menu</h1>
            </div>
            
            <div class="flex items-center space-x-2">
                <button class="p-2.5 bg-white rounded-2xl shadow-xs border border-gray-100 text-gray-400 hover:text-gray-600 hover:-translate-y-0.5 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @php
                $items = [
                    ['name' => 'Cheeseburger', 'price' => 9.60, 'weight' => '170g', 'image' => '🍔'],
                    ['name' => 'Classic Burger', 'price' => 8.90, 'weight' => '200g', 'image' => '🍔'],
                    ['name' => 'BBQ Bacon', 'price' => 10.50, 'weight' => '250g', 'image' => '🍔'],
                    ['name' => 'Double Beef', 'price' => 12.90, 'weight' => '190g', 'image' => '🍔'],
                    ['name' => 'Chicken Crispy', 'price' => 9.80, 'weight' => '200g', 'image' => '🍔'],
                    ['name' => 'Swiss Melt', 'price' => 10.20, 'weight' => '190g', 'image' => '🍔'],
                ];
            @endphp

            @foreach($items as $item)
                <div 
                    @click="selectedItem = {{ json_encode($item) }}; openModal = true"
                    class="bg-white rounded-[2rem] p-6 flex flex-col items-center text-center cursor-pointer hover:shadow-xl hover:-translate-y-1 transition-all group border border-gray-100 hover:border-[#C9A050]/30"
                >
                    <div class="text-5xl sm:text-6xl mb-5 transform group-hover:scale-110 transition-transform duration-300">
                        {{ $item['image'] }}
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1 group-hover:text-[#C9A050] transition-colors">{{ $item['name'] }}</h3>
                    <p class="text-gray-400 text-xs sm:text-sm mb-4 font-medium">{{ $item['weight'] }}</p>
                    <p class="text-xl sm:text-2xl font-black text-[#C9A050]">Rs. {{ number_format($item['price'], 2) }}</p>
                </div>
            @endforeach
        </div>

        <!-- Add to Order Modal -->
        <template x-teleport="body">
            <div 
                x-show="openModal" 
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs overflow-y-auto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                style="display: none;"
            >
                <div 
                    class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all my-8"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    @click.away="openModal = false"
                >
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col items-center text-center mb-6">
                            <div class="text-7xl mb-4" x-text="selectedItem?.image"></div>
                            <h2 class="text-2xl font-bold text-gray-900" x-text="selectedItem?.name"></h2>
                            <p class="text-gray-400 text-sm font-medium" x-text="selectedItem?.weight"></p>
                            <p class="text-3xl font-black text-[#C9A050] mt-3" x-text="'Rs. ' + parseFloat(selectedItem?.price || 0).toFixed(2)"></p>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-4 bg-gray-50/80 rounded-2xl border border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <span class="text-2xl">🍝</span>
                                    <span class="text-sm font-bold text-gray-900">Italian Pasta</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-xs text-gray-400 hover:text-gray-700 transition-colors">-</button>
                                    <span class="text-sm font-bold text-gray-900">1x</span>
                                    <button class="w-8 h-8 flex items-center justify-center btn-gold rounded-lg shadow-xs text-white">+</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50/80 rounded-2xl border border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <span class="text-2xl">🥪</span>
                                    <span class="text-sm font-bold text-gray-900">Sandwich</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-xs text-gray-400 hover:text-gray-700 transition-colors">-</button>
                                    <span class="text-sm font-bold text-gray-900">1x</span>
                                    <button class="w-8 h-8 flex items-center justify-center btn-gold rounded-lg shadow-xs text-white">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row gap-3">
                            <button @click="openModal = false" class="sm:flex-1 py-3.5 btn-ghost rounded-2xl font-bold text-sm">
                                Cancel
                            </button>
                            <button @click="openModal = false" class="sm:flex-2 py-3.5 btn-gold rounded-2xl font-bold text-base shadow-lg shadow-[#C9A050]/20 min-h-[44px]">
                                Add to Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>