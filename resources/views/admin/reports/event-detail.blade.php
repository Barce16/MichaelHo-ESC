<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800">Individual Event Report</h2>
            </div>
        </div>
    </x-slot>

    {{-- Print Styles --}}
    <style>
        @media print {

            nav,
            header,
            .no-print,
            .no-print * {
                display: none !important;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .print-container {
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }

            .print-content {
                box-shadow: none !important;
            }

            .overflow-x-auto,
            .overflow-y-auto,
            .overflow-auto {
                overflow: visible !important;
            }

            .max-h-64,
            .max-h-96,
            [class*="max-h-"] {
                max-height: none !important;
            }
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print-container">

            {{-- Event Selection (if no event selected) --}}
            @if(!isset($event))
            <div class="bg-white rounded-xl shadow-sm overflow-hidden no-print">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-8 text-white">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Generate Event Report</h3>
                            <p class="text-violet-100 mt-1">Select an event to view complete details and history</p>
                        </div>
                    </div>
                </div>

                {{-- Search & Stats Section --}}
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                        {{-- Search Form --}}
                        <form method="GET" class="flex-1 max-w-md">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search by event name or customer..."
                                    class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-300 focus:ring-2 focus:ring-violet-200 focus:border-violet-400 text-gray-900">
                            </div>
                        </form>

                        {{-- Quick Stats --}}
                        <div class="flex flex-wrap gap-3 text-sm">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200">
                                <span class="w-2 h-2 bg-violet-500 rounded-full"></span>
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold text-gray-900">{{ $totalEvents ?? $events->total() }}</span>
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="text-gray-600">Completed:</span>
                                <span class="font-semibold text-gray-900">{{ $completedEvents ?? 0 }}</span>
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span class="text-gray-600">Scheduled:</span>
                                <span class="font-semibold text-gray-900">{{ $scheduledEvents ?? 0 }}</span>
                            </span>
                        </div>
                    </div>

                    @if(request('search'))
                    <div class="mt-3 flex items-center gap-2">
                        <span class="text-sm text-gray-600">Showing results for "<strong>{{ request('search')
                                }}</strong>"</span>
                        <a href="{{ route('admin.reports.event-detail') }}"
                            class="text-sm text-violet-600 hover:text-violet-800 underline">Clear</a>
                    </div>
                    @endif
                </div>

                {{-- Event Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Event</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Customer</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-700">Date</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-4 text-right font-semibold text-gray-700">Total Amount</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($events as $e)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Event Name & Icon --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-violet-400 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $e->name }}</div>
                                            <div class="text-xs text-gray-500">ID: #{{ str_pad($e->id, 6, '0',
                                                STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Customer --}}
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 font-medium">{{ $e->customer->customer_name ?? 'N/A' }}
                                    </div>
                                    @if($e->customer?->phone)
                                    <div class="text-xs text-gray-500">{{ $e->customer->phone }}</div>
                                    @endif
                                </td>

                                {{-- Date --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="text-gray-900">{{ $e->event_date->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $e->event_date->diffForHumans() }}</div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    @php
                                    $statusColors = [
                                    'requested' => 'bg-amber-100 text-amber-800',
                                    'approved' => 'bg-emerald-100 text-emerald-800',
                                    'request_meeting' => 'bg-orange-100 text-orange-800',
                                    'meeting' => 'bg-blue-100 text-blue-800',
                                    'scheduled' => 'bg-indigo-100 text-indigo-800',
                                    'ongoing' => 'bg-teal-100 text-teal-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-rose-100 text-rose-800',
                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                    ];
                                    @endphp
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$e->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucwords(str_replace('_', ' ', $e->status)) }}
                                    </span>
                                </td>

                                {{-- Total Amount --}}
                                <td class="px-6 py-4 text-right">
                                    @if($e->billing)
                                    <div class="font-semibold text-gray-900">₱{{
                                        number_format($e->billing->total_amount ?? 0, 2) }}</div>
                                    @php
                                    $paid = $e->billing->payments->where('status', 'approved')->sum('amount');
                                    $balance = ($e->billing->total_amount ?? 0) - $paid;
                                    @endphp
                                    @if($balance > 0)
                                    <div class="text-xs text-red-600">Bal: ₱{{ number_format($balance, 2) }}</div>
                                    @else
                                    <div class="text-xs text-green-600">Fully Paid</div>
                                    @endif
                                    @else
                                    <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.reports.event-detail', ['event_id' => $e->id]) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-600 text-white text-sm font-medium rounded-lg hover:bg-violet-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        View Report
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">No events found</p>
                                    @if(request('search'))
                                    <p class="text-gray-400 text-sm mt-1">Try a different search term</p>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($events->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $events->links() }}
                </div>
                @endif
            </div>
            @else
            {{-- ========== EVENT REPORT CONTENT ========== --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden print-content">
                {{-- Report Header with Actions --}}
                <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-8 text-white relative">
                    <div class="no-print absolute top-4 right-4 flex gap-2">
                        <button onclick="window.print()"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print
                        </button>
                        <a href="{{ route('admin.reports.event-detail', ['event_id' => $event->id, 'export' => 'pdf']) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export PDF
                        </a>
                        <a href="{{ route('admin.reports.event-detail') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to List
                        </a>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">{{ $event->name }}</h1>
                            <p class="text-violet-100 mt-1">Event ID: #{{ str_pad($event->id, 6, '0', STR_PAD_LEFT) }} •
                                Generated {{ now()->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    @php
                    $statusColors = [
                    'requested' => 'bg-amber-400 text-amber-900',
                    'approved' => 'bg-emerald-400 text-emerald-900',
                    'request_meeting' => 'bg-orange-400 text-orange-900',
                    'meeting' => 'bg-blue-400 text-blue-900',
                    'scheduled' => 'bg-indigo-400 text-indigo-900',
                    'ongoing' => 'bg-teal-400 text-teal-900',
                    'completed' => 'bg-green-400 text-green-900',
                    'rejected' => 'bg-rose-400 text-rose-900',
                    'cancelled' => 'bg-gray-400 text-gray-900',
                    ];
                    @endphp
                    <div class="mt-4">
                        <span
                            class="inline-flex px-3 py-1 rounded-full text-sm font-bold {{ $statusColors[$event->status] ?? 'bg-gray-400 text-gray-900' }}">
                            {{ ucwords(str_replace('_', ' ', $event->status)) }}
                        </span>
                    </div>
                </div>

                {{-- Report Body --}}
                <div class="p-6">

                    {{-- Stats Cards --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div
                            class="bg-gradient-to-br from-violet-50 to-purple-50 rounded-xl p-4 border border-violet-200">
                            <div class="text-xs text-violet-600 font-medium uppercase tracking-wider">Total Amount</div>
                            <div class="text-2xl font-bold text-violet-900 mt-1">₱{{
                                number_format($stats['total_amount'], 2) }}</div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-4 border border-emerald-200">
                            <div class="text-xs text-emerald-600 font-medium uppercase tracking-wider">Total Paid</div>
                            <div class="text-2xl font-bold text-emerald-900 mt-1">₱{{
                                number_format($stats['total_paid'], 2) }}</div>
                        </div>
                        <div class="bg-gradient-to-br from-rose-50 to-red-50 rounded-xl p-4 border border-rose-200">
                            <div class="text-xs text-rose-600 font-medium uppercase tracking-wider">Balance</div>
                            <div class="text-2xl font-bold text-rose-900 mt-1">₱{{
                                number_format($stats['remaining_balance'], 2) }}</div>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                            <div class="text-xs text-blue-600 font-medium uppercase tracking-wider">Payment Progress
                            </div>
                            <div class="text-2xl font-bold text-blue-900 mt-1">{{ $stats['payment_percentage'] }}%</div>
                            <div class="w-full bg-blue-200 rounded-full h-1.5 mt-2">
                                <div class="bg-blue-600 h-1.5 rounded-full"
                                    style="width: {{ $stats['payment_percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Event Information --}}
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Event Information
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200">Event Details
                                </h4>
                                <dl class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Event Date:</dt>
                                        <dd class="font-medium text-gray-900">{{ $event->event_date->format('F d, Y') }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Venue:</dt>
                                        <dd class="font-medium text-gray-900">{{ $event->venue ?? 'TBD' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Theme:</dt>
                                        <dd class="font-medium text-gray-900">{{ $event->theme ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Package:</dt>
                                        <dd class="font-medium text-gray-900">{{ $event->package->name ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Created:</dt>
                                        <dd class="font-medium text-gray-900">{{ $event->created_at->format('M d, Y') }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200">Customer
                                    Information</h4>
                                <dl class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Name:</dt>
                                        <dd class="font-medium text-gray-900">{{ $event->customer->customer_name ??
                                            'N/A' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Email:</dt>
                                        <dd class="font-medium text-gray-900">{{ $event->customer->email ?? 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Phone:</dt>
                                        <dd class="font-medium text-gray-900">{{ $event->customer->phone ?? 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Address:</dt>
                                        <dd class="font-medium text-gray-900 text-right max-w-[200px]">{{
                                            $event->customer->address ?? 'N/A' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    {{-- Inclusions --}}
                    @if($event->inclusions->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Package Inclusions
                        </h3>
                        <div class="overflow-x-auto bg-gray-50 rounded-xl p-4">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b-2 border-gray-300">
                                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Inclusion</th>
                                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Category</th>
                                        <th class="py-3 px-4 text-right font-semibold text-gray-700">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($event->inclusions as $inclusion)
                                    <tr class="border-b border-gray-200 hover:bg-white">
                                        <td class="py-3 px-4 font-medium">{{ $inclusion->name }}</td>
                                        <td class="py-3 px-4 text-gray-600 capitalize">{{ $inclusion->category }}</td>
                                        <td class="py-3 px-4 text-right">₱{{
                                            number_format($inclusion->pivot->price_snapshot ?? $inclusion->price, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-t-2 border-gray-300 bg-amber-50">
                                        <td colspan="2" class="py-3 px-4 text-right font-bold text-gray-900">Total
                                            Inclusions:</td>
                                        <td class="py-3 px-4 text-right font-bold text-amber-700">₱{{
                                            number_format($event->inclusions->sum(fn($i) => $i->pivot->price_snapshot ??
                                            $i->price), 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Payment History --}}
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Payment History
                        </h3>
                        @if($payments->count() > 0)
                        <div class="overflow-x-auto bg-gray-50 rounded-xl p-4">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b-2 border-gray-300">
                                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Date</th>
                                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Type</th>
                                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Method</th>
                                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Reference</th>
                                        <th class="py-3 px-4 text-right font-semibold text-gray-700">Amount</th>
                                        <th class="py-3 px-4 text-center font-semibold text-gray-700">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr class="border-b border-gray-200 hover:bg-white">
                                        <td class="py-3 px-4">{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td class="py-3 px-4 capitalize">{{ str_replace('_', ' ',
                                            $payment->payment_type) }}</td>
                                        <td class="py-3 px-4 capitalize">{{ str_replace('_', ' ',
                                            $payment->payment_method) }}</td>
                                        <td class="py-3 px-4 font-mono text-xs">{{ $payment->reference_number ?? '—' }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-semibold">₱{{
                                            number_format($payment->amount, 2) }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                @if($payment->status === 'approved') bg-green-100 text-green-800
                                                @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-t-2 border-gray-300 bg-emerald-50">
                                        <td colspan="4" class="py-3 px-4 text-right font-bold text-gray-900">Total Paid
                                            (Approved):</td>
                                        <td class="py-3 px-4 text-right font-bold text-emerald-700 text-lg">₱{{
                                            number_format($stats['total_paid'], 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-8 bg-gray-50 rounded-xl">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-gray-500">No payments recorded yet.</p>
                        </div>
                        @endif
                    </div>

                    {{-- Progress Updates --}}
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Progress Updates
                        </h3>
                        @if($progressUpdates->count() > 0)
                        <div class="space-y-4">
                            @foreach($progressUpdates as $index => $progress)
                            <div class="flex gap-4">
                                {{-- Timeline Line --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-3 h-3 bg-indigo-500 rounded-full ring-4 ring-indigo-100"></div>
                                    @if(!$loop->last)
                                    <div class="w-0.5 flex-1 bg-indigo-200 my-1"></div>
                                    @endif
                                </div>
                                {{-- Content --}}
                                <div class="flex-1 bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-gray-900">{{ $progress->status }}</span>
                                        <span class="text-sm text-gray-500">{{ $progress->created_at->format('M d, Y h:i
                                            A') }}</span>
                                    </div>
                                    <p class="text-gray-600">{{ $progress->details }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 bg-gray-50 rounded-xl">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-500">No progress updates recorded.</p>
                        </div>
                        @endif
                    </div>

                    {{-- Staff Assignments --}}
                    @if($staffAssignments->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Staff Assignments
                        </h3>
                        <div class="overflow-x-auto bg-gray-50 rounded-xl p-4">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b-2 border-gray-300">
                                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Staff Member</th>
                                        <th class="py-3 px-4 text-left font-semibold text-gray-700">Role</th>
                                        <th class="py-3 px-4 text-right font-semibold text-gray-700">Pay Rate</th>
                                        <th class="py-3 px-4 text-center font-semibold text-gray-700">Work Status</th>
                                        <th class="py-3 px-4 text-center font-semibold text-gray-700">Pay Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($staffAssignments as $assignment)
                                    <tr class="border-b border-gray-200 hover:bg-white">
                                        <td class="py-3 px-4 font-medium">{{ $assignment->user->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 capitalize">{{ $assignment->pivot->assignment_role ??
                                            'Staff' }}</td>
                                        <td class="py-3 px-4 text-right">₱{{ number_format($assignment->pivot->pay_rate
                                            ?? 0, 2) }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                @if($assignment->pivot->work_status === 'finished') bg-green-100 text-green-800
                                                @elseif($assignment->pivot->work_status === 'ongoing') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($assignment->pivot->work_status ?? 'Assigned') }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                @if($assignment->pivot->pay_status === 'paid') bg-green-100 text-green-800
                                                @elseif($assignment->pivot->pay_status === 'approved') bg-blue-100 text-blue-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($assignment->pivot->pay_status ?? 'Pending') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-t-2 border-gray-300 bg-orange-50">
                                        <td colspan="2" class="py-3 px-4 text-right font-bold text-gray-900">Total
                                            Payroll:</td>
                                        <td class="py-3 px-4 text-right font-bold text-orange-700">₱{{
                                            number_format($staffAssignments->sum(fn($s) => $s->pivot->pay_rate ?? 0), 2)
                                            }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Feedback --}}
                    @if($event->feedback)
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Customer Feedback
                        </h3>
                        <div
                            class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-6 border border-yellow-200">
                            <div class="flex items-center gap-2 mb-3">
                                @for($i = 1; $i <= 5; $i++) <svg
                                    class="w-6 h-6 {{ $i <= $event->feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    @endfor
                                    <span class="ml-2 font-semibold text-gray-700">{{ $event->feedback->rating
                                        }}/5</span>
                            </div>
                            @if($event->feedback->comment)
                            <p class="text-gray-700 italic">"{{ $event->feedback->comment }}"</p>
                            @endif
                            <p class="text-sm text-gray-500 mt-3">Submitted on {{
                                $event->feedback->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Footer --}}
                    <div class="mt-8 pt-6 border-t border-gray-300 text-center text-sm text-gray-600">
                        <p>MichaelHo Events - Event Management System</p>
                        <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>