<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Events with Remaining Balances</h2>
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

            {{-- Export Buttons --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 flex justify-end items-center gap-2 no-print">
                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                <a href="{{ route('admin.reports.remaining-balances', ['export' => 'pdf']) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.reports.remaining-balances', ['export' => 'csv']) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export CSV
                </a>
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
                            <h2 class="text-xl font-bold text-gray-900">Remaining Balances Report</h2>
                            <p class="text-sm text-gray-600">
                                Generated: {{ now()->format('M d, Y g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Summary Statistics --}}
                <div class="grid md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-lg p-6 border-2 border-red-200">
                        <div class="text-sm text-red-700 font-medium mb-1">Events with Balance</div>
                        <div class="text-4xl font-bold text-red-900">{{ $stats['total_events'] }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-lg p-6 border-2 border-orange-200">
                        <div class="text-sm text-orange-700 font-medium mb-1">Total Outstanding</div>
                        <div class="text-3xl font-bold text-orange-900">₱{{ number_format($stats['total_outstanding'],
                            2) }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border-2 border-blue-200">
                        <div class="text-sm text-blue-700 font-medium mb-1">Package Balance</div>
                        <div class="text-3xl font-bold text-blue-900">₱{{ number_format($stats['package_outstanding'],
                            2) }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-6 border-2 border-purple-200">
                        <div class="text-sm text-purple-700 font-medium mb-1">Unpaid Expenses</div>
                        <div class="text-3xl font-bold text-purple-900">₱{{
                            number_format($stats['expenses_outstanding'], 2) }}</div>
                    </div>
                </div>

                {{-- Events Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-left py-3 font-semibold text-gray-700">Event Name</th>
                                <th class="text-left py-3 font-semibold text-gray-700">Event Date</th>
                                <th class="text-left py-3 font-semibold text-gray-700">Customer</th>
                                <th class="text-right py-3 font-semibold text-gray-700">Package</th>
                                <th class="text-right py-3 font-semibold text-gray-700">Expenses</th>
                                <th class="text-right py-3 font-semibold text-gray-700">Paid</th>
                                <th class="text-right py-3 font-semibold text-gray-700">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3">
                                    <div class="font-medium">{{ $event->name }}</div>
                                    @if($event->unpaid_expenses_count > 0)
                                    <span class="text-xs text-orange-600">{{ $event->unpaid_expenses_count }} unpaid
                                        expense(s)</span>
                                    @endif
                                </td>
                                <td class="py-3">{{ $event->event_date->format('M d, Y') }}</td>
                                <td class="py-3">
                                    <div>{{ $event->customer->customer_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $event->customer->phone ??
                                        $event->customer->email }}</div>
                                </td>
                                <td class="py-3 text-right">₱{{ number_format($event->package_total, 2) }}</td>
                                <td class="py-3 text-right">
                                    @if($event->expenses_total > 0)
                                    <span class="text-orange-700">₱{{ number_format($event->expenses_total, 2) }}</span>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right text-green-700">₱{{ number_format($event->total_paid, 2) }}
                                </td>
                                <td class="py-3 text-right">
                                    <div class="font-semibold text-red-700">₱{{ number_format($event->remaining_balance,
                                        2) }}</div>
                                    @if($event->package_balance > 0 && $event->unpaid_expenses > 0)
                                    <div class="text-xs text-gray-500">
                                        Pkg: ₱{{ number_format($event->package_balance, 2) }} |
                                        Exp: ₱{{ number_format($event->unpaid_expenses, 2) }}
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-500">No events with remaining balances
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-300 font-bold bg-red-50">
                                <td colspan="6" class="py-4 text-right text-lg">TOTAL OUTSTANDING BALANCE:</td>
                                <td class="py-4 text-right text-2xl text-red-700">₱{{
                                    number_format($stats['total_outstanding'], 2) }}</td>
                            </tr>
                            @if($stats['expenses_outstanding'] > 0)
                            <tr class="bg-gray-50 text-sm">
                                <td colspan="6" class="py-2 text-right text-gray-600">Package Balance:</td>
                                <td class="py-2 text-right text-gray-700">₱{{
                                    number_format($stats['package_outstanding'], 2) }}</td>
                            </tr>
                            <tr class="bg-gray-50 text-sm">
                                <td colspan="6" class="py-2 text-right text-gray-600">Unpaid Expenses:</td>
                                <td class="py-2 text-right text-orange-700">₱{{
                                    number_format($stats['expenses_outstanding'], 2) }}</td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t border-gray-300 text-center text-sm text-gray-600">
                    <p>MichaelHo Events - Event Management System</p>
                    <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>