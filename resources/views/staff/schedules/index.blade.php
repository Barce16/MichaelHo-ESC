<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">My Schedule</h2>
            <a href="{{ route('staff.earnings') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                View Earnings Report →
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-white border-l-4 border-gray-800 rounded-lg p-4 shadow-sm">
                <p class="text-gray-800 font-medium">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-white border-l-4 border-red-600 rounded-lg p-4 shadow-sm">
                <p class="text-red-600 font-medium">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="grid md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-gray-900 to-black text-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-gray-300 uppercase tracking-wide">Total Assignments
                            </div>
                            <div class="text-4xl font-bold mt-2">{{ $stats['total_assignments'] }}</div>
                        </div>
                        <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Upcoming</div>
                            <div class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['upcoming'] }}</div>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Completed</div>
                            <div class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['completed'] }}</div>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Earned</div>
                            <div class="text-2xl font-bold text-gray-900 mt-2">₱{{
                                number_format($stats['total_earnings'], 2) }}</div>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Calendar Section --}}
            <div class="grid lg:grid-cols-2 gap-6">
                {{-- Schedule Calendar --}}
                <div>
                    <x-staff-schedule-calendar :assignments="$allAssignments" />
                </div>

                {{-- Replace the "Upcoming & Tasks" card section --}}
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden"
                    x-data="{ showEventFilter: false, selectedEventId: '{{ request('event_filter', 'all') }}' }">

                    {{-- Header with Filter Button --}}
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900">Upcoming & Tasks</h3>
                        <button type="button" @click="showEventFilter = true"
                            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            @if(request('event_filter') && request('event_filter') !== 'all')
                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                            @endif
                            Filter Event
                        </button>
                    </div>

                    <div class="divide-y divide-gray-100 max-h-[450px] overflow-y-auto">
                        {{-- Inclusion Schedules (Tasks) --}}
                        @if(isset($inclusionSchedules) && $inclusionSchedules->count() > 0)
                        @foreach($inclusionSchedules as $schedule)
                        <div class="p-3 hover:bg-amber-50/50 transition"
                            x-data="{ showUpload: false, uploading: false }">
                            <div class="flex items-start gap-3">
                                {{-- Inclusion Image --}}
                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                    @if($schedule->inclusion->image)
                                    <img src="{{ Storage::url($schedule->inclusion->image) }}"
                                        alt="{{ $schedule->inclusion->name }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                {{-- Details --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-900 truncate text-sm">{{
                                                $schedule->inclusion->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $schedule->event->name }}</p>
                                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                                <span>{{ $schedule->scheduled_date->format('M d, Y') }}</span>
                                                @if($schedule->scheduled_time)
                                                <span>• {{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i
                                                    A') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Status/Action --}}
                                        <div class="flex-shrink-0">
                                            @if($schedule->proof_image)
                                            <div class="flex items-center gap-1">
                                                <button type="button"
                                                    onclick="viewProof('{{ asset('storage/' . $schedule->proof_image) }}', '{{ addslashes($schedule->inclusion->name) }}')"
                                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-emerald-700 bg-emerald-100 rounded-full hover:bg-emerald-200 transition">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Done
                                                </button>
                                                <button type="button" @click="showUpload = !showUpload"
                                                    class="p-1 text-gray-400 hover:text-gray-600 transition"
                                                    title="Re-upload">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </div>
                                            @else
                                            <button type="button" @click="showUpload = !showUpload"
                                                class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-white bg-amber-600 rounded-full hover:bg-amber-700 transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                                Upload
                                            </button>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Upload Form --}}
                                    <div x-show="showUpload" x-cloak x-transition
                                        class="mt-2 p-2 bg-gray-50 rounded-lg border">
                                        <form action="{{ route('staff.schedules.uploadProof', $schedule) }}"
                                            method="POST" enctype="multipart/form-data" @submit="uploading = true">
                                            @csrf
                                            <div class="flex items-center gap-2">
                                                <input type="file" name="proof_image" accept="image/*" required
                                                    class="flex-1 text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-gray-900 file:text-white hover:file:bg-black">
                                                <button type="submit" :disabled="uploading"
                                                    class="px-2 py-1 bg-emerald-600 text-white text-xs font-medium rounded hover:bg-emerald-700 disabled:opacity-50">
                                                    <span x-show="!uploading">Save</span>
                                                    <span x-show="uploading">...</span>
                                                </button>
                                                <button type="button" @click="showUpload = false"
                                                    class="text-gray-400 hover:text-gray-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Divider --}}
                        @php
                        $upcomingEvents = $allAssignments->filter(function($event) {
                        return \Carbon\Carbon::parse($event->event_date)->gte(today());
                        })->sortBy('event_date')->take(5);
                        @endphp
                        @if($upcomingEvents->count() > 0)
                        <div class="px-4 py-2 bg-gray-100">
                            <span class="text-xs font-semibold text-gray-500 uppercase">Event Assignments</span>
                        </div>
                        @endif
                        @endif

                        {{-- Upcoming Event Assignments --}}
                        @php
                        if(!isset($upcomingEvents)) {
                        $upcomingEvents = $allAssignments->filter(function($event) {
                        return \Carbon\Carbon::parse($event->event_date)->gte(today());
                        })->sortBy('event_date')->take(5);
                        }
                        @endphp

                        @forelse($upcomingEvents as $event)
                        @php
                        $workStatus = $event->staff_assignment->work_status ?? 'pending';
                        $statusColors = match($workStatus) {
                        'finished' => 'bg-emerald-500',
                        'ongoing' => 'bg-blue-500',
                        default => 'bg-gray-400',
                        };
                        @endphp
                        <div class="p-3 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full {{ $statusColors }}"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate text-sm">{{ $event->name }}</p>
                                    <p class="text-xs text-gray-500">{{
                                        \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</p>
                                </div>
                                <a href="{{ route('staff.schedules.show', $event) }}"
                                    class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @empty
                        @if(!isset($inclusionSchedules) || $inclusionSchedules->count() === 0)
                        <div class="p-8 text-center text-gray-500">
                            <p>No upcoming assignments</p>
                        </div>
                        @endif
                        @endforelse
                    </div>

                    {{-- Event Filter Modal --}}
                    <div x-show="showEventFilter" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
                        @click.self="showEventFilter = false">

                        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

                        <div class="flex min-h-full items-center justify-center p-4">
                            <div x-show="showEventFilter" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[80vh] overflow-hidden"
                                @click.away="showEventFilter = false">

                                {{-- Modal Header --}}
                                <div
                                    class="bg-gradient-to-r from-gray-900 to-black px-6 py-4 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                            </svg>
                                            Filter by Event
                                        </h3>
                                        <p class="text-sm text-gray-400 mt-0.5">Select an event to filter tasks</p>
                                    </div>
                                    <button type="button" @click="showEventFilter = false"
                                        class="text-white/80 hover:text-white transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Event List --}}
                                <div class="overflow-y-auto max-h-[60vh] p-4">
                                    <div class="space-y-2">
                                        {{-- All Events Option --}}
                                        <button type="button"
                                            @click="window.location.href = '{{ route('staff.schedules.index') }}'"
                                            :class="selectedEventId === 'all' 
                                    ? 'bg-gray-900 text-white' 
                                    : 'bg-white hover:bg-gray-50 text-gray-900 border border-gray-200'"
                                            class="w-full text-left px-4 py-3 rounded-lg font-medium transition">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0"
                                                    :class="selectedEventId === 'all' ? 'bg-white/20' : ''">
                                                    <svg class="w-5 h-5"
                                                        :class="selectedEventId === 'all' ? 'text-white' : 'text-gray-400'"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <span class="font-semibold">All Events</span>
                                                    <p class="text-xs opacity-75">Show all tasks</p>
                                                </div>
                                                <svg x-show="selectedEventId === 'all'" class="w-5 h-5 flex-shrink-0"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>

                                        {{-- Individual Events --}}
                                        @foreach($allAssignments as $event)
                                        @php
                                        $taskCount = $inclusionSchedules->where('event_id', $event->id)->count();
                                        @endphp
                                        @if($taskCount > 0)
                                        <button type="button"
                                            @click="window.location.href = '{{ route('staff.schedules.index', ['event_filter' => $event->id]) }}'"
                                            :class="selectedEventId === '{{ $event->id }}' 
                                    ? 'bg-gray-900 text-white' 
                                    : 'bg-white hover:bg-gray-50 text-gray-900 border border-gray-200'"
                                            class="w-full text-left px-4 py-3 rounded-lg transition">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex flex-col items-center justify-center flex-shrink-0 text-xs font-bold"
                                                    :class="selectedEventId === '{{ $event->id }}' ? 'bg-white/20 text-white' : 'text-gray-700'">
                                                    <div>{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}
                                                    </div>
                                                    <div class="text-[10px] uppercase">{{
                                                        \Carbon\Carbon::parse($event->event_date)->format('M') }}</div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-semibold truncate">{{ $event->name }}</div>
                                                    <div class="text-xs opacity-75 truncate">{{
                                                        $event->customer->customer_name }}</div>
                                                    <div class="text-xs opacity-60 mt-0.5">{{ $taskCount }} {{
                                                        Str::plural('task', $taskCount) }}</div>
                                                </div>
                                                <svg x-show="selectedEventId === '{{ $event->id }}'"
                                                    class="w-5 h-5 flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Modal Footer --}}
                                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                                    <button type="button" @click="showEventFilter = false"
                                        class="w-full px-4 py-2.5 bg-gray-900 text-white font-medium rounded-lg hover:bg-black transition">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                        <input type="month" name="month" value="{{ $month }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event Status</label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                            <option value="all" {{ $status==='all' ? 'selected' : '' }}>All Events</option>
                            <option value="scheduled" {{ $status==='scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ $status==='completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="px-6 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-black transition">
                        Filter
                    </button>
                    @if($status !== 'all' || $month !== now()->format('Y-m'))
                    <a href="{{ route('staff.schedules.index') }}"
                        class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Clear
                    </a>
                    @endif
                </form>
            </div>

            {{-- Events Schedule --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="font-semibold text-gray-900">My Assignments</h3>
                </div>

                <div class="divide-y divide-gray-200">
                    @forelse($events as $event)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-16 h-16 bg-gray-900 rounded-lg flex flex-col items-center justify-center text-white">
                                            <div class="text-2xl font-bold">{{
                                                \Carbon\Carbon::parse($event->event_date)->format('d') }}</div>
                                            <div class="text-xs uppercase">{{
                                                \Carbon\Carbon::parse($event->event_date)->format('M') }}</div>
                                        </div>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="text-lg font-bold text-gray-900">{{ $event->name }}</h4>
                                            @php
                                            $statusClasses = match(strtolower($event->status)) {
                                            'scheduled' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                                            'completed' => 'bg-green-100 text-green-800 border border-green-200',
                                            default => 'bg-gray-100 text-gray-800 border border-gray-200',
                                            };
                                            @endphp
                                            <span
                                                class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                                {{ ucfirst($event->status) }}
                                            </span>
                                        </div>

                                        <div class="space-y-1 text-sm text-gray-600">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span>{{ $event->customer->customer_name }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span class="font-medium text-gray-900">{{
                                                    $event->staff_assignment?->assignment_role ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Pay Rate</div>
                                    <div class="text-2xl font-bold text-gray-900">₱{{
                                        number_format($event->staff_assignment?->pay_rate ?? 0, 2) }}</div>
                                    @if($event->staff_assignment?->pay_status === 'paid')
                                    <div
                                        class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-green-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Paid
                                    </div>
                                    @else
                                    <div class="mt-1 text-xs font-semibold text-gray-500">
                                        Pending Payment
                                    </div>
                                    @endif
                                </div>

                                <a href="{{ route('staff.schedules.show', $event) }}"
                                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-black transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-lg font-medium">No assignments found</p>
                    </div>
                    @endforelse
                </div>

                @if($events->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $events->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Proof View Modal Script --}}
    <script>
        function viewProof(imageUrl, inclusionName) {
        const modal = document.createElement('div');
        modal.id = 'proofViewModal';
        modal.className = 'fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/70';
        modal.onclick = function(e) {
            if (e.target === modal) modal.remove();
        };
        
        modal.innerHTML = `
            <div class="relative max-w-3xl w-full bg-white rounded-xl shadow-2xl overflow-hidden">
                <button onclick="document.getElementById('proofViewModal').remove()"
                    class="absolute top-3 right-3 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="bg-emerald-50 px-4 py-3 border-b border-emerald-100">
                    <h6 class="font-medium text-emerald-900">✓ Proof: ${inclusionName}</h6>
                </div>
                <div class="p-3 bg-gray-50">
                    <img src="${imageUrl}" alt="Proof" class="w-full h-auto rounded" style="max-height: 70vh; object-fit: contain;">
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
    }
    </script>
</x-app-layout>