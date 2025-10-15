<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Customer Details</h2>
                <p class="text-sm text-gray-500 mt-1">View customer information and booking history</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.customers.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @php
            $custName = $customer->user?->name ?? $customer->customer_name ?? $customer->name ?? 'Unknown';
            $avatarUrl = $customer->user?->profile_photo_url ??
            'https://ui-avatars.com/api/?name='.urlencode($custName).'&background=3B82F6&color=ffffff&size=200';
            $initials = collect(explode(' ', $custName))->map(fn($word) => strtoupper(substr($word, 0,
            1)))->take(2)->implode('');

            $totalEvents = $customer->events->count();
            $completedEvents = $customer->events->where('status', 'completed')->count();
            $upcomingEvents = $customer->events->where('event_date', '>=', now())->where('status', '!=',
            'cancelled')->count();
            $totalSpent = $customer->events->sum(fn($e) => $e->billing?->total_amount ?? 0);
            @endphp

            {{-- Customer Profile Card --}}
            <div class="bg-gradient-to-br from-slate-500 to-gray-600 rounded-xl shadow-lg overflow-hidden text-white">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        {{-- Avatar --}}
                        <div class="relative">
                            <div class="w-24 h-24 rounded-full ring-4 ring-white shadow-lg overflow-hidden bg-white">
                                <img src="{{ $avatarUrl }}" class="w-full h-full object-cover" alt="{{ $custName }}">
                            </div>
                            <div
                                class="absolute -bottom-2 -right-2 w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center ring-4 ring-white">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        {{-- Customer Info --}}
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-2">{{ $custName }}</h3>
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center gap-2 text-sky-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $customer->email ?? '—' }}</span>
                                </div>
                                @if($customer->phone)
                                <div class="flex items-center gap-2 text-sky-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>{{ $customer->phone }}</span>
                                </div>
                                @endif
                                @if($customer->address)
                                <div class="flex items-start gap-2 text-sky-50">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $customer->address }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Customer ID Badge --}}
                        <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-3 text-center">
                            <div class="text-xs text-sky-100 uppercase tracking-wider mb-1">Customer ID</div>
                            <div class="text-xl font-bold">#{{ str_pad($customer->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistics Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- Total Events --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Total Events</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $totalEvents }}</div>
                        </div>
                    </div>
                </div>

                {{-- Completed Events --}}
                <div class="bg-emerald-50 rounded-xl shadow-sm border border-emerald-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-emerald-700 uppercase tracking-wide">Completed</div>
                            <div class="text-2xl font-bold text-emerald-800">{{ $completedEvents }}</div>
                        </div>
                    </div>
                </div>

                {{-- Upcoming Events --}}
                <div class="bg-violet-50 rounded-xl shadow-sm border border-violet-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-violet-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-violet-700 uppercase tracking-wide">Upcoming</div>
                            <div class="text-2xl font-bold text-violet-800">{{ $upcomingEvents }}</div>
                        </div>
                    </div>
                </div>

                {{-- Total Spent --}}
                <div class="bg-amber-50 rounded-xl shadow-sm border border-amber-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 36 36"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                </path>
                                <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                <path
                                    d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-amber-700 uppercase tracking-wide">Total Spent</div>
                            <div class="text-lg font-bold text-amber-800">₱{{ number_format($totalSpent, 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Events List --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-800">Event History</h3>
                        </div>
                        <span class="px-3 py-1 bg-slate-200 text-slate-700 text-xs font-semibold rounded-full">
                            {{ $totalEvents }} {{ Str::plural('event', $totalEvents) }}
                        </span>
                    </div>
                </div>

                @if($customer->events->isEmpty())
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 font-medium mb-1">No events yet</p>
                    <p class="text-gray-400 text-sm">This customer hasn't booked any events</p>
                </div>
                @else
                <div class="divide-y divide-gray-200">
                    @foreach($customer->events->sortByDesc('event_date') as $e)
                    @php
                    $date = $e->event_date ? \Carbon\Carbon::parse($e->event_date) : null;
                    $statusConfig = match(strtolower((string)$e->status)) {
                    'requested' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200',
                    'dot' => 'bg-amber-500'],
                    'approved' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'border' => 'border-sky-200', 'dot' =>
                    'bg-sky-500'],
                    'scheduled' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' =>
                    'border-violet-200', 'dot' => 'bg-violet-500'],
                    'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' =>
                    'border-emerald-200', 'dot' => 'bg-emerald-500'],
                    'cancelled' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200',
                    'dot' => 'bg-rose-500'],
                    default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'dot'
                    => 'bg-slate-500'],
                    };
                    @endphp
                    <div class="p-6 hover:bg-slate-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-3 mb-3">
                                    <div
                                        class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-1">{{ $e->name }}</h4>
                                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                                            @if($date)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $date->format('M d, Y') }}
                                            </div>
                                            @endif
                                            @if($e->event_location)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ Str::limit($e->event_location, 30) }}
                                            </div>
                                            @endif
                                            @if($e->package)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                {{ $e->package->name }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                    {{ ucwords(str_replace('_', ' ', strtolower($e->status))) }}
                                </span>

                                <a href="{{ route('admin.events.show', $e) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>