<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <nav class="flex text-sm text-gray-500 mb-2">
                <a href="#" class="hover:text-[#C9A050]">Food & Drinks</a>
                <span class="mx-2">></span>
                <span class="text-gray-900 font-semibold">Categories</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
        </div>
        
        <div class="flex space-x-2">
            <button class="px-6 py-2 bg-white rounded-xl shadow-sm text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all border border-transparent hover:border-gray-200">
                All categories
            </button>
            <button class="px-6 py-2 bg-[#C9A050] rounded-xl shadow-md text-sm font-semibold text-white hover:bg-[#B38E46] transition-all">
                Add category +
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
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
            <a href="{{ route('category', $category['slug']) }}" class="bg-white rounded-[2rem] p-8 flex flex-col items-center justify-center text-center hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="text-6xl mb-6 transform group-hover:scale-110 transition-transform duration-300">
                    {{ $category['icon'] }}
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-[#C9A050] transition-colors">{{ $category['name'] }}</h3>
                <p class="text-gray-400 text-sm font-medium">{{ $category['items'] }} items</p>
                <div class="mt-6 w-12 h-1 bg-gray-100 rounded-full group-hover:bg-[#C9A050] transition-colors"></div>
            </a>
        @endforeach
    </div>
</x-app-layout>