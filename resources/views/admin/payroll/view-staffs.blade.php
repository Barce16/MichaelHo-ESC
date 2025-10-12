<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Payroll Details</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $event->name }}</p>
            </div>
            <a href="{{ route('admin.payroll.index') }}" class="text-sm text-gray-600 hover:text-slate-700 font-medium">
                ← Back to Payroll
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-white border-l-4 border-gray-800 rounded-lg p-4 shadow-sm">
                <p class="text-gray-800 font-medium">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Event Information --}}
            <div class="bg-gradient-to-r from-slate-700 to-gray-800 rounded-lg shadow-sm p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Event Information</h3>
                <div class="grid md:grid-cols-4 gap-6">
                    <div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Event Date</div>
                        <div class="font-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Customer</div>
                        <div class="font-medium">{{ $event->customer->customer_name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Package</div>
                        <div class="font-medium">{{ $event->package->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</div>
                        <div class="font-medium">{{ ucfirst($event->status) }}</div>
                    </div>
                </div>
            </div>

            {{-- Payroll Summary --}}
            <div class="grid md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm border-2 border-slate-700 p-6">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Payroll</div>
                    <div class="text-3xl font-bold text-slate-700">₱{{ number_format($totalPayroll, 2) }}</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Paid Amount</div>
                    <div class="text-3xl font-bold text-slate-700">₱{{ number_format($paidAmount, 2) }}</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Pending Amount</div>
                    <div class="text-3xl font-bold text-gray-700">₱{{ number_format($pendingAmount, 2) }}</div>
                </div>
            </div>

            {{-- Staff List --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-slate-700">Staff Assignments ({{ $event->staffs->count() }})
                    </h3>
                </div>

                <div class="divide-y divide-gray-200">
                    @forelse($event->staffs as $staff)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 flex-1">
                                <div
                                    class="w-14 h-14 bg-slate-700 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                    {{ substr($staff->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-slate-700 text-lg">{{ $staff->name }}</div>
                                    <div class="text-sm text-gray-600 mt-1">{{ $staff->pivot->assignment_role }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $staff->position ?? 'Staff' }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="text-right">
                                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Pay Rate</div>
                                    <div class="text-2xl font-bold text-slate-700">₱{{
                                        number_format($staff->pivot->pay_rate, 2) }}</div>
                                </div>

                                <div class="flex flex-col gap-2">
                                    @if($staff->pivot->pay_status === 'paid')
                                    <div class="flex items-center gap-2 px-4 py-2 bg-slate-700 text-white rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="font-semibold">Paid</span>
                                    </div>
                                    <form method="POST"
                                        action="{{ route('admin.payroll.markAsPending', [$event, $staff]) }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full px-4 py-2 border-2 border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                                            Mark as Pending
                                        </button>
                                    </form>
                                    @else
                                    <div
                                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-center font-semibold">
                                        Pending
                                    </div>
                                    <form method="POST"
                                        action="{{ route('admin.payroll.markAsPaid', [$event, $staff]) }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full px-4 py-2 bg-slate-700 text-white font-medium rounded-lg hover:bg-gray-800 transition">
                                            Mark as Paid
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center text-gray-400">
                        <p>No staff assigned to this event</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>