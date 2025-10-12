<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Staff Details</h2>
                <p class="text-sm text-gray-500 mt-1">View staff member information and assignments</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('staff.edit', $staff) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('staff.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @php
            $initials = collect(explode(' ', $staff->user->name))->map(fn($word) => strtoupper(substr($word, 0,
            1)))->take(2)->implode('');
            $genderGradient = match($staff->gender ?? 'other') {
            'male' => 'from-slate-500 to-gray-600',
            'female' => 'from-slate-500 to-gray-600',
            default => 'from-slate-500 to-gray-600',
            };

            $events = isset($assignedEvents) ? $assignedEvents : $staff->events;
            $list = $events instanceof \Illuminate\Contracts\Pagination\Paginator ? $events : collect($events);

            $totalAssignments = $list->count();
            $upcomingAssignments = $list->where('event_date', '>=', now())->where('status', '!=', 'cancelled')->count();
            $completedAssignments = $list->where('status', 'completed')->count();
            @endphp

            {{-- Staff Profile Card --}}
            <div class="bg-gradient-to-br {{ $genderGradient }} rounded-xl shadow-lg overflow-hidden text-white">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        {{-- Avatar --}}
                        <div class="relative">
                            <div class="w-24 h-24 rounded-full ring-4 ring-white shadow-lg overflow-hidden bg-white">
                                <img src="{{ $staff->user->profile_photo_url }}" class="w-full h-full object-cover"
                                    alt="{{ $staff->user->name }}">
                            </div>
                            @if($staff->is_active)
                            <div
                                class="absolute -bottom-2 -right-2 w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center ring-4 ring-white">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            @else
                            <div
                                class="absolute -bottom-2 -right-2 w-10 h-10 bg-slate-500 rounded-full flex items-center justify-center ring-4 ring-white">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        {{-- Staff Info --}}
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-2">{{ $staff->user->name }}</h3>
                            @if($staff->role_type)
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur rounded-lg text-sm font-medium mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $staff->role_type }}
                            </div>
                            @endif

                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center gap-2 text-white/90">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $staff->user->email }}</span>
                                </div>
                                @if($staff->contact_number)
                                <div class="flex items-center gap-2 text-white/90">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>{{ $staff->contact_number }}</span>
                                </div>
                                @endif
                                @if($staff->user->username)
                                <div class="flex items-center gap-2 text-white/90">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ '@' . $staff->user->username }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Staff ID Badge --}}
                        <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-3 text-center">
                            <div class="text-xs text-white/70 uppercase tracking-wider mb-1">Staff ID</div>
                            <div class="text-xl font-bold">#{{ str_pad($staff->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistics Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- Total Assignments --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Total</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $totalAssignments }}</div>
                        </div>
                    </div>
                </div>

                {{-- Completed --}}
                <div class="bg-emerald-50 rounded-xl shadow-sm border border-emerald-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-emerald-700 uppercase tracking-wide">Completed</div>
                            <div class="text-2xl font-bold text-emerald-800">{{ $completedAssignments }}</div>
                        </div>
                    </div>
                </div>

                {{-- Upcoming --}}
                <div class="bg-violet-50 rounded-xl shadow-sm border border-violet-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-violet-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-violet-700 uppercase tracking-wide">Upcoming</div>
                            <div class="text-2xl font-bold text-violet-800">{{ $upcomingAssignments }}</div>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div
                    class="bg-{{ $staff->is_active ? 'emerald' : 'slate' }}-50 rounded-xl shadow-sm border border-{{ $staff->is_active ? 'emerald' : 'slate' }}-200 p-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 bg-{{ $staff->is_active ? 'emerald' : 'slate' }}-100 rounded-lg flex items-center justify-center">
                            @if($staff->is_active)
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @else
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            @endif
                        </div>
                        <div>
                            <div
                                class="text-xs text-{{ $staff->is_active ? 'emerald' : 'slate' }}-700 uppercase tracking-wide">
                                Status</div>
                            <div class="text-lg font-bold text-{{ $staff->is_active ? 'emerald' : 'slate' }}-800">{{
                                $staff->is_active ? 'Active' : 'Inactive' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Additional Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Additional Information
                    </h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($staff->specialization)
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                <span
                                    class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Specialization</span>
                            </div>
                            <div class="text-sm text-gray-900 bg-slate-50 rounded-lg p-3">{{ $staff->specialization }}
                            </div>
                        </div>
                        @endif

                        @if($staff->gender)
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Gender</span>
                            </div>
                            <div class="text-sm text-gray-900 bg-slate-50 rounded-lg p-3 capitalize">{{ $staff->gender
                                }}</div>
                        </div>
                        @endif

                        @if($staff->address)
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Address</span>
                            </div>
                            <div class="text-sm text-gray-900 bg-slate-50 rounded-lg p-3">{{ $staff->address }}</div>
                        </div>
                        @endif

                        @if($staff->remarks)
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Remarks</span>
                            </div>
                            <div class="text-sm text-gray-900 bg-slate-50 rounded-lg p-3 whitespace-pre-line">{{
                                $staff->remarks }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Assigned Events --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-800">Event Assignments</h3>
                        </div>
                        <span class="px-3 py-1 bg-slate-200 text-slate-700 text-xs font-semibold rounded-full">
                            {{ $totalAssignments }} {{ Str::plural('assignment', $totalAssignments) }}
                        </span>
                    </div>
                </div>

                @if($list->count() === 0)
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 font-medium mb-1">No assignments yet</p>
                    <p class="text-gray-400 text-sm">This staff member hasn't been assigned to any events</p>
                </div>
                @else
                <div class="divide-y divide-gray-200">
                    @foreach ($list as $e)
                    @php
                    $eventDate = \Carbon\Carbon::parse($e->event_date);
                    $statusConfig = match($e->status) {
                    'requested' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200',
                    'dot' => 'bg-amber-500'],
                    'approved' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'border' => 'border-sky-200', 'dot' =>
                    'bg-sky-500'],
                    'scheduled' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' =>
                    'border-violet-200', 'dot' => 'bg-violet-500'],
                    'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' =>
                    'border-emerald-200', 'dot' => 'bg-emerald-500'],
                    'cancelled' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200',
                    'dot' => 'bg-rose-500'],
                    default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'dot'
                    => 'bg-slate-500'],
                    };
                    @endphp
                    <div class="p-6 hover:bg-slate-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-3 mb-3">
                                    <div
                                        class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-1">{{ $e->name }}</h4>
                                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-2">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $eventDate->format('M d, Y') }}
                                            </div>
                                            @if($e->event_location)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ Str::limit($e->event_location, 30) }}
                                            </div>
                                            @endif
                                        </div>

                                        {{-- Assignment Role --}}
                                        @if($e->pivot && $e->pivot->assignment_role)
                                        <div
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-violet-50 text-violet-700 border border-violet-200 rounded-lg text-xs font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Role: {{ $e->pivot->assignment_role }}
                                        </div>
                                        @endif

                                        {{-- Customer --}}
                                        @if($e->customer)
                                        <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Customer: {{ $e->customer->customer_name }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                    {{ ucwords(str_replace('_', ' ', strtolower($e->status))) }}
                                </span>

                                <a href="{{ route('admin.events.show', $e) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator && $events->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-slate-50">
                    {{ $events->withQueryString()->links() }}
                </div>
                @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>