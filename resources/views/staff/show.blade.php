<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Staff</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                <img src="{{ $staff->user->profile_photo_url }}" class="h-16 w-16 rounded-full object-cover"
                    alt="Avatar">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">{{ $staff->user->name }}</h3>
                    <a href="{{ route('staff.edit', $staff) }}"
                        class="px-3 py-2 bg-gray-800 text-white rounded">Edit</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-gray-600 text-sm">Email</div>
                        <div class="font-medium">{{ $staff->user->email }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Username</div>
                        <div class="font-medium">{{ $staff->user->username ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Contact Number</div>
                        <div class="font-medium">{{ $staff->contact_number ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Role Type</div>
                        <div class="font-medium">{{ $staff->role_type ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Gender</div>
                        <div class="font-medium capitalize">{{ $staff->gender ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Status</div>
                        <span
                            class="px-2 py-1 rounded text-xs {{ $staff->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $staff->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-gray-600 text-sm">Address</div>
                        <div class="font-medium">{{ $staff->address ?: '—' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-gray-600 text-sm">Remarks</div>
                        <div class="font-medium whitespace-pre-line">{{ $staff->remarks ?: '—' }}</div>
                    </div>
                </div>

                <div class="pt-4 border-t">
                    <h4 class="text-md font-semibold mb-3">Assigned Events</h4>

                    @php
                    $events = isset($assignedEvents) ? $assignedEvents : $staff->events;
                    @endphp

                    @if($events instanceof \Illuminate\Contracts\Pagination\Paginator)
                    @php $list = $events; @endphp
                    @else
                    @php $list = collect($events); @endphp
                    @endif

                    @if($list->count() === 0)
                    <div class="text-sm text-gray-600">No events assigned.</div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-600 border-b">
                                    <th class="py-2 pr-4">Date</th>
                                    <th class="py-2 pr-4">Event</th>
                                    <th class="py-2 pr-4">Customer</th>
                                    <th class="py-2 pr-4">Role</th>
                                    <th class="py-2 pr-4">Status</th>
                                    <th class="py-2 pr-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $e)
                                <tr class="border-b">
                                    <td class="py-2 pr-4">
                                        {{ \Illuminate\Support\Carbon::parse($e->event_date)->format('Y-m-d') }}
                                    </td>
                                    <td class="py-2 pr-4">
                                        <div class="font-medium">{{ $e->name }}</div>
                                        <div class="text-gray-500">{{ $e->venue ?: '—' }}</div>
                                    </td>
                                    <td class="py-2 pr-4">
                                        {{ $e->customer?->customer_name ?? '—' }}
                                        <div class="text-xs text-gray-500">{{ $e->customer?->email ?? '' }}</div>
                                    </td>
                                    <td class="py-2 pr-4 text-xs text-gray-600">
                                        {{ $e->pivot->assignment_role ?? '—' }}
                                    </td>
                                    <td class="py-2 pr-4">
                                        @php
                                        $color = match($e->status){
                                        'requested' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-blue-100 text-blue-800',
                                        'scheduled' => 'bg-indigo-100 text-indigo-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                        };
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs {{ $color }}">
                                            {{ ucfirst($e->status) }}
                                        </span>
                                    </td>
                                    <td class="py-2 pr-4">
                                        <a href="{{ route('admin.events.show', $e) }}"
                                            class="text-indigo-600 hover:underline">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-4">
                        {{ $events->withQueryString()->links() }}
                    </div>
                    @endif
                    @endif
                </div>

                <div class="pt-4 border-t">
                    <a href="{{ route('staff.index') }}" class="underline">Back to staff</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>