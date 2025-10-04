<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Staff List for Event: {{ $event->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <table class="min-w-full text-sm">
                <thead class="text-gray-600">
                    <tr>
                        <th class="py-2 text-left">Staff Name</th>
                        <th class="py-2 text-left">Role</th>
                        <th class="py-2 text-left">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($event->staffs as $staff)
                    <tr class="border-t">
                        <td class="py-2">{{ $staff->name }}</td>
                        <td class="py-2">{{ $staff->pivot->assignment_role }}</td>
                        <td class="py-2">₱{{ number_format($staff->pivot->pay_rate, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>