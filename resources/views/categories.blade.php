<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center text-sm text-gray-500 mb-2">
                <a href="#" class="hover:text-[#C9A050] transition-colors">Food & Drinks</a>
                <svg class="w-3.5 h-3.5 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-900 font-semibold">Categories</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Categories</h1>
        </div>
        
        <div class="flex items-center space-x-2">
            <button class="px-5 py-2.5 bg-white rounded-xl shadow-xs text-xs sm:text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all border border-gray-200 hover:-translate-y-0.5">
                All categories
            </button>
            <button class="px-5 py-2.5 btn-gold rounded-xl text-xs sm:text-sm font-semibold shadow-xs min-h-[40px]">
                Add category +
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @php
            $categories = [
                ['name' => 'Seafood', 'icon' => '🦞', 'items' => 12, 'slug' => 'seafood'],
                ['name' => 'Burger', 'icon' => '🍔', 'items' => 24, 'slug' => 'burger'],
                ['name' => 'Pizza', 'icon' => '🍕', 'items' => 18, 'slug' => 'pizza'],
                ['name' => 'Pasta', 'icon' => '🍝', 'items' => 15, 'slug' => 'pasta'],
                ['name' => 'Desserts', 'icon' => '🍰', 'items' => 10, 'slug' => 'desserts'],
                ['name' => 'Drinks', 'icon' => '🥤', 'items' => 30, 'slug' => 'drinks'],
                ['name' => 'Salad', 'icon' => '🥗', 'items' => 8, 'slug' => 'salad'],
                ['name' => 'Sushi', 'icon' => '🍣', 'items' => 20, 'slug' => 'sushi'],
            ];
        @endphp

        @foreach($categories as $category)
            <a href="{{ route('category', $category['slug']) }}" class="bg-white rounded-[2rem] p-6 sm:p-8 flex flex-col items-center justify-center text-center shadow-xs border border-gray-100 hover:border-[#C9A050]/30 hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="text-5xl sm:text-6xl mb-5 transform group-hover:scale-110 transition-transform duration-300">
                    {{ $category['icon'] }}
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-1 group-hover:text-[#C9A050] transition-colors">{{ $category['name'] }}</h3>
                <p class="text-gray-400 text-xs sm:text-sm font-medium">{{ $category['items'] }} items</p>
                <div class="mt-5 w-10 h-1 bg-gray-100 rounded-full group-hover:bg-[#C9A050] transition-colors"></div>
            </a>
        @endforeach
    </div>
</x-app-layout>