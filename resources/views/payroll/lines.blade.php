<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Payroll Lines</h2>
            <a href="{{ route('admin.payroll.index') }}" class="px-3 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white p-4 rounded shadow-sm">
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-lg text-gray-800 border-b pb-3">
                            Event: {{ $event->name }} ({{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y')
                            }})
                        </h3>
                    </div>

                    <table class="min-w-full mt-4 text-sm">
                        <thead class="text-gray-600">
                            <tr>
                                <th class="py-2 text-left">Staff</th>
                                <th class="py-2 text-left">Rate</th>
                                <th class="py-2 text-left">Pay Status</th>
                                <th class="py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($event->staffs as $staff)
                            <tr class="border-t">
                                <td class="py-2">{{ $staff->name }}</td>
                                <td class="py-2">₱{{ number_format($staff->pivot->pay_rate, 2) }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded 
                                    @if($staff->pivot->pay_status === 'paid') bg-green-100 text-green-800 
                                    @elseif($staff->pivot->pay_status === 'approved') bg-blue-100 text-blue-800 
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($staff->pivot->pay_status) }}
                                    </span>
                                </td>

                                <td class="py-2">
                                    @if($staff->pivot->pay_status !== 'paid')
                                    <form
                                        action="{{ route('admin.payroll.markAsPaid', ['eventId' => $event->id, 'staffId' => $staff->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500">
                                            Mark as Paid
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-green-600 font-semibold">Paid</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>