<x-app-layout>
    <div x-data="{
        openCreateModal: false,
        openEditModal: false,
        openDeleteModal: false,
        currentFood: {
            id: '',
            name: '',
            type: 'Breakfast',
            price: '',
            description: '',
            prep_time: '15 min',
            calories: '450',
            difficulty: 'Easy',
            servings: '1 person',
            rating: '4.8',
            image_url: '',
            available_days: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        },
        deleteId: null,
        deleteName: '',
        imageMode: 'url',
        previewImage: null,

        openEdit(food) {
            this.currentFood = {
                id: food.id,
                name: food.name,
                type: food.type,
                price: food.price,
                description: food.description || '',
                prep_time: food.prep_time || '15 min',
                calories: food.calories || '450',
                difficulty: food.difficulty || 'Easy',
                servings: food.servings || '1 person',
                rating: food.rating || '4.8',
                image_url: food.image_url || '',
                available_days: Array.isArray(food.available_days) ? food.available_days : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
            };
            this.previewImage = food.image_url;
            this.imageMode = 'url';
            this.openEditModal = true;
        },

        confirmDelete(id, name) {
            this.deleteId = id;
            this.deleteName = name;
            this.openDeleteModal = true;
        },

        handleImagePreview(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewImage = URL.createObjectURL(file);
            }
        }
    }">
        <!-- Session Flash Alerts -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">✓</div>
                    <div>
                        <p class="text-sm font-bold">{{ session('success') }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 text-lg font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" 
                 class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-red-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">!</div>
                    <div>
                        <p class="text-sm font-bold">Please check the form for errors:</p>
                        <ul class="text-xs list-disc list-inside mt-1 text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700 text-lg font-bold">&times;</button>
            </div>
        @endif

        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center text-xs text-gray-500 mb-1.5 space-x-1.5">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#C9A050] transition-colors">Dashboard</a>
                    <span>/</span>
                    <span class="text-gray-900 font-semibold">Food & Menu</span>
                </nav>
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Food & Menu Management</h1>
                    <span class="px-2.5 py-1 bg-[#0A2E2A]/10 text-[#0A2E2A] text-xs font-bold rounded-lg border border-[#0A2E2A]/20">
                        {{ $totalCount }} Meals
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Full control over daily menus, dishes, pricing in Rs., and recipes</p>
            </div>

            <!-- Primary Add Button -->
            <div class="flex items-center space-x-3">
                <button @click="openCreateModal = true; previewImage = null; imageMode = 'url';" 
                        class="px-5 py-3 bg-[#C9A050] hover:bg-[#b58f43] text-white font-bold text-xs sm:text-sm rounded-2xl shadow-lg shadow-[#C9A050]/25 transition-all flex items-center space-x-2 active:scale-95">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add New Meal</span>
                </button>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 mb-8">
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-xs hover:border-[#C9A050]/40 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-gray-500">All Dishes</span>
                    <span class="w-8 h-8 rounded-xl bg-[#0A2E2A]/5 text-[#0A2E2A] flex items-center justify-center text-sm font-bold">🍽️</span>
                </div>
                <p class="text-2xl sm:text-3xl font-black text-gray-900">{{ $totalCount }}</p>
                <p class="text-[11px] text-gray-400 font-medium mt-0.5">Active in system</p>
            </div>

            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-xs hover:border-[#C9A050]/40 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-amber-700">Breakfast</span>
                    <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">🍳</span>
                </div>
                <p class="text-2xl sm:text-3xl font-black text-gray-900">{{ $breakfastCount }}</p>
                <p class="text-[11px] text-gray-400 font-medium mt-0.5">Morning menus</p>
            </div>

            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-xs hover:border-[#C9A050]/40 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-orange-700">Lunch</span>
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-sm font-bold">☀️</span>
                </div>
                <p class="text-2xl sm:text-3xl font-black text-gray-900">{{ $lunchCount }}</p>
                <p class="text-[11px] text-gray-400 font-medium mt-0.5">Mid-day specials</p>
            </div>

            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-xs hover:border-[#C9A050]/40 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-indigo-700">Dinner</span>
                    <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">🌙</span>
                </div>
                <p class="text-2xl sm:text-3xl font-black text-gray-900">{{ $dinnerCount }}</p>
                <p class="text-[11px] text-gray-400 font-medium mt-0.5">Evening dishes</p>
            </div>

            <div class="col-span-2 lg:col-span-1 bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-xs hover:border-[#C9A050]/40 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-[#C9A050]">Avg. Price</span>
                    <span class="w-8 h-8 rounded-xl bg-[#C9A050]/10 text-[#C9A050] flex items-center justify-center text-sm font-bold">Rs</span>
                </div>
                <p class="text-2xl sm:text-3xl font-black text-gray-900">Rs. {{ number_format($avgPrice, 0) }}</p>
                <p class="text-[11px] text-gray-400 font-medium mt-0.5">Across all items</p>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-gray-100 shadow-xs mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Meal Type Tabs -->
            <div class="flex items-center space-x-1.5 overflow-x-auto pb-1 md:pb-0 custom-scrollbar">
                @php
                    $tabs = [
                        ['id' => 'all', 'label' => 'All Dishes', 'count' => $totalCount, 'icon' => '🍽️'],
                        ['id' => 'Breakfast', 'label' => 'Breakfast', 'count' => $breakfastCount, 'icon' => '🍳'],
                        ['id' => 'Lunch', 'label' => 'Lunch', 'count' => $lunchCount, 'icon' => '☀️'],
                        ['id' => 'Dinner', 'label' => 'Dinner', 'count' => $dinnerCount, 'icon' => '🌙'],
                    ];
                @endphp

                @foreach($tabs as $tab)
                    @php $isActive = strtolower($activeType) === strtolower($tab['id']); @endphp
                    <a href="{{ route('foods.index', array_merge(request()->query(), ['type' => $tab['id'], 'page' => 1])) }}"
                       class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all flex items-center space-x-2 whitespace-nowrap {{ $isActive ? 'bg-[#0A2E2A] text-white shadow-md' : 'text-gray-600 hover:bg-gray-100/80 bg-gray-50' }}">
                        <span>{{ $tab['icon'] }}</span>
                        <span>{{ $tab['label'] }}</span>
                        <span class="px-1.5 py-0.5 text-[10px] rounded-md font-bold {{ $isActive ? 'bg-[#C9A050] text-white' : 'bg-gray-200 text-gray-700' }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Search & Sort Controls -->
            <form method="GET" action="{{ route('foods.index') }}" class="flex items-center space-x-2 w-full md:w-auto">
                <input type="hidden" name="type" value="{{ $activeType }}">
                
                <div class="relative flex-1 md:w-64">
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Search meals..." 
                           class="w-full pl-9 pr-8 py-2.5 bg-gray-50 hover:bg-gray-100/80 focus:bg-white rounded-2xl border border-gray-200 focus:border-[#C9A050] text-xs sm:text-sm font-medium transition-all focus:ring-0">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    @if($search)
                        <a href="{{ route('foods.index', ['type' => $activeType]) }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 font-bold">&times;</a>
                    @endif
                </div>

                <select name="sort" onchange="this.form.submit()" class="py-2.5 px-3 bg-gray-50 rounded-2xl border border-gray-200 text-xs sm:text-sm font-semibold text-gray-700 focus:border-[#C9A050] transition-all">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>
        </div>

        <!-- Meals Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($foods as $food)
                <div class="bg-white rounded-[2rem] p-5 border border-gray-100 shadow-xs hover:shadow-xl hover:border-[#C9A050]/40 transition-all flex flex-col group justify-between">
                    <div>
                        <!-- Dish Image with Overlay Badges -->
                        <div class="relative w-full h-44 rounded-2xl overflow-hidden mb-4 bg-gray-100 shadow-xs">
                            <img src="{{ $food->image_url }}" 
                                 alt="{{ $food->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80'">

                            <!-- Meal Type Badge -->
                            <div class="absolute top-3 left-3">
                                @php
                                    $badgeStyle = match($food->type) {
                                        'Breakfast' => 'bg-amber-500/90 text-white',
                                        'Lunch' => 'bg-orange-500/90 text-white',
                                        'Dinner' => 'bg-indigo-600/90 text-white',
                                        default => 'bg-[#0A2E2A]/90 text-white',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-[11px] font-extrabold rounded-lg backdrop-blur-xs shadow-sm uppercase tracking-wider {{ $badgeStyle }}">
                                    {{ $food->type }}
                                </span>
                            </div>

                            <!-- Rating Badge -->
                            <div class="absolute top-3 right-3 px-2 py-0.5 bg-black/60 backdrop-blur-xs rounded-lg text-white text-[11px] font-bold flex items-center space-x-1 shadow-sm">
                                <span class="text-amber-400">★</span>
                                <span>{{ number_format($food->rating ?: 4.8, 1) }}</span>
                            </div>
                        </div>

                        <!-- Dish Info -->
                        <h3 class="text-base font-extrabold text-gray-900 group-hover:text-[#C9A050] transition-colors line-clamp-1 mb-1" title="{{ $food->name }}">
                            {{ $food->name }}
                        </h3>

                        <p class="text-gray-500 text-xs line-clamp-2 mb-3 leading-relaxed">
                            {{ $food->description ?: 'Freshly prepared traditional specialty meal made with authentic local Ceylon spices and premium ingredients.' }}
                        </p>

                        <!-- Cooking / Health Metadata -->
                        <div class="flex items-center space-x-3 text-[11px] font-semibold text-gray-500 mb-3 pb-3 border-b border-gray-100">
                            <span class="flex items-center space-x-1">
                                <span>⏱️</span>
                                <span>{{ $food->prep_time ?: '15 min' }}</span>
                            </span>
                            <span>•</span>
                            <span class="flex items-center space-x-1">
                                <span>🔥</span>
                                <span>{{ $food->calories ?: '450' }} kcal</span>
                            </span>
                            <span>•</span>
                            <span class="flex items-center space-x-1">
                                <span>👥</span>
                                <span>{{ $food->servings ?: '1 person' }}</span>
                            </span>
                        </div>

                        <!-- Available Days Mini Pills -->
                        <div class="flex items-center flex-wrap gap-1 mb-4">
                            @php
                                $days = is_array($food->available_days) ? $food->available_days : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                            @endphp
                            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $d)
                                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded-md {{ in_array($d, $days) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-400' }}">
                                    {{ $d }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bottom Price & Actions Bar -->
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 block leading-tight">Price</span>
                            <span class="text-lg font-black text-[#C9A050]">Rs. {{ number_format($food->price, 2) }}</span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <!-- Edit Button -->
                            <button @click="openEdit({{ json_encode($food) }})" 
                                    class="p-2 text-gray-500 hover:text-white hover:bg-[#C9A050] bg-gray-50 rounded-xl transition-all border border-gray-200 hover:border-[#C9A050]" 
                                    title="Edit Meal">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>

                            <!-- Delete Button -->
                            <button @click="confirmDelete({{ $food->id }}, '{{ addslashes($food->name) }}')" 
                                    class="p-2 text-red-500 hover:text-white hover:bg-red-500 bg-red-50 rounded-xl transition-all border border-red-200 hover:border-red-500" 
                                    title="Delete Meal">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-gray-100">
                    <div class="text-6xl mb-4">🍽️</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No meals found</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">No dishes match your selected filter or search term. Try resetting your search or add a new meal.</p>
                    <a href="{{ route('foods.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl font-bold text-xs text-gray-700 transition-all">
                        Reset Filters
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $foods->links() }}
        </div>

        <!-- ========================================== -->
        <!-- ADD NEW MEAL MODAL -->
        <!-- ========================================== -->
        <div x-show="openCreateModal" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden my-8 border border-gray-100"
                 @click.away="openCreateModal = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="p-6 sm:p-8">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-2xl bg-[#C9A050]/15 text-[#C9A050] flex items-center justify-center font-bold text-xl">
                                🍳
                            </div>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900">Add New Dish</h2>
                                <p class="text-xs text-gray-400">Add a delicious meal item to The Menu catalog</p>
                            </div>
                        </div>
                        <button @click="openCreateModal = false" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Add Form -->
                    <form method="POST" action="{{ route('foods.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <!-- Meal Name & Type -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Meal / Dish Name *</label>
                                <input type="text" name="name" required placeholder="e.g. Special Chicken Biriyani" 
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-semibold text-gray-800 transition-all focus:ring-0">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Meal Type *</label>
                                <select name="type" required 
                                        class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-semibold text-gray-800 transition-all focus:ring-0">
                                    <option value="Breakfast">Breakfast 🍳</option>
                                    <option value="Lunch" selected>Lunch ☀️</option>
                                    <option value="Dinner">Dinner 🌙</option>
                                </select>
                            </div>
                        </div>

                        <!-- Price & Calories & Prep Time -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Price (Rs.) *</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-3 text-xs font-bold text-gray-400">Rs.</span>
                                    <input type="number" step="0.01" min="0" name="price" required placeholder="750.00" 
                                           class="w-full pl-10 pr-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-bold text-[#C9A050] transition-all focus:ring-0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Prep Time</label>
                                <input type="text" name="prep_time" value="15 min" placeholder="e.g. 20 min" 
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-medium text-gray-800 transition-all focus:ring-0">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Calories (kcal)</label>
                                <input type="number" min="0" name="calories" value="550" placeholder="e.g. 550" 
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-medium text-gray-800 transition-all focus:ring-0">
                            </div>
                        </div>

                        <!-- Servings & Difficulty & Rating -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Servings</label>
                                <input type="text" name="servings" value="1 person" placeholder="e.g. 1-2 persons" 
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-medium text-gray-800 transition-all focus:ring-0">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Difficulty</label>
                                <select name="difficulty" class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-medium text-gray-800 transition-all focus:ring-0">
                                    <option value="Easy">Easy</option>
                                    <option value="Medium" selected>Medium</option>
                                    <option value="Chef Special">Chef Special</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Initial Rating</label>
                                <input type="number" step="0.1" min="1" max="5" name="rating" value="4.8" 
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-medium text-gray-800 transition-all focus:ring-0">
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Description</label>
                            <textarea name="description" rows="3" placeholder="Describe the ingredients, taste, spices and specialty of this dish..." 
                                      class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-xs sm:text-sm font-medium text-gray-800 transition-all focus:ring-0"></textarea>
                        </div>

                        <!-- Available Days Checkboxes -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Available Days</label>
                            <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
                                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                    <label class="flex items-center justify-center p-2 rounded-xl border border-gray-200 hover:border-[#C9A050] bg-gray-50/80 cursor-pointer text-xs font-bold text-gray-700 select-none">
                                        <input type="checkbox" name="available_days[]" value="{{ $day }}" checked class="mr-1.5 rounded text-[#C9A050] focus:ring-0">
                                        {{ $day }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Image Upload / URL Mode -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-gray-700">Dish Photo</label>
                                <div class="flex items-center space-x-2 text-xs">
                                    <button type="button" @click="imageMode = 'url'" :class="imageMode === 'url' ? 'text-[#C9A050] font-bold underline' : 'text-gray-400'">Image URL</button>
                                    <span>|</span>
                                    <button type="button" @click="imageMode = 'file'" :class="imageMode === 'file' ? 'text-[#C9A050] font-bold underline' : 'text-gray-400'">Upload File</button>
                                </div>
                            </div>

                            <div x-show="imageMode === 'url'">
                                <input type="url" name="image_url" placeholder="https://images.unsplash.com/..." 
                                       @input="previewImage = $event.target.value"
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-xs sm:text-sm font-medium text-gray-800 transition-all focus:ring-0">
                            </div>

                            <div x-show="imageMode === 'file'">
                                <input type="file" name="image" accept="image/*" @change="handleImagePreview($event)" 
                                       class="w-full px-3 py-2 bg-gray-50 rounded-2xl border border-gray-200 text-xs font-medium text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0A2E2A] file:text-white hover:file:bg-[#0A2E2A]/90">
                            </div>

                            <!-- Live Image Preview -->
                            <template x-if="previewImage">
                                <div class="mt-3 flex items-center space-x-3 p-2 bg-gray-50 rounded-2xl border border-gray-100">
                                    <img :src="previewImage" class="w-16 h-16 object-cover rounded-xl shadow-xs" alt="Preview">
                                    <p class="text-[11px] text-gray-500 font-medium">Image preview looks great!</p>
                                </div>
                            </template>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                            <button type="button" @click="openCreateModal = false" class="px-5 py-3 rounded-2xl border border-gray-200 text-gray-600 font-bold text-xs hover:bg-gray-50 transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-3 bg-[#C9A050] hover:bg-[#b58f43] text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg shadow-[#C9A050]/20 transition-all">
                                Save & Publish Meal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- EDIT MEAL MODAL -->
        <!-- ========================================== -->
        <div x-show="openEditModal" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden my-8 border border-gray-100"
                 @click.away="openEditModal = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="p-6 sm:p-8">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-2xl bg-[#0A2E2A]/10 text-[#0A2E2A] flex items-center justify-center font-bold text-xl">
                                ✏️
                            </div>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900">Edit Dish Details</h2>
                                <p class="text-xs text-gray-400">Update meal attributes, pricing, or ingredients</p>
                            </div>
                        </div>
                        <button @click="openEditModal = false" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Edit Form -->
                    <form method="POST" :action="'/foods/' + currentFood.id" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Meal Name & Type -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Meal / Dish Name *</label>
                                <input type="text" name="name" x-model="currentFood.name" required 
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-semibold text-gray-800 transition-all focus:ring-0">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Meal Type *</label>
                                <select name="type" x-model="currentFood.type" required 
                                        class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-semibold text-gray-800 transition-all focus:ring-0">
                                    <option value="Breakfast">Breakfast 🍳</option>
                                    <option value="Lunch">Lunch ☀️</option>
                                    <option value="Dinner">Dinner 🌙</option>
                                </select>
                            </div>
                        </div>

                        <!-- Price & Calories & Prep Time -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Price (Rs.) *</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-3 text-xs font-bold text-gray-400">Rs.</span>
                                    <input type="number" step="0.01" min="0" name="price" x-model="currentFood.price" required 
                                           class="w-full pl-10 pr-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-bold text-[#C9A050] transition-all focus:ring-0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Prep Time</label>
                                <input type="text" name="prep_time" x-model="currentFood.prep_time" 
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-medium text-gray-800 transition-all focus:ring-0">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Calories (kcal)</label>
                                <input type="number" min="0" name="calories" x-model="currentFood.calories" 
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-medium text-gray-800 transition-all focus:ring-0">
                            </div>
                        </div>

                        <!-- Servings & Difficulty & Rating -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Servings</label>
                                <input type="text" name="servings" x-model="currentFood.servings" 
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-medium text-gray-800 transition-all focus:ring-0">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Difficulty</label>
                                <select name="difficulty" x-model="currentFood.difficulty" class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-medium text-gray-800 transition-all focus:ring-0">
                                    <option value="Easy">Easy</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Chef Special">Chef Special</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Rating (1 - 5)</label>
                                <input type="number" step="0.1" min="1" max="5" name="rating" x-model="currentFood.rating" 
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm font-medium text-gray-800 transition-all focus:ring-0">
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Description</label>
                            <textarea name="description" x-model="currentFood.description" rows="3" 
                                      class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-xs sm:text-sm font-medium text-gray-800 transition-all focus:ring-0"></textarea>
                        </div>

                        <!-- Available Days Checkboxes -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Available Days</label>
                            <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
                                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                    <label class="flex items-center justify-center p-2 rounded-xl border border-gray-200 hover:border-[#C9A050] bg-gray-50/80 cursor-pointer text-xs font-bold text-gray-700 select-none">
                                        <input type="checkbox" name="available_days[]" value="{{ $day }}" 
                                               :checked="currentFood.available_days && currentFood.available_days.includes('{{ $day }}')" 
                                               class="mr-1.5 rounded text-[#C9A050] focus:ring-0">
                                        {{ $day }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Image Upload / URL Mode -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-gray-700">Dish Photo</label>
                                <div class="flex items-center space-x-2 text-xs">
                                    <button type="button" @click="imageMode = 'url'" :class="imageMode === 'url' ? 'text-[#C9A050] font-bold underline' : 'text-gray-400'">Image URL</button>
                                    <span>|</span>
                                    <button type="button" @click="imageMode = 'file'" :class="imageMode === 'file' ? 'text-[#C9A050] font-bold underline' : 'text-gray-400'">Upload File</button>
                                </div>
                            </div>

                            <div x-show="imageMode === 'url'">
                                <input type="url" name="image_url" x-model="currentFood.image_url" 
                                       @input="previewImage = $event.target.value"
                                       class="w-full px-4 py-3 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-xs sm:text-sm font-medium text-gray-800 transition-all focus:ring-0">
                            </div>

                            <div x-show="imageMode === 'file'">
                                <input type="file" name="image" accept="image/*" @change="handleImagePreview($event)" 
                                       class="w-full px-3 py-2 bg-gray-50 rounded-2xl border border-gray-200 text-xs font-medium text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0A2E2A] file:text-white hover:file:bg-[#0A2E2A]/90">
                            </div>

                            <!-- Live Image Preview -->
                            <template x-if="previewImage">
                                <div class="mt-3 flex items-center space-x-3 p-2 bg-gray-50 rounded-2xl border border-gray-100">
                                    <img :src="previewImage" class="w-16 h-16 object-cover rounded-xl shadow-xs" alt="Preview">
                                    <p class="text-[11px] text-gray-500 font-medium">Selected image preview</p>
                                </div>
                            </template>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                            <button type="button" @click="openEditModal = false" class="px-5 py-3 rounded-2xl border border-gray-200 text-gray-600 font-bold text-xs hover:bg-gray-50 transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-3 bg-[#0A2E2A] hover:bg-[#12423d] text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg transition-all">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- DELETE CONFIRMATION MODAL -->
        <!-- ========================================== -->
        <div x-show="openDeleteModal" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden p-6 sm:p-7 border border-gray-100"
                 @click.away="openDeleteModal = false">
                <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-xl font-bold mb-4">
                    ⚠️
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-1">Delete Meal?</h3>
                <p class="text-xs sm:text-sm text-gray-500 mb-6">
                    Are you sure you want to remove <strong class="text-gray-800" x-text="deleteName"></strong> from the menu? This will permanently delete this dish and its ratings.
                </p>

                <form method="POST" :action="'/foods/' + deleteId" class="flex items-center justify-end space-x-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDeleteModal = false" class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-md shadow-red-500/20">
                        Yes, Delete Meal
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
