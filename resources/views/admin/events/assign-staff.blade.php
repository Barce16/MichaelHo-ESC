<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                Assign Staff - {{ $event->name }}
            </h2>
            <a href="{{ route('admin.events.show', $event) }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Back to Event
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="staffAssignment()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <ul class="text-red-700 text-sm list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid md:grid-cols-2 gap-6">
                {{-- Available Staff --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Available Staff
                    </h3>

                    @if($availableStaff->isEmpty())
                    <p class="text-gray-500 text-sm">No available staff to assign</p>
                    @else
                    <form method="POST" action="{{ route('admin.events.assignStaff', $event) }}">
                        @csrf
                        <div class="space-y-3 mb-4">
                            @foreach($availableStaff as $staff)
                            <div class="border rounded-lg p-3"
                                :class="selectedStaff.has({{ $staff->id }}) ? 'border-purple-300 bg-purple-50' : 'border-gray-200'">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" value="{{ $staff->id }}"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                        @change="toggleStaff({{ $staff->id }})">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $staff->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $staff->role_type ?? 'Staff' }}
                                            @if($staff->rate)
                                            • ₱{{ number_format($staff->rate, 2) }}
                                            @if($staff->rate_type)
                                            <span class="text-gray-400">({{ str_replace('_', ' ', $staff->rate_type)
                                                }})</span>
                                            @endif
                                            @endif
                                        </div>
                                    </div>
                                </label>

                                {{-- Role and Rate inputs (shown when checked) --}}
                                <template x-if="selectedStaff.has({{ $staff->id }})">
                                    <div class="mt-3 space-y-2 pl-8">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Assignment
                                                Role
                                                @if($staff->role_type)
                                                <span class="text-gray-500 font-normal">- Usual: {{ $staff->role_type
                                                    }}</span>
                                                @endif
                                            </label>
                                            <input type="text" name="staff[{{ $staff->id }}][role]"
                                                value="{{ old('staff.'.$staff->id.'.role', $staff->role_type ?? '') }}"
                                                placeholder="{{ $staff->role_type ?? 'e.g., Event Coordinator, Photographer' }}"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Pay Rate
                                                (₱)
                                                @if($staff->rate)
                                                <span class="text-gray-500 font-normal">
                                                    - Default: ₱{{ number_format($staff->rate, 2) }}
                                                    @if($staff->rate_type)
                                                    ({{ ucfirst(str_replace('_', ' ', $staff->rate_type)) }})
                                                    @endif
                                                </span>
                                                @endif
                                            </label>
                                            <input type="number" step="0.01" min="0"
                                                name="staff[{{ $staff->id }}][rate]"
                                                value="{{ old('staff.'.$staff->id.'.rate', $staff->rate ?? '') }}"
                                                placeholder="{{ $staff->rate ? number_format($staff->rate, 2) : '0.00' }}"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                                required>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            @endforeach
                        </div>
                        <button type="submit"
                            class="w-full px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="selectedStaff.size === 0">
                            Assign Selected Staff (<span x-text="selectedStaff.size"></span>)
                        </button>
                    </form>
                    @endif
                </div>

                {{-- Assigned Staff --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Assigned Staff ({{ $assignedStaff->count() }})
                    </h3>

                    @if($assignedStaff->isEmpty())
                    <p class="text-gray-500 text-sm">No staff assigned yet</p>
                    @else
                    <div class="space-y-3">
                        @foreach($assignedStaff as $staff)
                        <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ substr($staff->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $staff->name }}</div>
                                        <div class="text-xs text-gray-600">{{ $staff->role_type ?? 'Staff' }}</div>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('admin.events.assignStaff', $event) }}"
                                    class="inline">
                                    @csrf
                                    <input type="hidden" name="removed_staff_ids[]" value="{{ $staff->id }}">
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Remove
                                    </button>
                                </form>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="bg-white rounded px-3 py-2">
                                    <div class="text-xs text-gray-500 mb-1">Role</div>
                                    <div class="font-medium text-gray-900">{{ $staff->pivot->assignment_role ?? '-' }}
                                    </div>
                                </div>
                                <div class="bg-white rounded px-3 py-2">
                                    <div class="text-xs text-gray-500 mb-1">Pay Rate</div>
                                    <div class="font-medium text-green-700">₱{{ number_format($staff->pivot->pay_rate ??
                                        0, 2) }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Total Payroll --}}
                        <div class="border-t-2 border-green-300 pt-3 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-700">Total Payroll:</span>
                                <span class="text-xl font-bold text-green-700">
                                    ₱{{ number_format($assignedStaff->sum('pivot.pay_rate'), 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Event Info Summary --}}
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg shadow-sm p-6 mt-6">
                <h4 class="font-semibold text-purple-900 mb-3">Event Information</h4>
                <div class="grid md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <div class="text-purple-700 font-medium">Event Date</div>
                        <div class="text-purple-900">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-purple-700 font-medium">Customer</div>
                        <div class="text-purple-900">{{ $event->customer->customer_name }}</div>
                    </div>
                    <div>
                        <div class="text-purple-700 font-medium">Package</div>
                        <div class="text-purple-900">{{ $event->package->name }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function staffAssignment() {
            return {
                selectedStaff: new Set(),
                
                toggleStaff(id) {
                    if (this.selectedStaff.has(id)) {
                        this.selectedStaff.delete(id);
                    } else {
                        this.selectedStaff.add(id);
                    }
                }
            }
        }
    </script>
</x-app-layout>