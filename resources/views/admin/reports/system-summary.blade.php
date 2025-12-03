<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">System Summary Report</h2>
            <div class="flex gap-2 no-print">
                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                <form method="GET" action="{{ route('admin.reports.system-summary') }}" class="inline">
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
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print-container">

            {{-- Report Content --}}
            <div class="bg-white rounded-lg shadow-sm p-8 print-content">

                {{-- Report Header --}}
                <div class="border-b-2 border-gray-300 pb-6 mb-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-16 h-16 bg-gray-900 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white">MH</span>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">MichaelHo Events</h1>
                                    <p class="text-sm text-gray-600">Event Management System</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <h2 class="text-xl font-bold text-gray-900">System Summary Report</h2>
                            <p class="text-sm text-gray-600">
                                Generated: {{ now()->format('M d, Y g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Customer Statistics --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Customer Statistics</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div
                            class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-6 border-2 border-purple-200">
                            <div class="text-sm text-purple-700 font-medium mb-1">Total Customers</div>
                            <div class="text-5xl font-bold text-purple-900">{{ $summary->total_customers }}</div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border-2 border-blue-200">
                            <div class="text-sm text-blue-700 font-medium mb-1">Active Customers</div>
                            <div class="text-5xl font-bold text-blue-900">{{ $summary->active_customers }}</div>
                        </div>
                    </div>
                </div>

                {{-- Event Statistics --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Event Statistics</h3>
                    <div class="grid md:grid-cols-4 gap-4">
                        <div class="bg-white border-2 border-gray-900 rounded-lg p-6">
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Events
                            </div>
                            <div class="text-4xl font-bold text-gray-900">{{ $summary->total_events }}</div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-lg p-6 border border-yellow-200">
                            <div class="text-xs font-semibold text-yellow-700 uppercase tracking-wide mb-2">Requested
                            </div>
                            <div class="text-4xl font-bold text-yellow-900">{{ $summary->requested_events }}</div>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-6 border border-blue-200">
                            <div class="text-xs font-semibold text-blue-700 uppercase tracking-wide mb-2">Approved</div>
                            <div class="text-4xl font-bold text-blue-900">{{ $summary->approved_events }}</div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-6 border border-indigo-200">
                            <div class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-2">Scheduled
                            </div>
                            <div class="text-4xl font-bold text-indigo-900">{{ $summary->scheduled_events }}</div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6 border border-green-200">
                            <div class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-2">Completed
                            </div>
                            <div class="text-4xl font-bold text-green-900">{{ $summary->completed_events }}</div>
                        </div>
                        <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-lg p-6 border border-red-200">
                            <div class="text-xs font-semibold text-red-700 uppercase tracking-wide mb-2">Rejected</div>
                            <div class="text-4xl font-bold text-red-900">{{ $summary->rejected_events }}</div>
                        </div>
                    </div>
                </div>

                {{-- Financial Statistics --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Financial Overview</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div
                            class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-8 border-2 border-green-200">
                            <div class="text-sm text-green-700 font-medium mb-2">Total Revenue (Billings)</div>
                            <div class="text-5xl font-bold text-green-900">₱{{ number_format($summary->total_revenue, 2)
                                }}</div>
                        </div>
                        <div class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-lg p-8 border-2 border-teal-200">
                            <div class="text-sm text-teal-700 font-medium mb-2">Collected Revenue (Paid)</div>
                            <div class="text-5xl font-bold text-teal-900">₱{{ number_format($summary->collected_revenue,
                                2) }}</div>
                        </div>
                    </div>

                    @php
                    $outstanding = $summary->total_revenue - $summary->collected_revenue;
                    $collectionRate = $summary->total_revenue > 0 ? ($summary->collected_revenue /
                    $summary->total_revenue) * 100 : 0;
                    @endphp

                    <div class="grid md:grid-cols-2 gap-6 mt-6">
                        <div
                            class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-lg p-8 border-2 border-orange-200">
                            <div class="text-sm text-orange-700 font-medium mb-2">Outstanding Balance</div>
                            <div class="text-5xl font-bold text-orange-900">₱{{ number_format($outstanding, 2) }}</div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-8 border-2 border-purple-200">
                            <div class="text-sm text-purple-700 font-medium mb-2">Collection Rate</div>
                            <div class="text-5xl font-bold text-purple-900">{{ number_format($collectionRate, 1) }}%
                            </div>
                        </div>
                    </div>
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