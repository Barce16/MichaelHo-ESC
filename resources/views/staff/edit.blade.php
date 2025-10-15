<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Edit Staff Member</h2>
                <p class="text-sm text-gray-500 mt-1">Update staff information and settings</p>
            </div>
            <a href="{{ route('admin.staff.show', $staff) }}"
                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.staff.update', $staff) }}"
                class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Profile Photo Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile Photo
                        </h3>
                    </div>

                    <div class="p-6">
                        <div class="flex items-start gap-6">
                            <div class="flex-shrink-0">
                                <img src="{{ $staff->user->profile_photo_url }}"
                                    class="h-20 w-20 rounded-full object-cover ring-2 ring-gray-200 shadow-sm"
                                    alt="Current avatar">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload New Photo
                                </label>
                                <input type="file" name="avatar" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition" />
                                <p class="mt-2 text-xs text-gray-500">JPG, PNG or GIF. Max 2MB.</p>
                                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                            </div>
                        </div>
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

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            <x-text-input name="name" value="{{ old('name', $staff->user->name) }}" class="w-full"
                                autocomplete="name" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
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
                            <x-text-input type="email" name="email" value="{{ old('email', $staff->user->email) }}"
                                class="w-full" autocomplete="email" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Username --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Username
                            </label>
                            <x-text-input name="username" value="{{ old('username', $staff->user->username) }}"
                                class="w-full" autocomplete="username" placeholder="Optional" />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                New Password
                            </label>
                            <x-text-input type="password" name="password" class="w-full" autocomplete="new-password"
                                placeholder="Leave blank to keep current" />
                            <p class="mt-1 text-xs text-gray-500">Only fill if you want to change the password</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

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
                            <x-text-input name="contact_number"
                                value="{{ old('contact_number', $staff->contact_number) }}" class="w-full"
                                autocomplete="tel" placeholder="+63 900 000 0000" />
                            <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
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
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                                <option value="">Select gender</option>
                                @foreach(['male','female','other'] as $g)
                                <option value="{{ $g }}" @selected(old('gender', $staff->gender)===$g)>{{ ucfirst($g) }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
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
                            <x-text-input name="address" value="{{ old('address', $staff->address) }}" class="w-full"
                                autocomplete="street-address" placeholder="Full address" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Professional Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Professional Information
                        </h3>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Role Type --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                Role Type
                            </label>
                            <x-text-input name="role_type" value="{{ old('role_type', $staff->role_type) }}"
                                class="w-full" placeholder="e.g., Coordinator, Stylist" />
                            <x-input-error :messages="$errors->get('role_type')" class="mt-2" />
                        </div>

                        {{-- Default Rate --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Default Rate (per event)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                <x-text-input name="rate" type="number" step="0.01" min="0"
                                    value="{{ old('rate', $staff->rate ?? '') }}" class="w-full pl-8"
                                    placeholder="0.00" />
                            </div>
                            <input type="hidden" name="rate_type" value="per_event">
                            <x-input-error :messages="$errors->get('rate')" class="mt-2" />
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
                            <textarea name="remarks" rows="4"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400 px-3 py-2"
                                placeholder="Any additional notes or comments...">{{ old('remarks', $staff->remarks) }}</textarea>
                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                        </div>

                        {{-- Active Status --}}
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-lg border border-slate-200">
                                <input type="checkbox" name="is_active" value="1" @checked(old('is_active',
                                    $staff->is_active))
                                class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-2
                                focus:ring-emerald-200">
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-900">Active Status</label>
                                    <p class="text-xs text-gray-500 mt-0.5">Enable this staff member to be assigned to
                                        events</p>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Assigned Events (Read-only) --}}
                @if($staff->events->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Current Event Assignments
                            </h3>
                            <span class="px-2.5 py-1 bg-violet-100 text-violet-700 text-xs font-semibold rounded-full">
                                Read Only
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="bg-sky-50 border border-sky-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-sky-800">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Event assignments are managed from the event details page. This list is for reference
                                only.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($staff->events->take(6) as $e)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-slate-50 transition">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ $e->name }}</h4>
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($e->event_date)->format('M d, Y') }}
                                        </div>
                                        @if($e->pivot->assignment_role)
                                        <div class="mt-2">
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 bg-violet-50 text-violet-700 border border-violet-200 rounded text-xs font-medium">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ $e->pivot->assignment_role }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('admin.events.show', $e) }}"
                                    class="inline-flex items-center gap-1 text-xs text-slate-600 hover:text-slate-900 font-medium">
                                    View Event
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                            @endforeach
                        </div>

                        @if($staff->events->count() > 6)
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.staff.show', $staff) }}"
                                class="text-sm text-slate-600 hover:text-slate-900 font-medium">
                                View all {{ $staff->events->count() }} assignments →
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Form Actions --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.staff.show', $staff) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>