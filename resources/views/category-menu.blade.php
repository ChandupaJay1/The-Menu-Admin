<x-app-layout>
    <div x-data="{ openModal: false, selectedItem: null }">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <nav class="flex text-sm text-gray-500 mb-2">
                    <a href="{{ route('categories') }}" class="hover:text-[#C9A050]">Categories</a>
                    <span class="mx-2">></span>
                    <span class="text-gray-900 font-semibold capitalize">{{ $slug }}</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900 capitalize">{{ $slug }}</h1>
            </div>
            
            <div class="flex space-x-2">
                <button class="p-2 bg-white rounded-xl shadow-sm text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
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
                    class="bg-white rounded-[2rem] p-6 flex flex-col items-center text-center cursor-pointer hover:shadow-xl hover:-translate-y-1 transition-all group"
                >
                    <div class="text-6xl mb-6 transform group-hover:scale-110 transition-transform duration-300">
                        {{ $item['image'] }}
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-[#C9A050] transition-colors">{{ $item['name'] }}</h3>
                    <p class="text-gray-400 text-sm mb-4">{{ $item['weight'] }}</p>
                    <p class="text-2xl font-bold text-[#C9A050]">${{ number_format($item['price'], 2) }}</p>
                </div>
            @endforeach
        </div>

        <!-- Add to Order Modal -->
        <div 
            x-show="openModal" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.away="openModal = false"
            style="display: none;"
        >
            <div 
                class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            >
                <div class="p-8">
                    <div class="flex flex-col items-center text-center mb-8">
                        <div class="text-8xl mb-6" x-text="selectedItem?.image"></div>
                        <h2 class="text-2xl font-bold text-gray-900" x-text="selectedItem?.name"></h2>
                        <p class="text-gray-400" x-text="selectedItem?.weight"></p>
                        <p class="text-3xl font-bold text-[#C9A050] mt-4" x-text="'$' + parseFloat(selectedItem?.price).toFixed(2)"></p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl">🍝</span>
                                <span class="font-bold">Italian Pasta</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <button class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-400 hover:text-gray-600">-</button>
                                <span class="font-bold">1x</span>
                                <button class="w-8 h-8 flex items-center justify-center bg-[#C9A050] rounded-lg shadow-sm text-white hover:bg-[#B38E46]">+</button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl">🥪</span>
                                <span class="font-bold">Sandwich</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <button class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-400 hover:text-gray-600">-</button>
                                <span class="font-bold">1x</span>
                                <button class="w-8 h-8 flex items-center justify-center bg-[#C9A050] rounded-lg shadow-sm text-white hover:bg-[#B38E46]">+</button>
                            </div>
                        </div>
                    </div>

                    <button @click="openModal = false" class="w-full mt-8 py-4 bg-[#C9A050] text-white rounded-2xl font-bold text-lg hover:bg-[#B38E46] transition-all shadow-lg shadow-[#C9A050]/20">
                        Add to Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>