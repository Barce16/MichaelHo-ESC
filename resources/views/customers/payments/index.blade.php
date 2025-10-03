<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Payment History</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Payment History Table -->
            <div class="bg-white rounded-lg shadow-sm p-6">

                <table class="min-w-full bg-white border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Event Name</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Amount</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Payment Method</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $payment->billing->event->name }}</td>
                            <td class="px-4 py-2">₱{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-4 py-2">{{ ucfirst($payment->payment_method) }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded 
                                    @if($payment->status === 'approved') bg-green-100 text-green-800 @elseif($payment->status === 'rejected') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $payment->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500">No payment history available.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>