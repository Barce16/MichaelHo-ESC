<x-admin.layouts.management>
    <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">{{ $vendor->name }}</h3>
            <a href="{{ route('admin.management.vendors.edit', $vendor) }}"
                class="px-3 py-2 bg-gray-800 text-white rounded">Edit</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-gray-600 text-sm">Price</div>
                <div class="font-medium">₱{{ number_format($vendor->price, 2) }}</div>
            </div>

            <div>
                <div class="text-gray-600 text-sm">Status</div>
                <div>
                    @php
                    $badge = $vendor->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                        {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <div>
                <div class="text-gray-600 text-sm">Email</div>
                <div class="font-medium">{{ $vendor->email ?: '—' }}</div>
            </div>

            <div>
                <div class="text-gray-600 text-sm">Phone</div>
                <div class="font-medium">{{ $vendor->phone ?: '—' }}</div>
            </div>

            <div class="md:col-span-2">
                <div class="text-gray-600 text-sm">Address</div>
                <div class="font-medium">{{ $vendor->address ?: '—' }}</div>
            </div>

            <div class="md:col-span-2">
                <div class="text-gray-600 text-sm">Notes</div>
                <div class="font-medium whitespace-pre-line">{{ $vendor->notes ?: '—' }}</div>
            </div>
        </div>

        <div class="pt-4 border-t">
            <a href="{{ route('admin.management.vendors.index') }}" class="underline">Back to vendors</a>
        </div>

        <div class="pt-6 border-t">
            <h4 class="text-md font-semibold mb-3">Events</h4>

            @if ($eventsUsingVendor->count() === 0)
            <div class="text-sm text-gray-600">No Events</div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 border-b">
                            <th class="py-2 pr-4">Event</th>
                            <th class="py-2 pr-4">Date</th>
                            <th class="py-2 pr-4">Customer</th>
                            <th class="py-2 pr-4">Package</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($eventsUsingVendor as $event)
                        <tr class="border-b">
                            <td class="py-2 pr-4 font-medium">{{ $event->name }}</td>
                            <td class="py-2 pr-4">
                                {{ \Illuminate\Support\Carbon::parse($event->event_date)->format('M d, Y') }}
                            </td>
                            <td class="py-2 pr-4">
                                {{ optional($event->customer)->customer_name ?? '—' }}
                            </td>
                            <td class="py-2 pr-4">
                                {{ optional($event->package)->name ?? '—' }}
                            </td>
                            <td class="py-2 pr-4">
                                <span class="px-2 py-1 rounded text-xs
                                    @class([
                                        'bg-gray-100 text-gray-800' => $event->status === 'requested',
                                        'bg-blue-100 text-blue-800' => $event->status === 'approved',
                                        'bg-yellow-100 text-yellow-800' => $event->status === 'scheduled',
                                        'bg-green-100 text-green-800' => $event->status === 'completed',
                                        'bg-red-100 text-red-800' => $event->status === 'cancelled',
                                    ])
                                ">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="py-2 pr-4">
                                <a href="{{ route('admin.events.show', $event) }}"
                                    class="text-indigo-600 hover:underline">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $eventsUsingVendor->links() }}
            </div>
            @endif
        </div>

    </div>
</x-admin.layouts.management>