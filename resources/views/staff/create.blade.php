<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Add New Staff Member</h2>
            <a href="{{ route('staff.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Staff
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Error Alert --}}
            @if ($errors->any())
            <div
                class="bg-gradient-to-r from-rose-50 to-red-50 border-l-4 border-rose-500 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-rose-900 mb-2">Please fix the following errors:</h3>
                            <ul class="list-disc pl-5 space-y-1 text-sm text-rose-800">
                                @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <form method="POST" enctype="multipart/form-data" action="{{ route('staff.store') }}" class="space-y-6">
                @csrf

                {{-- Profile Photo Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-violet-50 to-purple-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Profile Photo
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Upload a profile picture for this staff member</p>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center gap-6">
                            <div class="flex-shrink-0">
                                <div id="avatar-preview"
                                    class="w-24 h-24 rounded-full bg-gradient-to-br from-violet-100 to-purple-100 flex items-center justify-center overflow-hidden border-4 border-white shadow-lg">
                                    <svg class="w-12 h-12 text-violet-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <label for="avatar" class="cursor-pointer">
                                    <div
                                        class="flex items-center justify-center px-6 py-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-violet-400 transition bg-gray-50 hover:bg-violet-50">
                                        <div class="text-center">
                                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">
                                                <span class="font-semibold text-violet-600">Click to upload</span> or
                                                drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 10MB</p>
                                        </div>
                                    </div>
                                </label>
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden"
                                    onchange="previewAvatar(event)" />
                                @error('avatar')
                                <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Account Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-sky-50 to-blue-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Account Information
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Basic login credentials for the staff member</p>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                                    placeholder="Enter full name" />
                                @error('name')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
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
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                                    placeholder="email@example.com" />
                                @error('email')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Username --}}
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    Username <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="username" value="{{ old('username') }}" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                                    placeholder="username" />
                                @error('username')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Password <span class="text-rose-500">*</span>
                                </label>
                                <input type="password" name="password" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                                    placeholder="••••••••" />
                                @error('password')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Personal Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Personal Information
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Contact details and personal information</p>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Contact Number --}}
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    Contact Number
                                </label>
                                <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                                    placeholder="+63 912 345 6789" />
                                @error('contact_number')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Gender --}}
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Gender
                                </label>
                                <select name="gender"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition">
                                    <option value="">Select gender</option>
                                    <option value="male" {{ old('gender')==='male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender')==='female' ? 'selected' : '' }}>Female
                                    </option>
                                    <option value="other" {{ old('gender')==='other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Address
                                </label>
                                <input type="text" name="address" value="{{ old('address') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                                    placeholder="Street, City, Province" />
                                @error('address')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Employment Details --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Employment Details
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Role and compensation information</p>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Role Type --}}
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Role / Position
                                </label>
                                <input type="text" name="role_type" value="{{ old('role_type') }}"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition"
                                    placeholder="e.g., Event Coordinator, Photographer" />
                                @error('role_type')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Default Rate --}}
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 36 36">
                                        <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                        </path>
                                        <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                        <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                        <path
                                            d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                        </path>
                                    </svg>
                                    Default Rate (per event)
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                    <input type="number" name="rate" step="0.01" min="0" value="{{ old('rate') }}"
                                        class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition"
                                        placeholder="0.00" />
                                </div>
                                <input type="hidden" name="rate_type" value="per_event">
                                @error('rate')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Remarks --}}
                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    Remarks / Notes
                                </label>
                                <textarea name="remarks" rows="3"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition resize-none"
                                    placeholder="Any additional notes or remarks...">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Active Status --}}
                            <div class="md:col-span-2">
                                <label class="inline-flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true)
                                        ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-2 focus:ring-amber-200">
                                    <span class="text-sm font-medium text-gray-700">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Active Staff Member
                                        </span>
                                        <span class="text-xs text-gray-500 block ml-6 mt-0.5">This staff member can be
                                            assigned to events</span>
                                    </span>
                                </label>
                                @error('is_active')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('staff.index') }}"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold rounded-lg hover:from-violet-600 hover:to-purple-700 transition shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Staff Member
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    </script>
</x-app-layout>