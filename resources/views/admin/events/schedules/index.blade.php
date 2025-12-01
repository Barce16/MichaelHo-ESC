<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Inclusion Schedules</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $event->name }}</p>
            </div>
            <a href="{{ route('admin.events.show', $event) }}"
                class="text-sm text-gray-600 hover:text-slate-700 font-medium">
                ← Back to Event
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="{
        showAddModal: false,
        showEditModal: false,
        showBulkModal: false,
        editingSchedule: null,
        
        openEdit(schedule) {
            this.editingSchedule = schedule;
            this.showEditModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-lg p-4">
                <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 rounded-lg p-4">
                <p class="text-rose-800 font-medium">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Event Info Card --}}
            <div class="bg-gradient-to-r from-slate-700 to-gray-800 rounded-xl shadow-sm p-6 text-white">
                <div class="grid md:grid-cols-4 gap-6">
                    <div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Event Date</div>
                        <div class="font-medium">{{ $event->event_date->format('M d, Y') }}</div>
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
                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Inclusions</div>
                        <div class="font-medium">{{ $event->inclusions->count() }}</div>
                    </div>
                </div>
            </div>

            {{-- Schedule Summary --}}
            @php
            $completedCount = $schedules->filter(fn($s) => $s->isCompleted())->count();
            $todayCount = $schedules->filter(fn($s) => $s->isToday())->count();
            $overdueCount = $schedules->filter(fn($s) => $s->isOverdue())->count();
            $upcomingCount = $schedules->filter(fn($s) => $s->isUpcoming())->count();
            @endphp
            <div class="grid md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-emerald-200 p-5">
                    <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-2">Completed</div>
                    <div class="text-3xl font-bold text-emerald-600">{{ $completedCount }}</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-5">
                    <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">Today</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $todayCount }}</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-rose-200 p-5">
                    <div class="text-xs font-semibold text-rose-600 uppercase tracking-wide mb-2">Overdue</div>
                    <div class="text-3xl font-bold text-rose-600">{{ $overdueCount }}</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-amber-200 p-5">
                    <div class="text-xs font-semibold text-amber-600 uppercase tracking-wide mb-2">Upcoming</div>
                    <div class="text-3xl font-bold text-amber-600">{{ $upcomingCount }}</div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-3">
                @if($unscheduledInclusions->count() > 0)
                <button @click="showAddModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-violet-600 text-white font-medium rounded-lg hover:bg-violet-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Schedule
                </button>

                <button @click="showBulkModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Schedule All ({{ $unscheduledInclusions->count() }})
                </button>
                @endif

                @if($schedules->filter(fn($s) => !$s->isCompleted())->count() > 0)
                <form method="POST" action="{{ route('admin.events.schedules.mark-all-completed', $event) }}"
                    class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Mark all schedules as completed?')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Mark All Completed
                    </button>
                </form>
                @endif
            </div>

            {{-- Schedules List --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-slate-700">Schedules ({{ $schedules->count() }})</h3>
                </div>

                @if($schedules->isEmpty())
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 mb-4">No schedules created yet</p>
                    @if($unscheduledInclusions->count() > 0)
                    <button @click="showAddModal = true" class="text-violet-600 font-medium hover:underline">
                        Create your first schedule →
                    </button>
                    @endif
                </div>
                @else
                <div class="divide-y divide-gray-200">
                    @foreach($schedules as $schedule)
                    @php
                    $statusColors = [
                    'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'today' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'overdue' => 'bg-rose-100 text-rose-700 border-rose-200',
                    'upcoming' => 'bg-amber-100 text-amber-700 border-amber-200',
                    ];
                    $badgeClass = $statusColors[$schedule->status] ?? 'bg-gray-100 text-gray-700';
                    $rowBg = $schedule->isOverdue() ? 'bg-rose-50' : ($schedule->isToday() ? 'bg-blue-50' : '');
                    @endphp
                    <div class="p-6 hover:bg-gray-50 transition {{ $rowBg }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4 flex-1">
                                {{-- Completion Checkbox --}}
                                <form method="POST"
                                    action="{{ route('admin.events.schedules.toggle-complete', [$event, $schedule]) }}"
                                    class="mt-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition hover:scale-110 {{ $schedule->isCompleted() ? 'bg-emerald-500 border-emerald-500' : 'border-gray-300 hover:border-emerald-400' }}">
                                        @if($schedule->isCompleted())
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        @endif
                                    </button>
                                </form>

                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4
                                            class="font-semibold text-gray-900 {{ $schedule->isCompleted() ? 'line-through text-gray-500' : '' }}">
                                            {{ $schedule->inclusion->name }}
                                        </h4>
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $badgeClass }}">
                                            {{ $schedule->status_label }}
                                        </span>
                                        @if($schedule->inclusion->category)
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium rounded-full bg-violet-50 text-violet-700">
                                            {{ $schedule->inclusion->category }}
                                        </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ $schedule->scheduled_date->format('M d, Y') }}</span>
                                        </div>
                                        @if($schedule->scheduled_time)
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A')
                                                }}</span>
                                        </div>
                                        @endif
                                    </div>

                                    @if($schedule->remarks)
                                    <div class="text-sm text-gray-600 bg-gray-100 rounded-lg px-3 py-2 mt-3">
                                        {{ $schedule->remarks }}
                                    </div>
                                    @endif

                                    @if($schedule->completed_at)
                                    <div class="text-xs text-emerald-600 mt-2">
                                        ✓ Completed {{ $schedule->completed_at->format('M d, Y g:i A') }}
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                {{-- Edit Button --}}
                                <button @click="openEdit({
                                    id: {{ $schedule->id }},
                                    inclusion_name: '{{ $schedule->inclusion->name }}',
                                    scheduled_date: '{{ $schedule->scheduled_date->format('Y-m-d') }}',
                                    scheduled_time: '{{ $schedule->scheduled_time ? \Carbon\Carbon::parse($schedule->scheduled_time)->format('H:i') : '' }}',
                                    remarks: `{{ addslashes($schedule->remarks ?? '') }}`
                                })" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                {{-- Delete Button --}}
                                <form method="POST"
                                    action="{{ route('admin.events.schedules.destroy', [$event, $schedule]) }}"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this schedule?')"
                                        class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Unscheduled Inclusions --}}
            @if($unscheduledInclusions->count() > 0)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                <h4 class="font-semibold text-amber-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Unscheduled Inclusions ({{ $unscheduledInclusions->count() }})
                </h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($unscheduledInclusions as $inclusion)
                    <span class="px-3 py-1.5 bg-white border border-amber-200 rounded-lg text-sm text-amber-800">
                        {{ $inclusion->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Add Schedule Modal --}}
        <div x-show="showAddModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            x-transition>
            <div @click.outside="showAddModal = false" class="bg-white rounded-xl shadow-2xl max-w-lg w-full">
                <div class="bg-violet-600 text-white px-6 py-4 rounded-t-xl">
                    <h3 class="text-lg font-bold">Add Schedule</h3>
                    <p class="text-violet-200 text-sm">Schedule an inclusion for this event</p>
                </div>

                <form method="POST" action="{{ route('admin.events.schedules.store', $event) }}" class="p-6 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Inclusion *</label>
                        <select name="inclusion_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                            <option value="">Select an inclusion</option>
                            @foreach($unscheduledInclusions as $inclusion)
                            <option value="{{ $inclusion->id }}">{{ $inclusion->name }} ({{ $inclusion->category }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date *</label>
                            <input type="date" name="scheduled_date" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time (Optional)</label>
                            <input type="time" name="scheduled_time"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Remarks (Optional)</label>
                        <textarea name="remarks" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent"
                            placeholder="Any notes about this schedule..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" @click="showAddModal = false"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-violet-600 text-white font-medium rounded-lg hover:bg-violet-700 transition">
                            Create Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Schedule Modal --}}
        <div x-show="showEditModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            x-transition>
            <div @click.outside="showEditModal = false" class="bg-white rounded-xl shadow-2xl max-w-lg w-full">
                <div class="bg-blue-600 text-white px-6 py-4 rounded-t-xl">
                    <h3 class="text-lg font-bold">Edit Schedule</h3>
                    <p class="text-blue-200 text-sm" x-text="editingSchedule?.inclusion_name"></p>
                </div>

                <form :action="`{{ url('admin/events/' . $event->id . '/schedules') }}/${editingSchedule?.id}`"
                    method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date *</label>
                            <input type="date" name="scheduled_date" required x-model="editingSchedule.scheduled_date"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time (Optional)</label>
                            <input type="time" name="scheduled_time" x-model="editingSchedule.scheduled_time"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Remarks (Optional)</label>
                        <textarea name="remarks" rows="3" x-model="editingSchedule.remarks"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Any notes about this schedule..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                            Update Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bulk Schedule Modal --}}
        <div x-show="showBulkModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            x-transition>
            <div @click.outside="showBulkModal = false" class="bg-white rounded-xl shadow-2xl max-w-lg w-full">
                <div class="bg-blue-600 text-white px-6 py-4 rounded-t-xl">
                    <h3 class="text-lg font-bold">Schedule All Inclusions</h3>
                    <p class="text-blue-200 text-sm">Set the same date for all unscheduled inclusions</p>
                </div>

                <form method="POST" action="{{ route('admin.events.schedules.bulk', $event) }}" class="p-6 space-y-4">
                    @csrf

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-800">
                            <span class="font-semibold">{{ $unscheduledInclusions->count() }}</span> inclusion(s) will
                            be scheduled:
                        </p>
                        <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                            @foreach($unscheduledInclusions->take(5) as $inclusion)
                            <li>{{ $inclusion->name }}</li>
                            @endforeach
                            @if($unscheduledInclusions->count() > 5)
                            <li>...and {{ $unscheduledInclusions->count() - 5 }} more</li>
                            @endif
                        </ul>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date *</label>
                            <input type="date" name="scheduled_date" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time (Optional)</label>
                            <input type="time" name="scheduled_time"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Remarks (Optional)</label>
                        <textarea name="remarks" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Common remarks for all schedules..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" @click="showBulkModal = false"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                            Schedule All
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>