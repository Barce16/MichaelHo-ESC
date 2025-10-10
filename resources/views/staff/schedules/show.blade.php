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

            {{-- Event Information --}}
            <div class="bg-gradient-to-r from-gray-900 to-black rounded-lg shadow-sm p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Event Information</h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Event Date</div>
                        <div class="font-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('l, F d, Y') }}
                        </div>
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
                <h3 class="text-lg font-semibold text-gray-900 mb-4">My Assignment</h3>

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
                                {{ substr($s->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $s->name }}</div>
                                <div class="text-xs text-gray-500">{{ $s->pivot->assignment_role }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>