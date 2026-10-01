<x-app-layout>
    <div class="space-y-6" x-data="{ 
        showModal: false,
        isEditing: false,
        userData: {},
        formAction: '{{ route('users.store') }}',
        formMethod: 'POST',
        openCreateModal() {
            this.isEditing = false;
            this.userData = { name: '', email: '', password: '', password_confirmation: '' };
            this.formAction = '{{ route('users.store') }}';
            this.formMethod = 'POST';
            this.showModal = true;
        },
        openEditModal(user) {
            this.isEditing = true;
            this.userData = { ...user, password: '', password_confirmation: '' };
            this.formAction = '/users/' + user.id;
            this.formMethod = 'PUT';
            this.showModal = true;
        }
    }">

        @if (session('success'))
            <div class="flex items-center justify-between bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-2xl text-sm font-medium shadow-sm">
                <span>{{ session('success') }}</span>
                <button @click="window.location.reload()" class="text-green-500 hover:text-green-700 font-bold text-lg leading-none">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex flex-col bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-2xl text-sm font-medium shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold">Please fix the following errors:</span>
                    <button @click="window.location.reload()" class="text-red-500 hover:text-red-700 font-bold text-lg leading-none">&times;</button>
                </div>
                <ul class="list-disc list-inside text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Users Management</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage your team members and permissions</p>
            </div>
            <button @click="openCreateModal()" class="px-5 py-2.5 bg-[#C9A050] text-white rounded-xl font-bold hover:bg-[#B38E46] transition-all shadow-lg shadow-[#C9A050]/20 flex items-center space-x-2 shrink-0 self-start sm:self-auto min-h-[40px] text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Add New User</span>
            </button>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-sm font-bold text-gray-900">Name</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-900">Email</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-900">Created At</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=C9A050&color=fff"
                                            class="w-10 h-10 rounded-xl" alt="{{ $user->name }}">
                                        <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-gray-500 text-sm">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button @click="openEditModal({{ json_encode($user) }})" class="p-2 text-gray-400 hover:text-[#C9A050] transition-colors rounded-lg hover:bg-gray-50">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </button>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors rounded-lg hover:bg-red-50">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <p class="font-medium">No users found</p>
                                        <p class="text-sm mt-1">Get started by adding a new user</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <template x-teleport="body">
            <div x-show="showModal"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">

                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showModal = false"></div>

                <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden transform transition-all"
                     x-show="showModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                    <div class="bg-[#0A2E2A] px-8 py-6 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/10 p-2.5 rounded-xl">
                                <svg class="w-6 h-6 text-[#C9A050]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-white" x-text="isEditing ? 'Edit User' : 'Add New User'"></h3>
                        </div>
                        <button @click="showModal = false" class="p-2 bg-white/10 text-white/70 hover:text-white rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form method="POST" :action="formAction" class="p-8 space-y-5">
                        @csrf
                        <template x-if="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" name="name" x-model="userData.name" required
                                class="w-full px-5 py-3.5 input @error('name') border-red-300 ring-1 ring-red-200 @enderror"
                                placeholder="Enter full name">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                            <input type="email" name="email" x-model="userData.email" required
                                class="w-full px-5 py-3.5 input @error('email') border-red-300 ring-1 ring-red-200 @enderror"
                                placeholder="Enter email address">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                            <input type="password" name="password" x-model="userData.password" :required="!isEditing"
                                class="w-full px-5 py-3.5 input @error('password') border-red-300 ring-1 ring-red-200 @enderror"
                                placeholder="Enter password (min 8 characters)">
                            <template x-if="isEditing">
                                <p class="mt-1.5 text-xs text-gray-500">Leave blank to keep the current password.</p>
                            </template>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                            <input type="password" name="password_confirmation" x-model="userData.password_confirmation" :required="!isEditing"
                                class="w-full px-5 py-3.5 input"
                                placeholder="Confirm password">
                        </div>

                        <div class="flex space-x-4 pt-2">
                            <button type="button" @click="showModal = false" class="flex-1 px-6 py-4 btn-ghost rounded-2xl font-bold text-sm">Cancel</button>
                            <button type="submit" class="flex-1 px-6 py-4 btn-gold rounded-2xl font-bold text-sm" x-text="isEditing ? 'Save Changes' : 'Create User'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
