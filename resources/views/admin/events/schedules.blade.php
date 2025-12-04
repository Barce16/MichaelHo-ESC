<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.events.show', $event) }}"
                    class="inline-flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">Manage Schedules</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $event->name }} - {{ $event->event_date->format('M d, Y')
                        }}</p>
                </div>
            </div>

            @php
            $statusColors = [
            'scheduled' => 'bg-violet-50 text-violet-700 border-violet-200',
            'ongoing' => 'bg-teal-50 text-teal-700 border-teal-200',
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            ];
            $badge = $statusColors[$event->status] ?? 'bg-slate-50 text-slate-700 border-slate-200';
            @endphp
            <span
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border {{ $badge }}">
                <span class="w-2 h-2 rounded-full bg-current"></span>
                {{ $event->status_label }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-rose-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            {{-- Progress Summary --}}
            @php
            $scheduledCount = $event->schedules?->filter(fn($s) => $s->scheduled_date)->count() ?? 0;
            $totalCount = $event->inclusions->count();
            $assignedCount = $event->schedules?->filter(fn($s) => $s->staff_id)->count() ?? 0;
            $completedCount = $event->schedules?->filter(fn($s) => $s->proof_image)->count() ?? 0;
            $progress = $totalCount > 0 ? round(($scheduledCount / $totalCount) * 100) : 0;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalCount }}</p>
                            <p class="text-xs text-gray-500">Total Inclusions</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $scheduledCount }}</p>
                            <p class="text-xs text-gray-500">Scheduled</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $assignedCount }}</p>
                            <p class="text-xs text-gray-500">Staff Assigned</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $completedCount }}</p>
                            <p class="text-xs text-gray-500">Completed (Proof)</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Schedule Progress</span>
                    <span class="text-sm font-bold text-amber-600">{{ $progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2.5 rounded-full transition-all duration-500"
                        style="width: {{ $progress }}%"></div>
                </div>
            </div>

            {{-- Schedules Form --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Inclusion Schedules</h3>
                                <p class="text-sm text-white/80">Set dates, times, and assign staff to each inclusion
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.events.saveSchedules', $event) }}">
                    @csrf

                    {{-- Table Header --}}
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-3">
                        <div
                            class="grid grid-cols-12 gap-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            <div class="col-span-3">Inclusion</div>
                            <div class="col-span-1">Date</div>
                            <div class="col-span-1">Time</div>
                            <div class="col-span-2">Remarks</div>
                            <div class="col-span-2">Assigned Staff</div>
                            <div class="col-span-1">Contact</div>
                            <div class="col-span-1">Venue</div>
                            <div class="col-span-1 text-center">Status</div>
                        </div>
                    </div>

                    {{-- Table Body --}}
                    <div class="divide-y divide-gray-100">
                        @forelse($event->inclusions as $index => $inclusion)
                        @php
                        $schedule = $event->schedules?->where('inclusion_id', $inclusion->id)->first();
                        $hasSchedule = $schedule && $schedule->scheduled_date;
                        $assignedStaff = $schedule ? $event->staffs->firstWhere('id', $schedule->staff_id) : null;
                        $rowBg = $hasSchedule ? 'bg-emerald-50/30' : 'bg-white';
                        @endphp
                        <div class="px-6 py-4 {{ $rowBg }} hover:bg-gray-50/50 transition" x-data="{ 
                                selectedStaffId: '{{ $schedule?->staff_id ?? '' }}',
                                selectedStaffName: '{{ addslashes($assignedStaff?->name ?? '') }}',
                                selectedStaffAvatar: '{{ $assignedStaff?->avatar_url ?? '' }}',
                                open: false
                            }">
                            <input type="hidden" name="schedules[{{ $index }}][inclusion_id]"
                                value="{{ $inclusion->id }}">
                            @if($schedule)
                            <input type="hidden" name="schedules[{{ $index }}][schedule_id]"
                                value="{{ $schedule->id }}">
                            <input type="hidden" name="schedules[{{ $index }}][original_staff_id]"
                                value="{{ $schedule->staff_id }}">
                            <input type="hidden" name="schedules[{{ $index }}][original_date]"
                                value="{{ $schedule->scheduled_date?->format('Y-m-d') }}">
                            <input type="hidden" name="schedules[{{ $index }}][was_notified]"
                                value="{{ $schedule->notified_at ? '1' : '0' }}">
                            @endif
                            <input type="hidden" name="schedules[{{ $index }}][staff_id]" :value="selectedStaffId">

                            <div class="grid grid-cols-12 gap-4 items-center">
                                {{-- Inclusion Info --}}
                                <div class="col-span-3 flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-200">
                                        @if($inclusion->image)
                                        <img src="{{ Storage::url($inclusion->image) }}" alt="{{ $inclusion->name }}"
                                            class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 truncate"
                                            title="{{ $inclusion->name }}">{{ $inclusion->name }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            @if($inclusion->category)
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium rounded-full bg-violet-100 text-violet-700">
                                                {{ $inclusion->category->value ?? $inclusion->category }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Date --}}
                                <div class="col-span-1">
                                    <input type="date" name="schedules[{{ $index }}][scheduled_date]"
                                        value="{{ $schedule?->scheduled_date?->format('Y-m-d') }}"
                                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                </div>

                                {{-- Time --}}
                                <div class="col-span-1">
                                    <input type="time" name="schedules[{{ $index }}][scheduled_time]"
                                        value="{{ $schedule?->scheduled_time ? \Carbon\Carbon::parse($schedule->scheduled_time)->format('H:i') : '' }}"
                                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                </div>

                                {{-- Remarks --}}
                                <div class="col-span-2">
                                    <input type="text" name="schedules[{{ $index }}][remarks]"
                                        value="{{ $schedule?->remarks }}" placeholder="Add notes..."
                                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                </div>

                                {{-- Assign Staff (Custom Dropdown) --}}
                                <div class="col-span-2 relative">
                                    <button type="button" @click="open = !open" @click.away="open = false"
                                        class="w-full flex items-center gap-2 px-2 py-2 text-sm border border-gray-300 rounded-lg hover:border-amber-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-white text-left">
                                        <template x-if="selectedStaffId">
                                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                                <img :src="selectedStaffAvatar"
                                                    class="w-6 h-6 rounded-full object-cover flex-shrink-0">
                                                <span class="truncate text-gray-900" x-text="selectedStaffName"></span>
                                            </div>
                                        </template>
                                        <template x-if="!selectedStaffId">
                                            <span class="text-gray-400 flex-1">Select staff...</span>
                                        </template>
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    {{-- Staff Dropdown --}}
                                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute z-50 mt-1 w-64 bg-white rounded-lg shadow-xl border border-gray-200 py-1 max-h-64 overflow-y-auto">

                                        {{-- Clear Selection --}}
                                        <button type="button"
                                            @click="selectedStaffId = ''; selectedStaffName = ''; selectedStaffAvatar = ''; open = false"
                                            class="w-full px-3 py-2 text-left text-sm text-gray-500 hover:bg-gray-50 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            No staff assigned
                                        </button>

                                        <div class="border-t border-gray-100 my-1"></div>

                                        @forelse($event->staffs as $staff)
                                        <button type="button"
                                            @click="selectedStaffId = '{{ $staff->id }}'; selectedStaffName = '{{ addslashes($staff->name) }}'; selectedStaffAvatar = '{{ $staff->avatar_url }}'; open = false"
                                            class="w-full px-3 py-2 text-left text-sm hover:bg-amber-50 flex items-center gap-3"
                                            :class="selectedStaffId == '{{ $staff->id }}' ? 'bg-amber-50' : ''">
                                            <img src="{{ $staff->avatar_url }}"
                                                class="w-8 h-8 rounded-full object-cover flex-shrink-0 border-2 border-white shadow-sm">
                                            <div class="min-w-0 flex-1">
                                                <div class="font-medium text-gray-900 truncate">{{ $staff->name }}</div>
                                                @if($staff->pivot->assignment_role)
                                                <div class="text-xs text-amber-600">{{ $staff->pivot->assignment_role }}
                                                </div>
                                                @endif
                                            </div>
                                            <svg x-show="selectedStaffId == '{{ $staff->id }}'"
                                                class="w-4 h-4 text-amber-600 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        @empty
                                        <div class="px-3 py-4 text-center text-gray-500 text-sm">
                                            <p>No staff assigned to event</p>
                                            <a href="{{ route('admin.events.assignStaffPage', $event) }}"
                                                class="text-amber-600 hover:underline text-xs mt-1 inline-block">Assign
                                                staff first →</a>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Contact Number --}}
                                <div class="col-span-1">
                                    <input type="text" name="schedules[{{ $index }}][contact_number]"
                                        value="{{ $schedule?->contact_number }}" placeholder="Phone"
                                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                </div>

                                {{-- Venue --}}
                                <div class="col-span-1">
                                    <input type="text" name="schedules[{{ $index }}][venue]"
                                        value="{{ $schedule?->venue }}" placeholder="Location"
                                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                </div>

                                {{-- Status / Proof --}}
                                <div class="col-span-1 text-center">
                                    @if($schedule && $schedule->proof_image)
                                    <button type="button"
                                        onclick="openProofModal('{{ asset('storage/' . $schedule->proof_image) }}', '{{ addslashes($inclusion->name) }}')"
                                        class="inline-flex items-center gap-1 px-2 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-100 rounded-lg hover:bg-emerald-200 transition"
                                        title="Uploaded {{ $schedule->proof_uploaded_at?->format('M d, Y g:i A') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Done
                                    </button>
                                    @elseif($hasSchedule && $schedule->staff_id)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1.5 text-xs font-medium text-amber-700 bg-amber-100 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Pending
                                    </span>
                                    @elseif($hasSchedule)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Set
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1.5 text-xs font-medium text-gray-500 bg-gray-100 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Not Set
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="font-medium">No inclusions in this event</p>
                            <p class="text-sm text-gray-400 mt-1">Add inclusions to the event first</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Form Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                {{ $completedCount }} completed
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                {{ $scheduledCount }} scheduled
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                {{ $assignedCount }} assigned
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.events.show', $event) }}"
                                class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Save All Schedules
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- Proof Image Modal --}}
    <div id="proofModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeProofModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden">
                <div class="bg-gray-100 px-4 py-3 border-b flex items-center justify-between">
                    <h6 id="proofModalTitle" class="font-semibold text-gray-900">Proof Image</h6>
                    <button onclick="closeProofModal()" class="p-1 rounded-lg hover:bg-gray-200 transition">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 bg-gray-50">
                    <img id="proofModalImage" src="" alt="Proof" class="w-full h-auto rounded-lg"
                        style="max-height: 70vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <script>
        function openProofModal(imageSrc, title) {
            document.getElementById('proofModalImage').src = imageSrc;
            document.getElementById('proofModalTitle').textContent = 'Proof: ' + title;
            document.getElementById('proofModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeProofModal() {
            document.getElementById('proofModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProofModal();
            }
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>