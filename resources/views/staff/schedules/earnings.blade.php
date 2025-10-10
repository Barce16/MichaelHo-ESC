<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Earnings Report</h2>
            <a href="{{ route('staff.schedules.index') }}"
                class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                ← Back to Schedule
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Year Filter --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                <form method="GET" class="flex items-center gap-3">
                    <label class="text-sm font-medium text-gray-700">Year:</label>
                    <select name="year"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year==$y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit"
                        class="px-6 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-black transition">
                        View
                    </button>
                </form>
            </div>

            {{-- Summary Stats --}}
            <div class="grid md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-gray-900 to-black text-white rounded-lg shadow-sm p-6">
                    <div class="text-xs font-semibold text-gray-300 uppercase tracking-wide mb-2">Total Earned (Paid)
                    </div>
                    <div class="text-3xl font-bold">₱{{ number_format($stats['total_earned'], 2) }}</div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Pending Payment</div>
                    <div class="text-3xl font-bold text-gray-900">₱{{ number_format($stats['pending_payment'], 2) }}
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class=" text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Events</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['total_events'] }}</div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Paid Events</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['paid_events'] }}</div>
                </div>
            </div>

            {{-- Monthly Breakdown --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="font-semibold text-gray-900">Monthly Breakdown - {{ $year }}</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Month</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Events</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Paid</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Pending</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($monthlyEarnings as $month => $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">
                                    {{ $data['count'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-gray-900">
                                    ₱{{ number_format($data['total'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-green-700">
                                    ₱{{ number_format($data['paid'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-gray-500">
                                    ₱{{ number_format($data['pending'], 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">No earnings data for {{
                                    $year
                                    }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($monthlyEarnings->isNotEmpty())
                        <tfoot class="bg-gray-900 text-white font-bold">
                            <tr>
                                <td class="px-6 py-4">TOTAL</td>
                                <td class="px-6 py-4 text-center">{{ $stats['total_events'] }}</td>
                                <td class="px-6 py-4 text-right">₱{{ number_format($stats['total_earned'] +
                                    $stats['pending_payment'], 2) }}</td>
                                <td class="px-6 py-4 text-right">₱{{ number_format($stats['total_earned'], 2) }}</td>
                                <td class="px-6 py-4 text-right">₱{{ number_format($stats['pending_payment'], 2) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Detailed Earnings List --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="font-semibold text-gray-900">All Assignments - {{ $year }}</h3>
                </div>

                <div class="divide-y divide-gray-200">
                    @forelse($assignments as $event)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="font-bold text-gray-900">{{ $event->name }}</h4>
                                    @php
                                    $statusClasses = match(strtolower($event->status)) {
                                    'scheduled' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                                    'completed' => 'bg-green-100 text-green-800 border border-green-200',
                                    default => 'bg-gray-100 text-gray-800 border border-gray-200',
                                    };
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <div class="text-xs text-gray-500">Date</div>
                                        <div class="font-medium text-gray-900">{{
                                            \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Customer</div>
                                        <div class="font-medium text-gray-900">{{ $event->customer->customer_name }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Role</div>
                                        <div class="font-medium text-gray-900">{{
                                            $event->staff_assignment?->assignment_role
                                            ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Pay Rate</div>
                                        <div class="text-lg font-bold text-gray-900">₱{{
                                            number_format($event->staff_assignment?->pay_rate ?? 0, 2) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                @if($event->staff_assignment?->pay_status === 'paid')
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Paid
                                </div>
                                @else
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Pending
                                </div>
                                @endif

                                <a href="{{ route('staff.schedules.show', $event) }}"
                                    class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-lg font-medium">No assignments in {{ $year }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>