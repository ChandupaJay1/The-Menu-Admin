<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <nav class="flex text-sm text-gray-500 mb-2">
                <a href="{{ route('users.index') }}" class="hover:text-[#C9A050]">Users</a>
                <span class="mx-2">></span>
                <span class="text-gray-900 font-semibold">Add New User</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Add New User</h1>
            <p class="text-sm text-gray-500 mt-1">Create a new team member account</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('users.store') }}" method="POST" class="p-8">
            @csrf

            <div class="max-w-2xl space-y-8">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-900 mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#C9A050] focus:border-transparent outline-none transition-all @error('name') border-red-500 @enderror"
                        placeholder="Enter full name" required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-900 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#C9A050] focus:border-transparent outline-none transition-all @error('email') border-red-500 @enderror"
                        placeholder="Enter email address" required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-900 mb-2">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#C9A050] focus:border-transparent outline-none transition-all @error('password') border-red-500 @enderror"
                        placeholder="Enter password (min 8 characters)" required>
                    @error('password')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-900 mb-2">Confirm
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#C9A050] focus:border-transparent outline-none transition-all"
                        placeholder="Confirm password" required>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit"
                        class="px-8 py-4 bg-[#C9A050] text-white rounded-2xl font-bold hover:bg-[#B38E46] transition-all shadow-lg shadow-[#C9A050]/20">
                        Create User
                    </button>
                    <a href="{{ route('users.index') }}"
                        class="px-8 py-4 bg-gray-100 text-gray-700 rounded-2xl font-bold hover:bg-gray-200 transition-all">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
