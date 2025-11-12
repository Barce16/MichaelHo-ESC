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
                                    class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
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
                            <tr class="hover:bg-slate-50 transition" x-data="{ showProgress: false }">
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

                                        {{-- Progress Button --}}
                                        @if($e->progress && $e->progress->count() > 0)
                                        <button @click="showProgress = true"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                            Progress
                                        </button>
                                        @endif

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

                                {{-- Progress Modal --}}
                                @if($e->progress && $e->progress->count() > 0)
                                <td class="relative">
                                    <div x-show="showProgress" x-cloak @click.self="showProgress = false"
                                        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                                        <div @click.stop
                                            class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-auto max-h-[90vh] overflow-hidden"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 transform scale-90"
                                            x-transition:enter-end="opacity-100 transform scale-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 transform scale-100"
                                            x-transition:leave-end="opacity-0 transform scale-90">

                                            {{-- Modal Header --}}
                                            <div
                                                class="bg-black text-white px-6 py-5 flex items-center justify-between">
                                                <div>
                                                    <h3 class="text-xl font-bold">Event Progress Tracking</h3>
                                                    <p class="text-sm text-white/80 mt-1">{{ $e->name }}</p>
                                                </div>
                                                <button @click="showProgress = false"
                                                    class="text-white/80 hover:text-white transition">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>

                                            {{-- Event Info --}}
                                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                                <div class="grid grid-cols-2 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Order ID:</span>
                                                        <span class="font-semibold ml-2">#{{ str_pad($e->id, 6, '0',
                                                            STR_PAD_LEFT) }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Event Date:</span>
                                                        <span class="font-semibold ml-2">{{ $e->event_date->format('M d,
                                                            Y') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Progress Timeline --}}
                                            <div class="p-6 overflow-y-auto max-h-[50vh]">
                                                <div class="space-y-1">
                                                    @foreach($e->progress as $progress)
                                                    <div
                                                        class="flex items-start gap-3 py-2 hover:bg-gray-50 rounded-lg px-2 -mx-2 transition">
                                                        {{-- Checkbox --}}
                                                        <div class="flex-shrink-0 mt-1">
                                                            <div
                                                                class="w-5 h-5 rounded border-2 {{ $loop->first ? 'bg-emerald-500 border-emerald-500' : 'bg-white border-gray-300' }} flex items-center justify-center">
                                                                <svg class="w-3 h-3 {{ $loop->first ? 'text-white' : 'text-gray-400' }}"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="3" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </div>
                                                        </div>

                                                        {{-- Progress Content --}}
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-start justify-between gap-2">
                                                                <h4
                                                                    class="font-semibold text-sm text-gray-900 leading-tight">
                                                                    {{ ucfirst(str_replace('_', ' ', $progress->status))
                                                                    }}
                                                                </h4>
                                                                <span
                                                                    class="text-xs text-gray-500 whitespace-nowrap mt-0.5">
                                                                    {{ $progress->progress_date->format('M d, g:i A') }}
                                                                </span>
                                                            </div>
                                                            @if($progress->details)
                                                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{
                                                                $progress->details }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            {{-- Modal Footer --}}
                                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                                                <button @click="showProgress = false"
                                                    class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-900 transition">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                @endif
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
                    <div class="p-4 hover:bg-slate-50 transition" x-data="{ showProgress: false }">
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

                            {{-- Progress Button (Mobile) --}}
                            @if($e->progress && $e->progress->count() > 0)
                            <button @click="showProgress = true"
                                class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 text-sm rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Progress
                            </button>
                            @endif

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

                        {{-- Progress Modal (Mobile) --}}
                        @if($e->progress && $e->progress->count() > 0)
                        <div x-show="showProgress" x-cloak @click.self="showProgress = false"
                            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                            <div @click.stop
                                class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-auto max-h-[90vh] overflow-hidden"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-90"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-90">

                                {{-- Modal Header --}}
                                <div class="bg-black text-white px-6 py-5 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold">Event Progress Tracking</h3>
                                        <p class="text-sm text-white/80 mt-1">{{ $e->name }}</p>
                                    </div>
                                    <button @click="showProgress = false"
                                        class="text-white/80 hover:text-white transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Event Info --}}
                                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Order ID:</span>
                                            <span class="font-semibold ml-2">#{{ str_pad($e->id, 6, '0', STR_PAD_LEFT)
                                                }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Event Date:</span>
                                            <span class="font-semibold ml-2">{{ $e->event_date->format('M d, Y')
                                                }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Progress Timeline --}}
                                <div class="p-6 overflow-y-auto max-h-[50vh]">
                                    <div class="space-y-1">
                                        @foreach($e->progress as $progress)
                                        <div
                                            class="flex items-start gap-3 py-2 hover:bg-gray-50 rounded-lg px-2 -mx-2 transition">
                                            {{-- Checkbox --}}
                                            <div class="flex-shrink-0 mt-1">
                                                <div
                                                    class="w-5 h-5 rounded border-2 {{ $loop->first ? 'bg-emerald-500 border-emerald-500' : 'bg-white border-gray-300' }} flex items-center justify-center">
                                                    <svg class="w-3 h-3 {{ $loop->first ? 'text-white' : 'text-gray-400' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>

                                            {{-- Progress Content --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2">
                                                    <h4 class="font-semibold text-sm text-gray-900 leading-tight">
                                                        {{ ucfirst(str_replace('_', ' ', $progress->status)) }}
                                                    </h4>
                                                    <span class="text-xs text-gray-500 whitespace-nowrap mt-0.5">
                                                        {{ $progress->progress_date->format('M d, g:i A') }}
                                                    </span>
                                                </div>
                                                @if($progress->details)
                                                <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{
                                                    $progress->details }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Modal Footer --}}
                                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                                    <button @click="showProgress = false"
                                        class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-900 transition">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
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