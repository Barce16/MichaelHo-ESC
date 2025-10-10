<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Event Status Summary</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 flex justify-between items-end">
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
            <div class="bg-white rounded-lg shadow-sm p-8">

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
                            <h2 class="text-xl font-bold text-gray-900">Event Status Summary</h2>
                            <p class="text-sm text-gray-600 mt-1">
                                Period: {{ $dateFrom->format('M d, Y') }} - {{ $dateTo->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Generated: {{ now()->format('M d, Y g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Summary Statistics --}}
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-lg p-6 border-2 border-yellow-200">
                        <div class="text-sm text-yellow-700 font-medium mb-1">Total Events</div>
                        <div class="text-4xl font-bold text-yellow-900">{{ $stats['total_events'] }}</div>
                    </div>

                    @if($stats['most_common'])
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border-2 border-blue-200">
                        <div class="text-sm text-blue-700 font-medium mb-1">Most Common Status</div>
                        <div class="text-2xl font-bold text-blue-900">{{ $stats['most_common']->status_label }}</div>
                        <div class="text-sm text-blue-700 mt-1">{{ $stats['most_common']->event_count }} events</div>
                    </div>
                    @endif
                </div>

                {{-- Status Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-left py-3 font-semibold text-gray-700">Status</th>
                                <th class="text-center py-3 font-semibold text-gray-700">Event Count</th>
                                <th class="text-right py-3 font-semibold text-gray-700">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statusSummary as $status)
                            @php
                            $percentage = $stats['total_events'] > 0 ? ($status->event_count / $stats['total_events']) *
                            100 : 0;
                            $colorClasses = match(strtolower($status->status)) {
                            'requested' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-blue-100 text-blue-800',
                            'meeting' => 'bg-orange-100 text-orange-800',
                            'scheduled' => 'bg-indigo-100 text-indigo-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800',
                            };
                            @endphp
                            <tr class="border-b border-gray-200">
                                <td class="py-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colorClasses }}">
                                        {{ $status->status_label }}
                                    </span>
                                </td>
                                <td class="py-3 text-center font-bold text-gray-900 text-2xl">{{ $status->event_count }}
                                </td>
                                <td class="py-3 text-right font-semibold text-gray-700 text-lg">{{
                                    number_format($percentage, 2) }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-gray-500">No event data in this period</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-300 font-bold bg-yellow-50">
                                <td class="py-4 text-left text-lg">TOTAL</td>
                                <td class="py-4 text-center text-2xl text-yellow-700">{{ $stats['total_events'] }}</td>
                                <td class="py-4 text-right text-lg">100%</td>
                            </tr>
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