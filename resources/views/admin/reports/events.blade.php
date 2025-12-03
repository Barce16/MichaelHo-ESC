<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Events Report</h2>

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
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print-container">

            {{-- Filters --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 flex justify-between items-end no-print">
                <form method="GET" class="flex gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                        <input type="date" name="from" value="{{ $dateFrom->format('Y-m-d') }}"
                            class="rounded-lg border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                        <input type="date" name="to" value="{{ $dateTo->format('Y-m-d') }}"
                            class="rounded-lg border-gray-300">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-6 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700">
                            Generate Report
                        </button>
                    </div>
                </form>
                <div class="flex gap-2">
                    <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                    <form method="GET" class="inline">
                        <input type="hidden" name="from" value="{{ $dateFrom->format('Y-m-d') }}">
                        <input type="hidden" name="to" value="{{ $dateTo->format('Y-m-d') }}">
                        <input type="hidden" name="export" value="pdf">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Export PDF
                        </button>
                    </form>
                    <form method="GET" class="inline">
                        <input type="hidden" name="from" value="{{ $dateFrom->format('Y-m-d') }}">
                        <input type="hidden" name="to" value="{{ $dateTo->format('Y-m-d') }}">
                        <input type="hidden" name="export" value="csv">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export CSV
                        </button>
                    </form>
                </div>
            </div>

            {{-- Report Content --}}
            <div class="bg-white rounded-lg shadow-sm p-8 print-content">

                {{-- Report Header --}}
                <div class="border-b-2 border-gray-300 pb-6 mb-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-600">MH</span>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">MichaelHo Events</h1>
                                    <p class="text-sm text-gray-600">Event Management System</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <h2 class="text-xl font-bold text-gray-900">Events Report</h2>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $dateFrom->format('M d, Y') }} - {{ $dateTo->format('M d, Y') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Generated: {{ now()->format('M d, Y g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Summary Statistics --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-5 border-2 border-blue-200">
                        <div class="text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">Total Events</div>
                        <div class="text-3xl font-bold text-blue-900">{{ $stats['total_events'] }}</div>
                    </div>

                    @foreach($stats['by_status'] as $status => $count)
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">{{
                            ucfirst($status) }}</div>
                        <div class="text-3xl font-bold text-gray-900">{{ $count }}</div>
                    </div>
                    @endforeach

                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-5 border-2 border-green-200">
                        <div class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-1">Total Revenue
                        </div>
                        <div class="text-2xl font-bold text-green-900">₱{{ number_format($stats['total_revenue'], 2) }}
                        </div>
                    </div>
                </div>

                {{-- Events Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-300 bg-gray-50">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Event Name</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Customer</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Package</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($events as $event)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </td>
                                <td class="py-3 px-4 font-medium text-gray-900">{{ $event->name }}</td>
                                <td class="py-3 px-4">{{ $event->customer->customer_name }}</td>
                                <td class="py-3 px-4">{{ $event->package->name ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($event->status === 'completed') bg-green-100 text-green-800
                                        @elseif($event->status === 'scheduled') bg-blue-100 text-blue-800
                                        @elseif($event->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($event->status === 'approved') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right font-semibold">₱{{
                                    number_format($event->billing->total_amount ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-gray-500 text-lg">No events found in this period</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($events->isNotEmpty())
                        <tfoot>
                            <tr class="border-t-2 border-gray-300 bg-gray-50 font-bold">
                                <td colspan="5" class="py-4 px-4 text-right text-lg">TOTAL:</td>
                                <td class="py-4 px-4 text-right text-xl text-green-700">₱{{
                                    number_format($stats['total_revenue'], 2) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t border-gray-300 text-center text-sm text-gray-600">
                    <p class="font-medium">MichaelHo Events - Event Management System</p>
                    <p class="mt-1">Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>