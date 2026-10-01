<x-app-layout>
    <div class="space-y-6" x-data="{ 
        showModal: false, 
        isEditing: false, 
        modalTitle: 'Create Special Offer',
        formAction: '{{ route('offers.store') }}',
        formMethod: 'POST',
        offerData: {
            title: '',
            description: '',
            badge_text: 'SPECIAL OFFER',
            discount_percentage: 20,
            food_id: '',
            banner_image_url: '',
            is_active: true
        },
        openCreateModal() {
            this.isEditing = false;
            this.modalTitle = 'Create Special Offer';
            this.formAction = '{{ route('offers.store') }}';
            this.formMethod = 'POST';
            this.offerData = {
                title: '',
                description: '',
                badge_text: 'SPECIAL OFFER',
                discount_percentage: 20,
                food_id: '',
                banner_image_url: '',
                is_active: true
            };
            this.showModal = true;
        },
        openEditModal(offer) {
            this.isEditing = true;
            this.modalTitle = 'Edit Offer: ' + offer.title;
            this.formAction = '/offers/' + offer.id;
            this.formMethod = 'PUT';
            this.offerData = {
                title: offer.title || '',
                description: offer.description || '',
                badge_text: offer.badge_text || 'SPECIAL OFFER',
                discount_percentage: offer.discount_percentage || 0,
                food_id: offer.food_id || '',
                banner_image_url: offer.banner_image_url || '',
                is_active: !!offer.is_active
            };
            this.showModal = true;
        }
    }">

        @if (session('success'))
            <div class="flex items-center justify-between bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-2xl text-sm font-medium">
                <span>{{ session('success') }}</span>
                <button @click="window.location.reload()" class="text-green-500 hover:text-green-700">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-2xl text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#C9A050] transition-colors">Dashboard</a>
                    <svg class="w-3.5 h-3.5 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <span class="text-gray-900 font-semibold">Promotions</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Special Offers & Banners</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Manage promotional cards synced directly with the user app</p>
            </div>
            <button @click="openCreateModal()" class="px-5 py-2.5 btn-gold rounded-xl text-xs sm:text-sm font-semibold flex items-center space-x-2 shadow-xs shrink-0 self-start sm:self-auto min-h-[40px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add New Offer</span>
            </button>
        </div>

        <!-- Offers Table Card -->
        <div class="card overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Active Campaign Cards</h2>
                    <p class="text-xs text-gray-400">These offers display on the user app home banner</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Offer</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Badge</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Linked Dish</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($offers as $offer)
                            <tr class="hover:bg-[#0A2E2A]/[0.02] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3.5">
                                        @if ($offer->banner_image_url || ($offer->food && $offer->food->image_url))
                                            <img src="{{ $offer->banner_image_url ?: $offer->food->image_url }}" class="w-12 h-12 rounded-xl object-cover shadow-xs border border-gray-100 shrink-0" alt="">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-[#C9A050]/10 flex items-center justify-center text-[#C9A050] font-black text-base shrink-0">
                                                %
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-gray-900 text-sm truncate">{{ $offer->title }}</h4>
                                            <p class="text-xs text-gray-500 truncate max-w-xs">{{ $offer->description ?: 'No description' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg bg-[#0A2E2A] text-white">
                                        {{ $offer->badge_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-black text-[#C9A050]">
                                        {{ $offer->discount_percentage }}% OFF
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($offer->food)
                                        <span class="text-sm font-semibold text-gray-900">{{ $offer->food->name }}</span>
                                        <p class="text-[10px] text-gray-400">Rs. {{ number_format($offer->food->price, 2) }}</p>
                                    @else
                                        <span class="text-xs text-gray-400 italic">General Promo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('offers.toggle', $offer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 rounded-full text-[11px] font-bold border transition-all cursor-pointer {{ $offer->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-200' }}">
                                            {{ $offer->is_active ? 'Active' : 'Paused' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button @click='openEditModal(@json($offer))' class="p-2 text-gray-400 hover:text-[#C9A050] transition-colors rounded-lg hover:bg-gray-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <form action="{{ route('offers.destroy', $offer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this offer?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors rounded-lg hover:bg-red-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900">No promotional offers yet</h3>
                                    <p class="text-xs text-gray-500 mt-1">Create an offer card to sync with the mobile app banner</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($offers->hasPages())
                <div class="p-6 border-t border-gray-100">
                    {{ $offers->links() }}
                </div>
            @endif
        </div>

        <!-- Add / Edit Offer Modal -->
        <template x-teleport="body">
            <div x-show="showModal"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-y-auto"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">

                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs" @click="showModal = false"></div>

                <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden transform transition-all my-8"
                     x-show="showModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                    <form :action="formAction" method="POST">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="bg-[#0A2E2A] px-8 py-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-white" x-text="modalTitle"></h3>
                                <p class="text-xs text-white/50 mt-0.5">Synced live to the user mobile app</p>
                            </div>
                            <button type="button" @click="showModal = false" class="p-2 bg-white/10 text-white/70 hover:text-white rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-6 sm:p-8 space-y-4">
                            <!-- Title -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Offer Title</label>
                                <input type="text" name="title" x-model="offerData.title" required placeholder="e.g. Chicken Teriyaki Promo" class="input w-full px-4 py-3 rounded-xl text-sm">
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Description / Tagline</label>
                                <textarea name="description" x-model="offerData.description" rows="2" placeholder="e.g. Authentic Teriyaki glaze with Japanese spices" class="input w-full px-4 py-3 rounded-xl text-sm"></textarea>
                            </div>

                            <!-- Badge & Discount -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Badge Text</label>
                                    <input type="text" name="badge_text" x-model="offerData.badge_text" placeholder="SPECIAL OFFER" class="input w-full px-4 py-3 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Discount %</label>
                                    <input type="number" name="discount_percentage" x-model="offerData.discount_percentage" min="0" max="100" placeholder="20" class="input w-full px-4 py-3 rounded-xl text-sm">
                                </div>
                            </div>

                            <!-- Linked Food Item -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Linked Dish (Optional)</label>
                                <select name="food_id" x-model="offerData.food_id" class="input w-full px-4 py-3 rounded-xl text-sm">
                                    <option value="">-- No specific dish (General Promo) --</option>
                                    @foreach ($foods as $food)
                                        <option value="{{ $food->id }}">{{ $food->name }} (Rs. {{ number_format($food->price, 2) }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Banner Image URL -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Custom Image URL (Optional)</label>
                                <input type="url" name="banner_image_url" x-model="offerData.banner_image_url" placeholder="https://example.com/banner.jpg" class="input w-full px-4 py-3 rounded-xl text-sm">
                            </div>

                            <!-- Active status toggle -->
                            <div class="flex items-center space-x-3 pt-2">
                                <input type="checkbox" id="is_active" name="is_active" value="1" x-model="offerData.is_active" class="w-4 h-4 text-[#C9A050] rounded focus:ring-[#C9A050] border-gray-300">
                                <label for="is_active" class="text-sm font-semibold text-gray-700">Display this offer as active</label>
                            </div>

                            <div class="pt-4 flex space-x-3">
                                <button type="button" @click="showModal = false" class="flex-1 py-3 btn-ghost rounded-xl font-bold text-sm">Cancel</button>
                                <button type="submit" class="flex-1 py-3 btn-gold rounded-xl font-bold text-sm shadow-xs min-h-[44px]">Save Offer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
