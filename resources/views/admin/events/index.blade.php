<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Events</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <form method="GET" class="bg-white p-4 rounded-lg shadow-sm grid grid-cols-1 md:grid-cols-7 gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search (event, customer, venue)"
                    class="border rounded px-3 py-2 md:col-span-2 text-sm">

                {{-- Package filter --}}
                <select name="package_id" class="border rounded px-3 py-2 text-sm">
                    <option value="">All Packages</option>
                    @isset($packages)
                    @foreach($packages as $p)
                    <option value="{{ $p->id }}" @selected((int)request('package_id')===$p->id)>{{ $p->name }}</option>
                    @endforeach
                    @endisset
                </select>

                {{-- Status filter --}}
                <select name="status" class="border rounded px-3 py-2 text-sm">
                    <option value="">All Status</option>
                    @foreach(['requested','approved','scheduled','completed','cancelled'] as $s)
                    <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>

                {{-- Date range (spans two columns) --}}
                <div class="flex gap-2 md:col-span-2">
                    <input type="date" name="from" value="{{ request('from') }}"
                        class="border rounded px-3 py-2 w-full text-sm">
                    <input type="date" name="to" value="{{ request('to')   }}"
                        class="border rounded px-3 py-2 w-full text-sm">
                </div>

                <div class="md:col-span-1 flex justify-end gap-2">
                    <a href="{{ route('admin.events.index') }}" class="px-3 py-2 border rounded">Reset</a>
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Filter</button>
                </div>
            </form>

            {{-- Table --}}
            <div class="bg-white rounded-lg shadow-sm p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-gray-600">
                        <tr>
                            <th class="text-left py-2">Date</th>
                            <th class="text-left py-2">Event</th>
                            <th class="text-left py-2">Package</th>
                            <th class="text-left py-2">Customer</th>
                            <th class="text-left py-2">Status</th>
                            <th class="text-left py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $e)
                        <tr class="border-t">
                            <td class="py-2">
                                {{ \Illuminate\Support\Carbon::parse($e->event_date)->format('Y-m-d') }}
                            </td>

                            <td class="py-2">
                                <div class="font-medium">{{ $e->name }}</div>
                                <div class="text-gray-500">{{ $e->venue ?: '—' }}</div>
                            </td>

                            <td class="py-2">
                                {{ $e->package?->name ?? '—' }}
                            </td>

                            <td class="py-2">
                                <div>{{ $e->customer?->customer_name ?? '—' }}</div>
                                <div class="text-gray-500">{{ $e->customer?->email ?? '' }}</div>
                            </td>

                            <td class="py-2">
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
                                    {{ ucwords(str_replace('_', ' ', strtolower($e->status))) }}
                                </span>
                            </td>

                            <td class="py-2 space-x-2">
                                <a href="{{ route('admin.events.show', $e) }}" class="underline">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="py-6 text-center text-gray-500" colspan="6">No events found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $events->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>