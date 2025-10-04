<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">My Event</h2>
            <a href="{{ route('staff.schedule.index') }}" class="px-3 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Event card --}}
            <div class="bg-white p-6 rounded-lg shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">{{ $event->name }}</h3>
                    @php
                    $color = match($event->status) {
                    'requested' => 'bg-yellow-100 text-yellow-800',
                    'approved' => 'bg-blue-100 text-blue-800',
                    'scheduled' => 'bg-indigo-100 text-indigo-800',
                    'completed' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    default => 'bg-gray-100 text-gray-800',
                    };
                    @endphp

                    <span class="px-2 py-1 rounded text-xs {{ $color }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>

                <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500">Date</div>
                        <div>{{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Venue</div>
                        <div>{{ $event->venue ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Theme</div>
                        <div>{{ $event->theme ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Budget</div>
                        <div>{{ is_null($event->budget) ? '—' : '₱'.number_format($event->budget, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Guests</div>
                        <div>{{ $event->guests ?: '—' }}</div>
                    </div>
                </div>

                {{-- Customer --}}
                <div class="mt-4">
                    <div class="text-gray-500 text-sm mb-1">Customer</div>
                    <div class="p-3 border rounded">
                        <div class="font-medium">{{ $event->customer?->customer_name ?? '—' }}</div>
                        <div class="text-gray-600 text-sm">{{ $event->customer?->email ?? '' }}</div>
                    </div>
                </div>

                {{-- Staff list --}}
                <div class="mt-6">
                    <div class="text-gray-500 text-sm mb-1">Assigned Staff</div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-gray-600">
                                <tr>
                                    <th class="text-left py-2">Name</th>
                                    <th class="text-left py-2">Email</th>
                                    <th class="text-left py-2">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($event->staffs as $s)
                                <tr class="border-t">
                                    <td class="py-2">{{ $s->user->name }}</td>
                                    <td class="py-2">{{ $s->user->email }}</td>
                                    <td class="py-2">{{ $s->pivot->assignment_role ?? '—' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-gray-500 text-center">No staff assigned.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="mt-6">
                    <div class="text-gray-500 text-sm mb-1">Notes</div>
                    <div class="whitespace-pre-wrap">{{ $event->notes ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>