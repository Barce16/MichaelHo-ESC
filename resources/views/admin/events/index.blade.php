<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Events Management</h2>
                <p class="text-sm text-gray-500 mt-1">Manage and track all event bookings</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Statistics Dashboard --}}
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                @php
                $totalEvents = $events->total();
                $requestedCount = \App\Models\Event::where('status', 'requested')->count();
                $approvedCount = \App\Models\Event::where('status', 'approved')->count();
                $scheduledCount = \App\Models\Event::where('status', 'scheduled')->count();
                $completedCount = \App\Models\Event::where('status', 'completed')->count();
                $cancelledCount = \App\Models\Event::where('status', 'cancelled')->count();
                @endphp

                {{-- Total Events --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Total</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $totalEvents }}</div>
                        </div>
                    </div>
                </div>

                {{-- Requested --}}
                <div class="bg-amber-50 rounded-xl shadow-sm border border-amber-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-amber-700 uppercase tracking-wide">Requested</div>
                            <div class="text-2xl font-bold text-amber-800">{{ $requestedCount }}</div>
                        </div>
                    </div>
                </div>

                {{-- Approved --}}
                <div class="bg-sky-50 rounded-xl shadow-sm border border-sky-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-sky-700 uppercase tracking-wide">Approved</div>
                            <div class="text-2xl font-bold text-sky-800">{{ $approvedCount }}</div>
                        </div>
                    </div>
                </div>

                {{-- Scheduled --}}
                <div class="bg-violet-50 rounded-xl shadow-sm border border-violet-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-violet-700 uppercase tracking-wide">Scheduled</div>
                            <div class="text-2xl font-bold text-violet-800">{{ $scheduledCount }}</div>
                        </div>
                    </div>
                </div>

                {{-- Completed --}}
                <div class="bg-emerald-50 rounded-xl shadow-sm border border-emerald-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-emerald-700 uppercase tracking-wide">Completed</div>
                            <div class="text-2xl font-bold text-emerald-800">{{ $completedCount }}</div>
                        </div>
                    </div>
                </div>

                {{-- Cancelled --}}
                <div class="bg-rose-50 rounded-xl shadow-sm border border-rose-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-rose-700 uppercase tracking-wide">Cancelled</div>
                            <div class="text-2xl font-bold text-rose-800">{{ $cancelledCount }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <h3 class="font-semibold text-gray-800">Filter Events</h3>
                </div>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-7 gap-4">
                    {{-- Search --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Event, customer, venue..."
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                        </div>
                    </div>

                    {{-- Package filter --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Package</label>
                        <select name="package_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                            <option value="">All Packages</option>
                            @isset($packages)
                            @foreach($packages as $p)
                            <option value="{{ $p->id }}" @selected((int)request('package_id')===$p->id)>{{ $p->name }}
                            </option>
                            @endforeach
                            @endisset
                        </select>
                    </div>

                    {{-- Status filter --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                            <option value="">All Status</option>
                            @foreach(['requested','approved','request_meeting','meeting','scheduled','ongoing','completed']
                            as $s)
                            <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">From Date</label>
                        <input type="date" name="from" value="{{ request('from') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                    </div>

                    {{-- Date To --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">To Date</label>
                        <input type="date" name="to" value="{{ request('to') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2 items-end">
                        <a href="{{ route('admin.events.index') }}"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition text-center">
                            Reset
                        </a>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Date
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Event Details
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Package
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($events as $e)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ \Illuminate\Support\Carbon::parse($e->event_date)->format('M d, Y')
                                                }}
                                            </div>
                                            @if($e->event_time)
                                            <div class="text-xs text-gray-500">
                                                {{ \Illuminate\Support\Carbon::parse($e->event_time)->format('g:i A') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $e->name }}</div>
                                    @if($e->event_location)
                                    <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ Str::limit($e->event_location, 30) }}
                                    </div>
                                    @endif
                                    @if($e->guest_count)
                                    <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        {{ number_format($e->guest_count) }} guests
                                    </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($e->package)
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $e->package->name }}</span>
                                    </div>
                                    @else
                                    <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if($e->customer)
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-sky-100 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-semibold text-sky-700">
                                                {{ strtoupper(substr($e->customer->customer_name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{
                                                $e->customer->customer_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $e->customer->email }}</div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $statusConfig = match($e->status) {
                                    'requested' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' =>
                                    'border-amber-200', 'dot' => 'bg-amber-500'],
                                    'approved' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'border' =>
                                    'border-sky-200', 'dot' => 'bg-sky-500'],
                                    'scheduled' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' =>
                                    'border-violet-200', 'dot' => 'bg-violet-500'],
                                    'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' =>
                                    'border-emerald-200', 'dot' => 'bg-emerald-500'],
                                    'cancelled' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' =>
                                    'border-rose-200', 'dot' => 'bg-rose-500'],
                                    default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' =>
                                    'border-slate-200', 'dot' => 'bg-slate-500'],
                                    };
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                        {{ ucwords(str_replace('_', ' ', strtolower($e->status))) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right">
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
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">No events found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new
                                        event</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($events->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-slate-50">
                    {{ $events->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>