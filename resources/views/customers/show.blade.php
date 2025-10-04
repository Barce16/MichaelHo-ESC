<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Customer
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Info --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-2">Info</h3>
                @php
                $custName = $customer->user?->name ?? $customer->customer_name ?? $customer->name ?? 'Unknown';
                $avatarUrl = $customer->user?->profile_photo_url
                ?? 'https://ui-avatars.com/api/?name='.urlencode($custName).'&background=E5E7EB&color=111827';
                @endphp
                <img src="{{ $avatarUrl }}" class="h-16 w-16 rounded-full object-cover mb-4" alt="Avatar">

                <dl class="text-sm grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div><span class="font-medium">Name:</span> {{ $customer->customer_name ?? '—' }}</div>
                    <div><span class="font-medium">Email:</span> {{ $customer->email ?? '—' }}</div>
                    <div><span class="font-medium">Phone:</span> {{ $customer->phone ?? '—' }}</div>
                    <div>
                        <span class="font-medium">Address:</span> {{ $customer->address ?? '—' }}
                    </div>
                </dl>
            </div>

            {{-- Events --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">Events</h3>

                @if($customer->events->isEmpty())
                <p class="text-gray-600">No events yet.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-600">
                            <tr>
                                <th class="text-left py-2 pr-4">Event</th>
                                <th class="text-left py-2 pr-4">Date</th>
                                <th class="text-left py-2 pr-4">Status</th>
                                <th class="text-left py-2 pr-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->events as $e)
                            @php
                            $date = $e->event_date
                            ? \Illuminate\Support\Carbon::parse($e->event_date)->format('M d, Y')
                            : '—';

                            $badge = match(strtolower((string)$e->status)) {
                            'requested' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-blue-100 text-blue-800',
                            'scheduled' => 'bg-indigo-100 text-indigo-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800',
                            };
                            @endphp
                            <tr class="border-t">
                                <td class="py-2 pr-4 font-medium text-gray-900">
                                    {{ $e->name }}
                                    @if($e->venue)
                                    <div class="text-xs text-gray-500">{{ $e->venue }}</div>
                                    @endif
                                </td>
                                <td class="py-2 pr-4">{{ $date }}</td>
                                <td class="py-2 pr-4">
                                    <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                                        {{ ucfirst($e->status ?? '—') }}
                                    </span>
                                </td>
                                <td class="py-2 pr-4">
                                    <a href="{{ route('admin.events.show', $e) }}"
                                        class="text-indigo-600 hover:underline">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <div>
                <a href="{{ route('customers.index') }}" class="inline-block border px-4 py-2 rounded">Back to list</a>
            </div>

        </div>
    </div>
</x-app-layout>