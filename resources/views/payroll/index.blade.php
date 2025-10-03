<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Payroll Summary</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <form method="GET" class="bg-white p-4 rounded shadow-sm grid grid-cols-1 md:grid-cols-6 gap-3">
                <input type="date" name="from" value="{{ $from }}" class="border rounded px-3 py-2">
                <input type="date" name="to" value="{{ $to }}" class="border rounded px-3 py-2">
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All status</option>
                    @foreach(['pending','approved','paid'] as $s)
                    <option value="{{ $s }}" @selected($status===$s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <div class="md:col-span-3 flex justify-end">
                    <a href="{{ route('admin.payroll.index') }}" class="px-3 py-2 border rounded mr-2">Reset</a>
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Apply</button>
                </div>
            </form>

            <div class="bg-white p-4 rounded shadow-sm">
                @foreach($groupedEvents as $eventId => $staffs)
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-lg text-gray-800 border-b pb-3">
                            Event: {{ $staffs->first()->event_name }} ({{
                            \Carbon\Carbon::parse($staffs->first()->event_date)->format('F j, Y') }})
                        </h3>
                        <a href="{{ route('admin.payroll.lines', ['eventId' => $eventId]) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500">
                            View Lines
                        </a>
                    </div>

                    <table class="min-w-full mt-4 text-sm">
                        <thead class="text-gray-600">
                            <tr>
                                <th class="py-2 text-left">Staff</th>
                                <th class="py-2 text-left">Role</th>
                                <th class="py-2 text-left">Rate</th>
                                <th class="py-2 text-left">Pay Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffs as $staff)
                            <tr class="border-t">
                                <td class="py-2">{{ $staff->staff_name }}</td>
                                <td class="py-2">{{ $staff->assignment_role }}</td>
                                <td class="py-2">₱{{ number_format($staff->pay_rate, 2) }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded 
                                    @if($staff->pay_status === 'paid') bg-green-100 text-green-800 
                                    @elseif($staff->pay_status === 'approved') bg-blue-100 text-blue-800 
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($staff->pay_status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>