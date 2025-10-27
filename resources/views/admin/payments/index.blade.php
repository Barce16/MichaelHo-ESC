<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Payment Management</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filters --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <form method="GET" class="flex gap-3 items-end">
                    {{-- Status Filter --}}
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>

                    {{-- Payment Type Filter --}}
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Payment Type</label>
                        <select name="payment_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">All Types</option>
                            <option value="introductory" {{ request('payment_type')=='introductory' ? 'selected' : ''
                                }}>Introductory</option>
                            <option value="downpayment" {{ request('payment_type')=='downpayment' ? 'selected' : '' }}>
                                Downpayment</option>
                            <option value="balance" {{ request('payment_type')=='balance' ? 'selected' : '' }}>Balance
                            </option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-800 transition text-sm">
                            Filter
                        </button>
                        @if(request('status') || request('payment_type'))
                        <a href="{{ route('admin.payments.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                            Clear
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-4 gap-4">
                @php
                $allPayments = \App\Models\Payment::count();
                $pending = \App\Models\Payment::where('status', 'pending')->count();
                $approved = \App\Models\Payment::where('status', 'approved')->count();
                $rejected = \App\Models\Payment::where('status', 'rejected')->count();
                @endphp

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-xs text-gray-500 mb-1">Total</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $allPayments }}</div>
                </div>

                <div class="bg-amber-50 rounded-lg shadow-sm border border-amber-200 p-4">
                    <div class="text-xs text-amber-700 mb-1">Pending</div>
                    <div class="text-2xl font-bold text-amber-700">{{ $pending }}</div>
                </div>

                <div class="bg-emerald-50 rounded-lg shadow-sm border border-emerald-200 p-4">
                    <div class="text-xs text-emerald-700 mb-1">Approved</div>
                    <div class="text-2xl font-bold text-emerald-700">{{ $approved }}</div>
                </div>

                <div class="bg-rose-50 rounded-lg shadow-sm border border-rose-200 p-4">
                    <div class="text-xs text-rose-700 mb-1">Rejected</div>
                    <div class="text-2xl font-bold text-rose-700">{{ $rejected }}</div>
                </div>
            </div>

            {{-- Payments Table --}}
            @if($payments->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Payments</h3>
                <p class="text-gray-500">No payments match your filters</p>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Event /
                                    Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Method
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($payments as $payment)
                            @php
                            $event = $payment->billing->event ?? $payment->event;
                            $customer = $event->customer;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Event / Customer --}}
                                <td class="px-6 py-4">
                                    <div>
                                        <a href="{{ route('admin.events.show', $event) }}"
                                            class="text-sm font-semibold text-gray-900 hover:text-blue-600">
                                            {{ $event->name }}
                                        </a>
                                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $customer->customer_name }}
                                        </div>
                                    </div>
                                </td>

                                {{-- Type --}}
                                <td class="px-6 py-4">
                                    @php
                                    $typeBadge = match($payment->payment_type) {
                                    'introductory' => 'bg-orange-100 text-orange-700',
                                    'downpayment' => 'bg-violet-100 text-violet-700',
                                    'balance' => 'bg-emerald-100 text-emerald-700',
                                    default => 'bg-gray-100 text-gray-700',
                                    };
                                    @endphp
                                    <span
                                        class="inline-block px-2.5 py-1 rounded-full text-xs font-medium {{ $typeBadge }}">
                                        {{ ucfirst($payment->payment_type) }}
                                    </span>
                                </td>

                                {{-- Amount --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">₱{{ number_format($payment->amount, 2)
                                        }}</div>
                                </td>

                                {{-- Method --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ',
                                        $payment->payment_method)) }}</div>
                                    @if($payment->reference_number)
                                    <div class="text-xs text-gray-400">Ref: {{ $payment->reference_number }}</div>
                                    @endif
                                </td>

                                {{-- Date --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">{{ $payment->payment_date?->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">{{ $payment->created_at->diffForHumans() }}</div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @php
                                    $statusBadge = match($payment->status) {
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-rose-100 text-rose-700',
                                    default => 'bg-gray-100 text-gray-700',
                                    };
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusBadge }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.payments.show', $payment) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-slate-700 bg-white border border-gray-200 rounded-lg hover:bg-slate-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if($payments->hasPages())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-4">
                {{ $payments->links() }}
            </div>
            @endif
            @endif

        </div>
    </div>
</x-app-layout>