<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">My Events</h2>
            <a href="{{ route('customer.events.create') }}" class="bg-gray-800 text-white px-4 py-2 rounded">
                Request Event
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white rounded-lg shadow-sm p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-gray-600">
                        <tr>
                            <th class="text-left py-2">Date</th>
                            <th class="text-left py-2">Event</th>
                            <th class="text-left py-2">Package</th>
                            <th class="text-left py-2">Status</th>
                            <th class="text-left py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $e)
                        <tr class="border-t">
                            <td class="py-2">{{ \Illuminate\Support\Carbon::parse($e->event_date)->format('Y-m-d') }}
                            </td>
                            <td class="py-2">
                                <div class="font-medium">{{ $e->name }}</div>
                                <div class="text-gray-500">{{ $e->venue ?: '—' }}</div>
                            </td>
                            <td class="py-2">{{ $e->package?->name ?? '—' }}</td>
                            <td class="py-2">
                                <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">
                                    {{ ucfirst($e->status) }}
                                </span>
                            </td>
                            <td class="py-2 space-x-2">
                                <a href="{{ route('customer.events.show', $e) }}"
                                    class="px-3 py-1 border rounded">View</a>

                                @if($e->status === 'requested')
                                <a href="{{ route('customer.events.edit', $e) }}"
                                    class="px-3 py-1 border rounded">Edit</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="py-6 text-center text-gray-500" colspan="5">No events yet.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

                <div class="mt-4">{{ $events->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>