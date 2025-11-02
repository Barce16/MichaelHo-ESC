<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">My Events</h2>
            <a href="{{ route('customer.events.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-slate-900 to-gray-950 text-white px-4 py-2 rounded-lg hover:from-violet-600 hover:to-purple-700 transition shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Request New Event
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Events Calendar Component --}}
            <x-events-calendar :events="$events" userType="customer" />

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-slate-900 to-gray-800 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-white uppercase tracking-wide">Total Events</p>
                            <p class="text-4xl font-bold mt-1">{{ $events->total() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Upcoming</p>
                            <p class="text-4xl font-bold text-sky-600 mt-1">
                                {{ $events->whereIn('status', ['approved', 'meeting', 'scheduled'])->where('event_date',
                                '>=', now())->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-sky-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pending</p>
                            <p class="text-4xl font-bold text-amber-600 mt-1">
                                {{ $events->where('status', 'requested')->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Completed</p>
                            <p class="text-4xl font-bold text-emerald-600 mt-1">
                                {{ $events->where('status', 'completed')->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Events Table/List --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        All Events
                    </h3>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Event</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Package</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($events as $e)
                            @php
                            $date = \Illuminate\Support\Carbon::parse($e->event_date);
                            $statusConfig = match(strtolower($e->status)) {
                            'requested' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' =>
                            'border-amber-200', 'dot' => 'bg-amber-500'],
                            'approved' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' =>
                            'border-emerald-200', 'dot' => 'bg-emerald-500'],
                            'meeting' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' =>
                            'border-blue-200', 'dot' => 'bg-blue-500'],
                            'scheduled' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'border' =>
                            'border-violet-200', 'dot' => 'bg-violet-500'],
                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' =>
                            'border-green-200', 'dot' => 'bg-green-500'],
                            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200',
                            'dot' => 'bg-gray-500'],
                            };
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $e->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $e->venue ?: '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $date->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $date->format('l') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $e->package?->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} border">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                        {{ ucwords(str_replace('_', ' ', $e->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('customer.events.show', $e) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
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
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm rounded-lg bg-violet-500 text-white hover:bg-violet-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">No events yet</p>
                                    <p class="text-gray-400 text-sm mt-1">Start by creating your first event</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @forelse($events as $e)
                    @php
                    $date = \Illuminate\Support\Carbon::parse($e->event_date);
                    $statusConfig = match(strtolower($e->status)) {
                    'requested' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200',
                    'dot' => 'bg-amber-500'],
                    'approved' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' =>
                    'border-emerald-200', 'dot' => 'bg-emerald-500'],
                    'meeting' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'dot'
                    => 'bg-blue-500'],
                    'scheduled' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'border' =>
                    'border-violet-200', 'dot' => 'bg-violet-500'],
                    'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200',
                    'dot' => 'bg-green-500'],
                    default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'dot'
                    => 'bg-gray-500'],
                    };
                    @endphp
                    <div class="p-4 hover:bg-slate-50 transition">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $e->name }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $e->venue ?: '—' }}</div>
                            </div>
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} border">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                {{ ucwords(str_replace('_', ' ', $e->status)) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                            <div>
                                <div class="text-xs text-gray-500">Date</div>
                                <div class="font-medium text-gray-900">{{ $date->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Package</div>
                                <div class="font-medium text-gray-900">{{ $e->package?->name ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('customer.events.show', $e) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 text-sm rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                View
                            </a>
                            @if($e->status === 'requested')
                            <a href="{{ route('customer.events.edit', $e) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 text-sm rounded-lg bg-violet-500 text-white hover:bg-violet-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500 font-medium">No events yet</p>
                        <p class="text-gray-400 text-sm mt-1">Start by creating your first event</p>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($events->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $events->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>