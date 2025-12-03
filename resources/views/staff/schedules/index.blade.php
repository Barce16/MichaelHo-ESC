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
                            <svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64" fill="none"
                                class="w-6 h-6 text-gray-600">
                                <g id="SVGRepo_iconCarrier">
                                    <path fill="none" stroke="currentColor" stroke-width="2.88" stroke-miterlimit="10"
                                        d="M53.92,10.081c12.107,12.105,12.107,31.732,0,43.838 
                c-12.106,12.108-31.734,12.108-43.839,0c-12.107-12.105-12.107-31.732,0-43.838
                C22.186-2.027,41.813-2.027,53.92,10.081z">
                                    </path>
                                    <line fill="none" stroke="currentColor" stroke-width="2.88" stroke-miterlimit="10"
                                        x1="24" y1="48" x2="24" y2="16"></line>
                                    <path fill="none" stroke="currentColor" stroke-width="2.88" stroke-miterlimit="10"
                                        d="M24,17h7c0,0,11-1,11,9s-11,9-11,9h-7"></path>
                                    <line fill="none" stroke="currentColor" stroke-width="2.88" stroke-miterlimit="10"
                                        x1="19" y1="24" x2="47" y2="24"></line>
                                    <line fill="none" stroke="currentColor" stroke-width="2.88" stroke-miterlimit="10"
                                        x1="19" y1="28" x2="47" y2="28"></line>
                                </g>
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

                {{-- Quick Stats / Upcoming --}}
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                        <h3 class="font-semibold text-gray-900">Upcoming Assignments</h3>
                    </div>
                    <div class="divide-y divide-gray-100 max-h-[400px] overflow-y-auto">
                        @php
                        $upcomingEvents = $allAssignments->filter(function($event) {
                        return \Carbon\Carbon::parse($event->event_date)->gte(today());
                        })->sortBy('event_date')->take(5);
                        @endphp

                        @forelse($upcomingEvents as $event)
                        @php
                        $workStatus = $event->staff_assignment->work_status ?? 'pending';
                        $statusColors = match($workStatus) {
                        'finished' => 'bg-emerald-500',
                        'ongoing' => 'bg-amber-500',
                        default => 'bg-indigo-500',
                        };
                        @endphp
                        <a href="{{ route('staff.schedules.show', $event) }}"
                            class="flex items-center gap-3 p-4 hover:bg-gray-50 transition">
                            <div
                                class="w-12 h-12 {{ $statusColors }} rounded-lg flex flex-col items-center justify-center text-white flex-shrink-0">
                                <div class="text-lg font-bold">{{ \Carbon\Carbon::parse($event->event_date)->format('d')
                                    }}</div>
                                <div class="text-[10px] uppercase">{{
                                    \Carbon\Carbon::parse($event->event_date)->format('M') }}</div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 truncate">{{ $event->name }}</h4>
                                <p class="text-sm text-gray-500 truncate">{{ $event->customer->customer_name }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="font-bold text-gray-900">₱{{
                                    number_format($event->staff_assignment->pay_rate ?? 0, 2) }}</div>
                                <div class="text-xs text-gray-500">{{ $event->staff_assignment->assignment_role ?? '-'
                                    }}</div>
                            </div>
                        </a>
                        @empty
                        <div class="p-8 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="font-medium">No upcoming assignments</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                <form method="GET" class="flex flex-wrap gap-3">
                    <div>
                        <input type="month" name="month" value="{{ $month }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                    </div>
                    <div>
                        <select name="status"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                            <option value="all" {{ $status==='all' ? 'selected' : '' }}>All Status</option>
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
</x-app-layout>