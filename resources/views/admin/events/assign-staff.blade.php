<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Assign Staff to Event: {{ $event->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Available Staffs --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold mb-3">Assign Staff</h3>

                <form method="POST" action="{{ route('admin.events.assignStaff', $event) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($availableStaff as $staff)
                        <div class="flex items-center space-x-4">
                            <input type="checkbox" name="staff_ids[]" value="{{ $staff->id }}" class="form-checkbox">
                            <div class="flex-1">
                                <div>{{ $staff->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $staff->role_type }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end pt-4">
                        <button class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-600">
                            Assign Staff
                        </button>
                    </div>
                </form>
            </div>

            {{-- Assigned Staffs --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold mb-3">Assigned Staffs</h3>

                <form method="POST" action="{{ route('admin.events.assignStaff', $event) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($assignedStaff as $staff)
                        <div class="flex items-center space-x-4">
                            <button type="button" class="text-red-600 hover:text-red-800"
                                @click="removeStaff({{ $staff->id }})">
                                Remove
                            </button>
                            <div class="flex-1">
                                <div>{{ $staff->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $staff->role_type }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="removed_staff_ids[]" :value="removedStaffIds">

                    <div class="flex justify-end pt-4">
                        <button class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-600">
                            Remove Staff
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>