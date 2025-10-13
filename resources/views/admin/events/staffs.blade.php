<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                Staff for Event: {{ $event->name }}
            </h2>

            <!-- Go Back Button -->
            <a href="{{ route('admin.events.show', $event) }}"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-opacity-50 transition duration-200">
                Go Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Add Staff Form -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="font-semibold text-lg text-gray-800">Add Staff to Event</h3>

                <form method="POST" action="{{ route('admin.events.assignStaff', $event) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="staff" class="block text-sm font-medium text-gray-700">Select Staff</label>
                        <select id="staff" name="staff_id"
                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="">Select Staff</option>
                            @foreach($availableStaff as $staff)
                            <option value="{{ $staff->id }}" data-name="{{ $staff->name }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Role and Pay Rate Input Fields -->
                    <div id="roleAndRate" class="hidden space-y-4">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <input type="text" name="role" id="role"
                                class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                        </div>

                        <div>
                            <label for="pay_rate" class="block text-sm font-medium text-gray-700">Pay Rate</label>
                            <input type="number" name="pay_rate" id="pay_rate"
                                class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="px-6 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50 transition duration-200">
                            Add Staff
                        </button>
                    </div>
                </form>
            </div>

            <!-- Staff List -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="font-semibold text-lg mb-6 text-gray-800">Staff List</h3>

                @if($staffs->isEmpty())
                <p class="text-gray-500">No staff assigned yet.</p>
                @else
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Role</th>
                            <th class="px-4 py-2 text-left">Pay Rate</th>
                            <th class="px-4 py-2 text-left">Pay Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staffs as $staff)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $staff->name }}</td>
                            <td class="px-4 py-2">{{ $staff->pivot->assignment_role }}</td>
                            <td class="px-4 py-2">₱ {{ number_format($staff->pivot->pay_rate, 2) }}</td>
                            <td class="px-4 py-2">{{ ucfirst($staff->pivot->pay_status) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

        </div>
    </div>

    <script>
        // Show the Role and Pay Rate inputs when a staff member is selected
        const staffSelect = document.getElementById('staff');
        const roleAndRateDiv = document.getElementById('roleAndRate');

        staffSelect.addEventListener('change', function() {
            if (this.value) {
                roleAndRateDiv.classList.remove('hidden');
            } else {
                roleAndRateDiv.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>