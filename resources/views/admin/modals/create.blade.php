<!-- Admin Creation Modal Component -->
<div id="userModal"
    class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col modal-content">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">Create New Admin</h3>
                        <p class="text-white/90 text-sm mt-1">Add a new administrator to the system</p>
                    </div>
                </div>
                <button onclick="closeUserModal()" class="text-white/80 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.create-user.store') }}" class="flex-1 overflow-y-auto p-8">
            @csrf


            {{-- Validation Errors Summary --}}
            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h3 class="font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            {{-- Hidden user_type field --}}
            <input type="hidden" name="user_type" value="admin">

            <div class="space-y-6">
                {{-- Account Information --}}
                <div>
                    <h4
                        class="text-lg font-semibold text-gray-900 flex items-center gap-2 pb-3 border-b-2 border-purple-200 mb-4">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Account Information
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition @error('name') border-red-500 @enderror"
                                placeholder="Enter full name">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition @error('email') border-red-500 @enderror"
                                placeholder="admin@example.com">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Username <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition @error('username') border-red-500 @enderror"
                                placeholder="admin.username">
                            @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="userPassword" name="password"
                                    class="block w-full px-4 py-3 pr-12 rounded-lg border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition @error('password') border-red-500 @enderror"
                                    placeholder="••••••••">
                                <button type="button" onclick="toggleUserPassword()"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-purple-600 transition">
                                    <svg id="userEyeIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="userEyeSlashIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="status" value="active" checked
                            class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-2 focus:ring-purple-200">
                        <span class="text-sm font-medium text-gray-700">
                            Active Account
                            <span class="block text-xs text-gray-500 mt-0.5">Administrator can log in and access the
                                system immediately</span>
                        </span>
                    </label>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="mt-8 pt-6 border-t border-gray-200 flex gap-3 justify-end">
                <button type="button" onclick="closeUserModal()"
                    class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-semibold rounded-lg hover:from-purple-600 hover:to-indigo-700 transition shadow-md hover:shadow-lg">
                    Create Administrator
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleUserPassword() {
        const passwordInput = document.getElementById('userPassword');
        const eyeIcon = document.getElementById('userEyeIcon');
        const eyeSlashIcon = document.getElementById('userEyeSlashIcon');
        
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        eyeIcon.classList.toggle('hidden');
        eyeSlashIcon.classList.toggle('hidden');
    }
</script>