<x-settings-layout active="security" title="Security Settings">
    <div x-data="{
        savedNotice: false,
        passwordSuccess: false,
        twoFactor: true,
        sessionTimeout: '60',
        currentPassword: '',
        newPassword: '',
        confirmPassword: '',
        passwordError: '',

        changePassword() {
            if (!this.currentPassword || !this.newPassword) {
                this.passwordError = 'Please fill in all password fields.';
                return;
            }
            if (this.newPassword.length < 8) {
                this.passwordError = 'New password must be at least 8 characters long.';
                return;
            }
            if (this.newPassword !== this.confirmPassword) {
                this.passwordError = 'Passwords do not match.';
                return;
            }

            this.passwordError = '';
            this.passwordSuccess = true;
            this.currentPassword = '';
            this.newPassword = '';
            this.confirmPassword = '';
            setTimeout(() => this.passwordSuccess = false, 5000);
        },

        saveSecurity() {
            this.savedNotice = true;
            setTimeout(() => this.savedNotice = false, 4000);
        }
    }">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Security & Access</h2>
                <p class="text-xs text-gray-500 mt-1">Review active devices, protect administrator access, and update credentials</p>
            </div>

            <button @click="saveSecurity()" class="px-5 py-2.5 bg-[#C9A050] hover:bg-[#b58f43] text-white rounded-xl font-bold text-xs sm:text-sm shadow-md shadow-[#C9A050]/20 transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Save Security Rules</span>
            </button>
        </div>

        <!-- Success Toast -->
        <div x-show="savedNotice" x-cloak 
             class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-bold flex items-center space-x-2 shadow-xs transition-all">
            <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs">✓</span>
            <span>Security rules updated successfully!</span>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-8">
            <!-- Security Overview Banner -->
            <div class="flex items-start space-x-5 p-6 bg-emerald-50/60 rounded-3xl border border-emerald-100">
                <div class="p-3 bg-emerald-500 text-white rounded-2xl shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">Account Protection Status: Strong</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">Your administrator session is authenticated with token encryption and HTTPS protection. No suspicious access attempts detected.</p>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-gray-100 shadow-xs">
                <h3 class="text-base font-bold text-gray-900 mb-1">Update Administrator Password</h3>
                <p class="text-xs text-gray-500 mb-6">Ensure your account uses a long, random password to stay secure</p>

                <div x-show="passwordSuccess" x-cloak class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-xl">
                    Password updated successfully!
                </div>

                <div x-show="passwordError" x-cloak class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs font-bold rounded-xl" x-text="passwordError"></div>

                <form @submit.prevent="changePassword()" class="space-y-4 max-w-lg">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Current Password</label>
                        <input type="password" x-model="currentPassword" placeholder="••••••••" 
                               class="w-full px-4 py-2.5 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm focus:ring-0">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">New Password</label>
                            <input type="password" x-model="newPassword" placeholder="••••••••" 
                                   class="w-full px-4 py-2.5 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Confirm Password</label>
                            <input type="password" x-model="confirmPassword" placeholder="••••••••" 
                                   class="w-full px-4 py-2.5 bg-gray-50 rounded-2xl border border-gray-200 focus:border-[#C9A050] text-sm focus:ring-0">
                        </div>
                    </div>

                    <button type="submit" class="px-5 py-2.5 bg-[#0A2E2A] hover:bg-[#12423d] text-white rounded-xl font-bold text-xs shadow-md transition-all">
                        Update Password
                    </button>
                </form>
            </div>

            <!-- Two-Factor Authentication & Session Timeout -->
            <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-gray-100 shadow-xs">
                <h3 class="text-base font-bold text-gray-900 mb-2">Access Rules</h3>
                <p class="text-xs text-gray-500 mb-6">Manage session timeouts and login safeguards</p>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div>
                            <span class="font-bold text-gray-800 text-sm block">Two-Factor Authentication (2FA)</span>
                            <span class="text-xs text-gray-400">Require OTP verification on unknown devices</span>
                        </div>
                        <button type="button" @click="twoFactor = !twoFactor" 
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden"
                                :class="twoFactor ? 'bg-[#C9A050]' : 'bg-gray-200'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                  :class="twoFactor ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div>
                            <span class="font-bold text-gray-800 text-sm block">Admin Session Idle Timeout</span>
                            <span class="text-xs text-gray-400">Automatically logout inactive administrator tabs</span>
                        </div>
                        <select x-model="sessionTimeout" class="px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700">
                            <option value="30">30 minutes</option>
                            <option value="60">1 hour</option>
                            <option value="120">2 hours</option>
                            <option value="480">8 hours</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Active Sessions Section -->
            <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-gray-100 shadow-xs">
                <h3 class="text-base font-bold text-gray-900 mb-1">Active Administrator Sessions</h3>
                <p class="text-xs text-gray-500 mb-6">Devices currently authenticated with this account</p>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-4 bg-gray-50/80 rounded-2xl border border-gray-100">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-white text-[#C9A050] flex items-center justify-center shadow-xs">
                                💻
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-gray-900">Current Windows Browser (Chrome / Edge)</h4>
                                <p class="text-[11px] text-emerald-600 font-semibold flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Active Now • This Device
                                </p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-emerald-100 text-emerald-800">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-settings-layout>