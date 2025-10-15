<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Staff Management</h2>
                <p class="text-sm text-gray-500 mt-1">Manage team members and their assignments</p>
            </div>
            <a href="{{ route('admin.staff.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Staff
            </a>
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

                // Use database directly to count assignments
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
                    {{-- Search --}}
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

                    {{-- Active Status --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select name="active"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                            <option value="">All Status</option>
                            <option value="1" @selected(request('active')==='1' )>Active</option>
                            <option value="0" @selected(request('active')==='0' )>Inactive</option>
                        </select>
                    </div>

                    {{-- Gender --}}
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

                    {{-- Action Buttons --}}
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
                                    Staff Member
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Role / Specialization
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Assignments
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($staffs as $s)
                            @php
                            // Count assignments using database directly
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

                {{-- Pagination --}}
                @if($staffs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-slate-50">
                    {{ $staffs->withQueryString()->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>