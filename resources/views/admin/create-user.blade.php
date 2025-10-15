<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Create New User</h2>
                <p class="text-sm text-gray-500 mt-1">Add a new user to the system</p>
            </div>
            <a href="{{ route('admin.users.list') }}"
                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Error Display --}}
            @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-5">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-rose-800 mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside space-y-1 text-sm text-rose-700">
                            @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.create-user.store') }}"
                class="space-y-6">
                @csrf

                {{-- Profile Photo Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Profile Photo
                        </h3>
                    </div>

                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Photo (Optional)
                        </label>
                        <input type="file" name="avatar" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition" />
                        <p class="mt-2 text-xs text-gray-500">JPG, PNG or GIF. Max 2MB.</p>
                        <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                    </div>
                </div>

                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Basic Information
                        </h3>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- Name --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                placeholder="Enter full name" required>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Username --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Username <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                placeholder="Choose a username" required>
                            <p class="mt-1 text-xs text-gray-500">Unique identifier for the user</p>
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email Address <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                placeholder="user@example.com" required>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- User Type --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                User Type <span class="text-rose-500">*</span>
                            </label>
                            <select name="user_type"
                                class="w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                required>
                                <option value="">Select user type</option>
                                <option value="admin" @selected(old('user_type')==='admin' )>
                                    Admin - Full system access
                                </option>
                                <option value="staff" @selected(old('user_type')==='staff' ) disabled>
                                    Staff - Event management
                                </option>
                                <option value="customer" @selected(old('user_type')==='customer' ) disabled>
                                    Customer - Book events
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Security Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Security Information
                        </h3>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- Password --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    class="w-full px-4 py-3 pr-12 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                    placeholder="Enter secure password" required>

                                <!-- Toggle Button -->
                                <button type="button" id="togglePassword"
                                    class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition focus:outline-none">
                                    <!-- Eye Icon (Show) -->
                                    <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Eye Slash Icon (Hide) -->
                                    <svg id="eyeSlashIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Minimum 8 characters recommended</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Confirm Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full px-4 py-3 pr-12 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400"
                                    placeholder="Re-enter password" required>

                                <!-- Toggle Button -->
                                <button type="button" id="togglePasswordConfirmation"
                                    class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition focus:outline-none">
                                    <!-- Eye Icon (Show) -->
                                    <svg id="eyeIconConfirm" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Eye Slash Icon (Hide) -->
                                    <svg id="eyeSlashIconConfirm" class="w-5 h-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Must match the password above</p>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.users.list') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Create User
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script>
        // Password toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            eyeIcon.classList.toggle('hidden');
            eyeSlashIcon.classList.toggle('hidden');
        });

        // Password confirmation toggle
        const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');
        const eyeSlashIconConfirm = document.getElementById('eyeSlashIconConfirm');

        togglePasswordConfirmation.addEventListener('click', function() {
            const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmationInput.setAttribute('type', type);

            eyeIconConfirm.classList.toggle('hidden');
            eyeSlashIconConfirm.classList.toggle('hidden');
        });
    </script>
</x-app-layout>