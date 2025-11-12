<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Event Details</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $event->name }}</p>
            </div>
            <a href="{{ route('staff.schedules.index') }}"
                class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                ← Back to Schedule
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Work Status Alert --}}
            @php
            $eventDate = \Carbon\Carbon::parse($event->event_date);
            $today = \Carbon\Carbon::today();
            $isEventToday = $eventDate->isSameDay($today);
            $isEventPast = $eventDate->isPast() && !$isEventToday;
            $workStatus = $assignment?->work_status ?? 'pending';
            @endphp

            @if($workStatus === 'finished')
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-green-800">Work Completed</p>
                        <p class="text-sm text-green-700">You have marked this work as finished.</p>
                    </div>
                </div>
            </div>
            @elseif($workStatus === 'ongoing' || $isEventToday)
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-600 animate-pulse" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-blue-800">Event is Today!</p>
                        <p class="text-sm text-blue-700">Don't forget to mark your work as finished when done.</p>
                    </div>
                </div>
            </div>
            @elseif($isEventPast)
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-amber-800">Event Date Passed</p>
                        <p class="text-sm text-amber-700">Please mark your work as finished if you've completed it.</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Event Information --}}
            <div class="bg-gradient-to-r from-gray-900 to-black rounded-lg shadow-sm p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Event Information</h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Event Date</div>
                        <div class="font-medium">{{ $eventDate->format('l, F d, Y') }}</div>
                        @if($isEventToday)
                        <div
                            class="inline-flex items-center gap-1 mt-2 px-2 py-1 bg-blue-500 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                            Today
                        </div>
                        @elseif($eventDate->isFuture())
                        <div class="text-xs text-gray-300 mt-1">{{ $eventDate->diffForHumans() }}</div>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Customer</div>
                        <div class="font-medium">{{ $event->customer->customer_name }}</div>
                        <div class="text-xs text-gray-300 mt-1">{{ $event->customer->email }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Package</div>
                        <div class="font-medium">{{ $event->package->name }}</div>
                    </div>
                </div>

                @if($event->venue)
                <div class="mt-4 pt-4 border-t border-white/20">
                    <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Venue</div>
                    <div class="font-medium">{{ $event->venue }}</div>
                </div>
                @endif
            </div>

            {{-- My Assignment --}}
            <div class="bg-white border-2 border-gray-900 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">My Assignment</h3>

                    {{-- Work Status Badge --}}
                    @php
                    $statusConfig = match($workStatus) {
                    'finished' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'ongoing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200',
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'icon'
                    => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    };
                    @endphp
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} border rounded-full text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $statusConfig['icon'] }}" />
                        </svg>
                        {{ ucfirst($workStatus) }}
                    </span>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Role</div>
                        <div class="text-lg font-bold text-gray-900">{{ $assignment?->assignment_role ?? '-' }}</div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Pay Rate</div>
                        <div class="text-2xl font-bold text-gray-900">₱{{ number_format($assignment?->pay_rate ?? 0, 2)
                            }}</div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Payment Status
                        </div>
                        @if($assignment?->pay_status === 'paid')
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Paid
                        </div>
                        @else
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pending
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Mark as Finished Button --}}
                {{-- UPDATED: Button shows for pending/ongoing, hidden only when finished --}}
                @if($workStatus !== 'finished')
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <form action="{{ route('staff.schedules.finish', $event) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to mark this work as finished? Admin will be notified.')">
                        @csrf
                        @method('POST')
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Mark Work as Finished
                        </button>
                        <p class="text-xs text-gray-500 text-center mt-2">Admin will be notified when you mark this as
                            finished</p>
                    </form>
                </div>
                @endif
            </div>

            {{-- Other Staff Assigned --}}
            @if($event->staffs->count() > 1)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="font-semibold text-gray-900">Other Staff Members ({{ $event->staffs->count() - 1 }})</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($event->staffs as $s)
                    @if($s->id !== $staff->id)
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($s->staff_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $s->staff_name }}</div>
                                <div class="text-xs text-gray-500">{{ $s->pivot->assignment_role }}</div>
                            </div>
                        </div>

                        {{-- Other staff work status --}}
                        @php
                        $otherWorkStatus = $s->pivot->work_status ?? 'pending';
                        $otherStatusConfig = match($otherWorkStatus) {
                        'finished' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                        'ongoing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                        default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                        };
                        @endphp
                        <span
                            class="px-2 py-1 {{ $otherStatusConfig['bg'] }} {{ $otherStatusConfig['text'] }} rounded-full text-xs font-medium">
                            {{ ucfirst($otherWorkStatus) }}
                        </span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>