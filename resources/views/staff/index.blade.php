<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Staff Management</h2>
                <p class="text-sm text-gray-500 mt-1">Manage team members and their assignments</p>
            </div>
            <button onclick="openStaffModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Staff
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Statistics Dashboard --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @php
                $totalStaff = $staffs->total();
                $activeStaff = \App\Models\Staff::where('is_active', true)->count();
                $inactiveStaff = \App\Models\Staff::where('is_active', false)->count();

                $totalAssignments = \DB::table('event_staff')->count();
                $staffWithAssignments = \DB::table('event_staff')
                ->select('staff_id')
                ->distinct()
                ->count();
                @endphp

                {{-- Total Staff --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Total</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $totalStaff }}</div>
                        </div>
                    </div>
                </div>

                {{-- Active Staff --}}
                <div class="bg-emerald-50 rounded-xl shadow-sm border border-emerald-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-emerald-700 uppercase tracking-wide">Active</div>
                            <div class="text-2xl font-bold text-emerald-800">{{ $activeStaff }}</div>
                        </div>
                    </div>
                </div>

                {{-- Inactive Staff --}}
                <div class="bg-slate-50 rounded-xl shadow-sm border border-slate-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-slate-700 uppercase tracking-wide">Inactive</div>
                            <div class="text-2xl font-bold text-slate-800">{{ $inactiveStaff }}</div>
                        </div>
                    </div>
                </div>

                {{-- Total Assignments --}}
                <div class="bg-violet-50 rounded-xl shadow-sm border border-violet-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-violet-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-violet-700 uppercase tracking-wide">Assignments</div>
                            <div class="text-2xl font-bold text-violet-800">{{ $totalAssignments }}</div>
                        </div>
                    </div>
                </div>

                {{-- Assigned Staff --}}
                <div class="bg-sky-50 rounded-xl shadow-sm border border-sky-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-sky-700 uppercase tracking-wide">Assigned</div>
                            <div class="text-2xl font-bold text-sky-800">{{ $staffWithAssignments }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <h3 class="font-semibold text-gray-800">Filter Staff</h3>
                </div>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Name, email, or role..."
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select name="active"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                            <option value="">All Status</option>
                            <option value="1" @selected(request('active')==='1' )>Active</option>
                            <option value="0" @selected(request('active')==='0' )>Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Gender</label>
                        <select name="gender"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                            <option value="">All Genders</option>
                            @foreach(['male','female','other'] as $g)
                            <option value="{{ $g }}" @selected(request('gender')===$g)>{{ ucfirst($g) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2 items-end">
                        <a href="{{ route('admin.staff.index') }}"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition text-center">
                            Reset
                        </a>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Staff Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Staff Member</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Contact</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Role / Specialization</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Assignments</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($staffs as $s)
                            @php
                            $assignmentsCount = \DB::table('event_staff')->where('staff_id', $s->id)->count();
                            $initials = collect(explode(' ', $s->user->name))->map(fn($word) => strtoupper(substr($word,
                            0, 1)))->take(2)->implode('');
                            $genderColor = match($s->gender ?? 'other') {
                            'male' => 'from-blue-400 to-indigo-500',
                            'female' => 'from-pink-400 to-rose-500',
                            default => 'from-slate-400 to-gray-500',
                            };
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br {{ $genderColor }} rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                            <span class="text-sm font-bold text-white">{{ $initials }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $s->user->name }}</div>
                                            @if($s->user->username)
                                            <div class="text-xs text-gray-500"><span>@</span>{{ $s->user->username }}
                                            </div>
                                            @else
                                            <div class="text-xs text-gray-500">ID: #{{ str_pad($s->id, 4, '0',
                                                STR_PAD_LEFT) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 text-sm text-gray-900">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $s->user->email }}
                                        </div>
                                        @if($s->contact_number)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $s->contact_number }}
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($s->role_type)
                                    <div class="flex items-start gap-2">
                                        <div
                                            class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $s->role_type }}</div>
                                            @if($s->specialization)
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $s->specialization }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($assignmentsCount > 0)
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold bg-violet-100 text-violet-700">
                                        {{ $assignmentsCount }}
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($s->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                        Inactive
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.staff.show', $s) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ route('admin.staff.edit', $s) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-violet-700 bg-violet-100 rounded-lg hover:bg-violet-200 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">
                                        @if(request('q') || request('active') !== null || request('gender'))
                                        No staff found matching your filters
                                        @else
                                        No staff members yet
                                        @endif
                                    </p>
                                    <p class="text-gray-400 text-sm mt-1">
                                        @if(request('q') || request('active') !== null || request('gender'))
                                        Try adjusting your filters
                                        @else
                                        Get started by adding your first team member
                                        @endif
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($staffs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-slate-50">
                    {{ $staffs->withQueryString()->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Add Staff Modal --}}
    <div id="staffModal"
        class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div
            class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col modal-content">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-violet-500 to-purple-600 p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold">Add New Staff Member</h3>
                    <button onclick="closeStaffModal()" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Progress Indicator --}}
                <div class="flex items-center justify-between" id="progressBar">
                    <div class="flex items-center flex-1">
                        <div class="step-indicator flex items-center justify-center w-10 h-10 rounded-full border-2">
                            <span class="text-sm font-bold">1</span>
                        </div>
                        <div class="flex-1 h-1 mx-2 rounded progress-line"></div>
                    </div>
                    <div class="flex items-center flex-1">
                        <div class="step-indicator flex items-center justify-center w-10 h-10 rounded-full border-2">
                            <span class="text-sm font-bold">2</span>
                        </div>
                        <div class="flex-1 h-1 mx-2 rounded progress-line"></div>
                    </div>
                    <div class="flex items-center flex-1">
                        <div class="step-indicator flex items-center justify-center w-10 h-10 rounded-full border-2">
                            <span class="text-sm font-bold">3</span>
                        </div>
                        <div class="flex-1 h-1 mx-2 rounded progress-line"></div>
                    </div>
                    <div class="flex items-center">
                        <div class="step-indicator flex items-center justify-center w-10 h-10 rounded-full border-2">
                            <span class="text-sm font-bold">4</span>
                        </div>
                    </div>
                </div>

                {{-- Step Labels --}}
                <div class="flex justify-between mt-2 text-sm">
                    <span id="label1" class="font-semibold">Photo</span>
                    <span id="label2" class="opacity-70">Account</span>
                    <span id="label3" class="opacity-70">Personal</span>
                    <span id="label4" class="opacity-70">Employment</span>
                </div>
            </div>

            {{-- Form --}}
            <form id="addStaffForm" method="POST" action="{{ route('admin.staff.store') }}"
                enctype="multipart/form-data" class="flex-1 overflow-y-auto">
                @csrf

                {{-- Step 1: Profile Photo --}}
                <div class="form-step p-8" data-step="1">
                    <h4 class="text-xl font-bold text-gray-900 mb-6">Profile Photo</h4>
                    <div class="flex flex-col items-center gap-6">
                        <div id="avatarPreview"
                            class="w-32 h-32 rounded-full bg-gradient-to-br from-violet-100 to-purple-100 flex items-center justify-center overflow-hidden border-4 border-white shadow-lg">
                            <svg class="w-16 h-16 text-violet-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <label for="avatar" class="cursor-pointer w-full">
                            <div
                                class="flex items-center justify-center px-6 py-8 border-2 border-dashed border-gray-300 rounded-lg hover:border-violet-400 transition bg-gray-50 hover:bg-violet-50">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-3 text-sm text-gray-600">
                                        <span class="font-semibold text-violet-600">Click to upload</span> or drag and
                                        drop
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2">PNG, JPG up to 10MB</p>
                                </div>
                            </div>
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden"
                            onchange="previewAvatar(event)" />
                    </div>
                </div>

                {{-- Step 2: Account Information --}}
                <div class="form-step hidden p-8" data-step="2">
                    <h4 class="text-xl font-bold text-gray-900 mb-6">Account Information</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="name" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                    placeholder="Enter full name" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <span
                                        class="text-rose-500">*</span></label>
                                <input type="email" name="email" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                    placeholder="email@example.com" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Username <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="username" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                    placeholder="username" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password <span
                                        class="text-rose-500">*</span></label>
                                <input type="password" name="password" required
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                    placeholder="••••••••" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Personal Information --}}
                <div class="form-step hidden p-8" data-step="3">
                    <h4 class="text-xl font-bold text-gray-900 mb-6">Personal Information</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                                <input type="text" name="contact_number"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                    placeholder="+63 912 345 6789" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                                <select name="gender"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <input type="text" name="address"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                    placeholder="Street, City, Province" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 4: Employment Details --}}
                <div class="form-step hidden p-8" data-step="4">
                    <h4 class="text-xl font-bold text-gray-900 mb-6">Employment Details</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Role / Position</label>
                                <input type="text" name="role_type"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                    placeholder="e.g., Event Coordinator, Photographer" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Default Rate (per
                                    event)</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                    <input type="number" name="rate" step="0.01" min="0"
                                        class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                        placeholder="0.00" />
                                </div>
                                <input type="hidden" name="rate_type" value="per_event">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Remarks / Notes</label>
                                <textarea name="remarks" rows="3"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition resize-none"
                                    placeholder="Any additional notes or remarks..."></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="inline-flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" checked
                                        class="w-5 h-5 rounded border-gray-300 text-violet-600 focus:ring-2 focus:ring-violet-200">
                                    <span class="text-sm font-medium text-gray-700">
                                        Active Staff Member
                                        <span class="block text-xs text-gray-500 mt-0.5">This staff member can be
                                            assigned to events</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Footer Navigation --}}
            <div class="border-t border-gray-200 p-6 bg-gray-50">
                <div class="flex justify-between items-center">
                    <button type="button" id="prevBtn" onclick="previousStep()"
                        class="hidden px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-100 transition">
                        Previous
                    </button>
                    <div></div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeStaffModal()"
                            class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-100 transition">
                            Cancel
                        </button>
                        <button type="button" id="nextBtn" onclick="nextStep()"
                            class="px-6 py-3 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold rounded-lg hover:from-violet-600 hover:to-purple-700 transition">
                            Next
                        </button>
                        <button type="submit" form="addStaffForm" id="submitBtn"
                            class="hidden px-6 py-3 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold rounded-lg hover:from-violet-600 hover:to-purple-700 transition">
                            Create Staff Member
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 4;

        function openStaffModal() {
            document.getElementById('staffModal').classList.remove('hidden');
            currentStep = 1;
            showStep(1);
        }

        function closeStaffModal() {
            document.getElementById('staffModal').classList.add('hidden');
            document.getElementById('addStaffForm').reset();
            document.getElementById('avatarPreview').innerHTML = `
                <svg class="w-16 h-16 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            `;
            currentStep = 1;
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.form-step').forEach(el => el.classList.add('hidden'));
            
            // Show current step
            document.querySelector(`.form-step[data-step="${step}"]`).classList.remove('hidden');
            
            // Update progress indicators
            document.querySelectorAll('.step-indicator').forEach((el, index) => {
                const stepNumber = index + 1;
                
                if (stepNumber === step) {
                    // Current step - white background with violet text
                    el.classList.remove('bg-violet-600', 'border-white/30', 'text-white');
                    el.classList.add('bg-white', 'text-violet-600', 'border-white');
                } else if (stepNumber < step) {
                    // Completed steps - white background with violet text
                    el.classList.remove('bg-violet-600', 'border-white/30', 'text-white');
                    el.classList.add('bg-white', 'text-violet-600', 'border-white');
                } else {
                    // Future steps - violet background with white text
                    el.classList.remove('bg-white', 'text-violet-600', 'border-white');
                    el.classList.add('bg-violet-600', 'text-white', 'border-white/30');
                }
            });

            // Update progress lines
            document.querySelectorAll('.progress-line').forEach((el, index) => {
                if (index + 1 < step) {
                    el.classList.remove('bg-white/30');
                    el.classList.add('bg-white');
                } else {
                    el.classList.remove('bg-white');
                    el.classList.add('bg-white/30');
                }
            });

            // Update labels
            for (let i = 1; i <= totalSteps; i++) {
                const label = document.getElementById(`label${i}`);
                if (i === step) {
                    label.classList.add('font-semibold');
                    label.classList.remove('opacity-70');
                } else {
                    label.classList.remove('font-semibold');
                    label.classList.add('opacity-70');
                }
            }
            
            // Update buttons
            document.getElementById('prevBtn').classList.toggle('hidden', step === 1);
            document.getElementById('nextBtn').classList.toggle('hidden', step === totalSteps);
            document.getElementById('submitBtn').classList.toggle('hidden', step !== totalSteps);
        }

        function previewAvatar(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').innerHTML = 
                    `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('staffModal').classList.contains('hidden')) {
                closeStaffModal();
            }
        });
    </script>

    <style>
        .modal-content {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        #staffModal.hidden {
            display: none !important;
        }
    </style>
</x-app-layout>