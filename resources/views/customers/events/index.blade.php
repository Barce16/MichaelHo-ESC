<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">My Events</h2>

            <a href="{{ route('customer.events.create') }}"
                class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-black transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Request Event
            </a>
        </div>
    </x-slot>

    @php
    $statusClass = function($s) {
    $s = strtolower($s ?? '');
    return match($s) {
    'requested' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
    'approved' => 'bg-amber-100 text-amber-800 border border-amber-200',
    'meeting' => 'bg-blue-100 text-blue-800 border border-blue-200',
    'completed' => 'bg-green-100 text-green-800 border border-green-200',
    'cancelled', 'canceled' => 'bg-rose-100 text-rose-800 border border-rose-200',
    default => 'bg-gray-100 text-gray-800 border border-gray-200',
    };
    };
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Desktop table --}}
            <div class="hidden md:block bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="font-semibold text-gray-900">My Events</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Event</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Package</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($events as $e)
                            @php $date = \Illuminate\Support\Carbon::parse($e->event_date); @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 align-top">
                                    <div class="font-medium text-gray-900">{{ $date->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $date->format('D') }}</div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <div class="font-medium text-gray-900">{{ $e->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $e->venue ?: '—' }}</div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <div class="text-sm text-gray-900">{{ $e->package?->name ?? '—' }}</div>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass($e->status) }}">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-current"></span>
                                        {{ ucfirst($e->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 align-top">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('customer.events.show', $e) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>

                                        @if($e->status === 'requested')
                                        <a href="{{ route('customer.events.edit', $e) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm rounded-lg bg-gray-900 text-white hover:bg-black transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5h2m2 0h2m-6 4h6M7 9h2m-2 4h2m2 0h6m-8 4h8M5 5h2" />
                                            </svg>
                                            Edit
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-gray-500" colspan="5">
                                    No events yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $events->links() }}
                </div>
            </div>

            {{-- Mobile list --}}
            <div class="md:hidden space-y-3">
                @forelse($events as $e)
                @php
                $date = \Illuminate\Support\Carbon::parse($e->event_date);
                $chip = $statusClass($e->status);
                @endphp

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-xs uppercase tracking-wide text-gray-500">Date</div>
                            <div class="font-semibold text-gray-900">
                                {{ $date->format('M d, Y') }}
                                <span class="ml-1 text-xs text-gray-500">({{ $date->format('D') }})</span>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[11px] font-semibold {{ $chip }}">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ ucfirst($e->status) }}
                        </span>
                    </div>

                    <div class="mt-3">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Event</div>
                        <div class="font-medium text-gray-900">{{ $e->name }}</div>
                        <div class="text-sm text-gray-500">{{ $e->venue ?: '—' }}</div>
                    </div>

                    <div class="mt-3">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Package</div>
                        <div class="text-sm text-gray-900">{{ $e->package?->name ?? '—' }}</div>
                    </div>

                    <div class="mt-4 flex items-center gap-2">
                        <a href="{{ route('customer.events.show', $e) }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View
                        </a>

                        @if($e->status === 'requested')
                        <a href="{{ route('customer.events.edit', $e) }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm rounded-lg bg-gray-900 text-white hover:bg-black transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5h2m2 0h2m-6 4h6M7 9h2m-2 4h2m2 0h6m-8 4h8M5 5h2" />
                            </svg>
                            Edit
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 text-center text-gray-500">
                    No events yet.
                </div>
                @endforelse

                @if($events->hasPages())
                <div>{{ $events->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>