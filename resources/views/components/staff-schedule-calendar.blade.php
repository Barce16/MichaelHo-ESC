@props(['assignments' => []])

@php
// Convert assignments to array format
$assignmentsCollection = collect($assignments);

// Find the first assignment date to set initial calendar month
$firstAssignmentDate = $assignmentsCollection
->pluck('event_date')
->map(function($date) {
if ($date instanceof \DateTime || $date instanceof \Carbon\Carbon) {
return $date->format('Y-m-d');
}
return $date;
})
->filter()
->sort()
->first();

// Default to current month if no assignments
$initialYear = now()->year;
$initialMonth = now()->month - 1; // JavaScript months are 0-indexed

if ($firstAssignmentDate) {
$firstDate = \Carbon\Carbon::parse($firstAssignmentDate);
$initialYear = $firstDate->year;
$initialMonth = $firstDate->month - 1; // JavaScript months are 0-indexed
}

$assignmentsArray = $assignmentsCollection->map(function($event) {
if (!is_object($event)) return null;

$eventDate = $event->event_date;
if ($eventDate) {
if ($eventDate instanceof \DateTime || $eventDate instanceof \Carbon\Carbon) {
$eventDate = $eventDate->format('Y-m-d');
} else {
$eventDate = substr($eventDate, 0, 10);
}
}

$pivot = $event->staff_assignment;

return [
'id' => $event->id ?? 0,
'name' => $event->name ?? 'Untitled Event',
'event_date' => $eventDate ?? now()->format('Y-m-d'),
'status' => $event->status ?? 'scheduled',
'venue' => $event->venue ?? '',
'customer_name' => $event->customer->customer_name ?? 'N/A',
'assignment_role' => $pivot->assignment_role ?? 'Staff',
'pay_rate' => $pivot->pay_rate ?? 0,
'pay_status' => $pivot->pay_status ?? 'pending',
'work_status' => $pivot->work_status ?? 'pending',
];
})->filter()->values()->toArray();
@endphp

<div x-data="staffCalendar()" x-init="initCalendar(@js($assignmentsArray), {{ $initialYear }}, {{ $initialMonth }})"
    class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-4 py-3">
        <div class="flex items-center justify-between">
            <button type="button" @click="previousMonth()"
                class="p-1.5 rounded-lg hover:bg-white/20 transition text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="text-center">
                <h3 class="text-lg font-bold text-white" x-text="currentMonthDisplay"></h3>
                <div class="flex items-center justify-center gap-3 mt-1">
                    <span class="text-xs text-white/70">
                        <span x-text="assignmentsInMonth"></span> assignment<span
                            x-show="assignmentsInMonth !== 1">s</span>
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- Today Button --}}
                {{-- <button type="button" @click="goToToday()"
                    class="px-2 py-1 text-xs rounded-lg hover:bg-white/20 transition text-white/80 hover:text-white"
                    title="Go to Today">
                    Today
                </button> --}}
                {{-- View All Assignments Button --}}
                <button type="button" @click="showAssignmentsList = true"
                    class="p-1.5 rounded-lg hover:bg-white/20 transition text-white/80 hover:text-white flex items-center gap-2 pe-3"
                    title="View All Assignments">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span class="text-sm">List View</span>
                </button>
                {{-- Next Month Button --}}
                <button type="button" @click="nextMonth()"
                    class="p-1.5 rounded-lg hover:bg-white/20 transition text-white/80 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Calendar Grid --}}
    <div class="p-3">
        {{-- Weekday Headers --}}
        <div class="grid grid-cols-7 gap-1 mb-2">
            <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']">
                <div class="text-center text-xs font-semibold text-gray-500 py-1" x-text="day"></div>
            </template>
        </div>

        {{-- Days Grid --}}
        <div class="grid grid-cols-7 gap-1">
            <template x-for="(day, index) in calendarDays" :key="index">
                <div>
                    {{-- Day Cell --}}
                    <div x-show="day.inCurrentMonth" @click="day.assignments.length > 0 && showDayModal(day)" :class="{
                            'ring-2 ring-gray-900 ring-offset-1': day.isToday,
                            'cursor-pointer hover:shadow-md hover:scale-[1.02]': day.assignments.length > 0,
                            'bg-emerald-50 border-emerald-400': day.hasFinished,
                            'bg-amber-50 border-amber-400': !day.hasFinished && day.hasOngoing,
                            'bg-indigo-50 border-indigo-400': !day.hasFinished && !day.hasOngoing && day.hasPending,
                            'border-gray-200': day.assignments.length === 0
                        }" class="h-[80px] p-1.5 rounded-lg border transition-all relative overflow-hidden">

                        {{-- Day Number with Badge --}}
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold" :class="day.isToday ? 'text-gray-900' : 'text-gray-600'"
                                x-text="day.day"></span>

                            <span x-show="day.assignments.length > 0"
                                class="w-5 h-5 flex items-center justify-center text-[10px] font-bold text-white rounded-full"
                                :class="{
                                    'bg-emerald-500': day.hasFinished,
                                    'bg-amber-500': !day.hasFinished && day.hasOngoing,
                                    'bg-indigo-500': !day.hasFinished && !day.hasOngoing && day.hasPending
                                }" x-text="day.assignments.length"></span>
                        </div>

                        {{-- Assignment Preview --}}
                        <template x-if="day.assignments.length > 0">
                            <div class="space-y-0.5">
                                <template x-for="(assignment, idx) in day.assignments.slice(0, 2)" :key="idx">
                                    <div class="text-[9px] px-1 py-0.5 rounded truncate" :class="{
                                            'bg-emerald-100 text-emerald-700': assignment.work_status === 'finished',
                                            'bg-amber-100 text-amber-700': assignment.work_status === 'ongoing',
                                            'bg-indigo-100 text-indigo-700': assignment.work_status === 'pending'
                                        }">
                                        <span class="font-semibold" x-text="assignment.name"></span>
                                    </div>
                                </template>
                                <div x-show="day.assignments.length > 2" class="text-[9px] text-gray-500 text-center">
                                    +<span x-text="day.assignments.length - 2"></span> more
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Empty day placeholder --}}
                    <div x-show="!day.inCurrentMonth" class="h-[80px] rounded-lg bg-gray-50"></div>
                </div>
            </template>
        </div>
    </div>

    {{-- Legend --}}
    <div class="border-t border-gray-200 px-4 py-2 bg-gray-50">
        <div class="flex items-center justify-center gap-6 text-xs">
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                <span class="text-gray-600">Pending</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <span class="text-gray-600">Ongoing</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                <span class="text-gray-600">Finished</span>
            </div>
        </div>
    </div>

    {{-- Day Detail Modal --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.self="showModal = false">

        <div class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-[80vh] overflow-hidden"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-4 py-3 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-white" x-text="modalDate"></h3>
                    <p class="text-xs text-white/70">
                        <span x-text="selectedDayAssignments.length"></span> assignment(s)
                    </p>
                </div>
                <button @click="showModal = false" class="text-white/70 hover:text-white p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Content --}}
            <div class="p-4 max-h-[60vh] overflow-y-auto space-y-3">
                <template x-for="assignment in selectedDayAssignments" :key="assignment.id">
                    <a :href="'/staff/schedules/' + assignment.id"
                        class="block p-4 rounded-lg border-2 hover:shadow-md transition" :class="{
                            'border-emerald-300 bg-emerald-50': assignment.work_status === 'finished',
                            'border-amber-300 bg-amber-50': assignment.work_status === 'ongoing',
                            'border-indigo-300 bg-indigo-50': assignment.work_status === 'pending'
                        }">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-900 truncate" x-text="assignment.name"></h4>
                                <p class="text-sm text-gray-600 mt-1" x-text="assignment.customer_name"></p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-emerald-100 text-emerald-700': assignment.work_status === 'finished',
                                            'bg-amber-100 text-amber-700': assignment.work_status === 'ongoing',
                                            'bg-indigo-100 text-indigo-700': assignment.work_status === 'pending'
                                        }">
                                        <span
                                            x-text="assignment.work_status.charAt(0).toUpperCase() + assignment.work_status.slice(1)"></span>
                                    </span>
                                    <span class="text-xs text-gray-500" x-text="assignment.assignment_role"></span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="text-lg font-bold text-gray-900">
                                    ₱<span
                                        x-text="Number(assignment.pay_rate).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                </div>
                                <div class="text-xs mt-1"
                                    :class="assignment.pay_status === 'paid' ? 'text-emerald-600 font-semibold' : 'text-gray-500'">
                                    <span
                                        x-text="assignment.pay_status === 'paid' ? '✓ Paid' : 'Pending Payment'"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </div>

    {{-- All Assignments List Modal --}}
    <div x-show="showAssignmentsList" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        @click.self="showAssignmentsList = false">

        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full max-h-[85vh] overflow-hidden"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-4 py-3 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-white">All Assignments</h3>
                    <p class="text-xs text-white/70">
                        <span x-text="assignments.length"></span> total assignment(s)
                    </p>
                </div>
                <button @click="showAssignmentsList = false" class="text-white/70 hover:text-white p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Content --}}
            <div class="max-h-[70vh] overflow-y-auto">
                <template x-if="sortedAssignments.length > 0">
                    <div class="divide-y divide-gray-100">
                        <template x-for="assignment in sortedAssignments" :key="assignment.id">
                            <a :href="'/staff/schedules/' + assignment.id"
                                class="flex items-center gap-4 p-4 hover:bg-gray-50 transition">
                                {{-- Date Badge --}}
                                <div class="flex-shrink-0 w-14 h-14 rounded-lg flex flex-col items-center justify-center text-white"
                                    :class="{
                                        'bg-emerald-500': assignment.work_status === 'finished',
                                        'bg-amber-500': assignment.work_status === 'ongoing',
                                        'bg-indigo-500': assignment.work_status === 'pending'
                                    }">
                                    <div class="text-lg font-bold" x-text="new Date(assignment.event_date).getDate()">
                                    </div>
                                    <div class="text-[10px] uppercase"
                                        x-text="new Date(assignment.event_date).toLocaleDateString('en-US', {month: 'short'})">
                                    </div>
                                </div>

                                {{-- Assignment Info --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 truncate" x-text="assignment.name"></h4>
                                    <p class="text-sm text-gray-500 truncate" x-text="assignment.customer_name"></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs px-2 py-0.5 rounded-full" :class="{
                                                'bg-emerald-100 text-emerald-700': assignment.work_status === 'finished',
                                                'bg-amber-100 text-amber-700': assignment.work_status === 'ongoing',
                                                'bg-indigo-100 text-indigo-700': assignment.work_status === 'pending'
                                            }"
                                            x-text="assignment.work_status.charAt(0).toUpperCase() + assignment.work_status.slice(1)">
                                        </span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500" x-text="assignment.assignment_role"></span>
                                    </div>
                                </div>

                                {{-- Pay Info --}}
                                <div class="text-right flex-shrink-0">
                                    <div class="font-bold text-gray-900">
                                        ₱<span
                                            x-text="Number(assignment.pay_rate).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                    </div>
                                    <div class="text-xs"
                                        :class="assignment.pay_status === 'paid' ? 'text-emerald-600' : 'text-gray-400'">
                                        <span x-text="assignment.pay_status === 'paid' ? '✓ Paid' : 'Pending'"></span>
                                    </div>
                                </div>

                                {{-- Arrow --}}
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </template>
                    </div>
                </template>

                <template x-if="sortedAssignments.length === 0">
                    <div class="p-8 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="font-medium">No assignments yet</p>
                        <p class="text-sm mt-1">Your event assignments will appear here</p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
    function staffCalendar() {
    return {
        currentDate: new Date(),
        currentMonthDisplay: '',
        calendarDays: [],
        assignments: [],
        showModal: false,
        showAssignmentsList: false,
        selectedDayAssignments: [],
        modalDate: '',
        assignmentsInMonth: 0,

        get sortedAssignments() {
            return [...this.assignments].sort((a, b) => {
                const dateA = new Date(a.event_date);
                const dateB = new Date(b.event_date);
                return dateA - dateB;
            });
        },

        initCalendar(assignmentsData, initialYear, initialMonth) {
            this.assignments = Array.isArray(assignmentsData) ? assignmentsData : [];
            
            // Set initial date to first assignment's month
            this.currentDate = new Date(initialYear, initialMonth, 1);
            
            this.calculateStats();
            this.renderCalendar();
        },

        calculateStats() {
            const currentYear = this.currentDate.getFullYear();
            const currentMonth = this.currentDate.getMonth();

            this.assignmentsInMonth = this.assignments.filter(a => {
                const eventDate = new Date(a.event_date);
                return eventDate.getFullYear() === currentYear && eventDate.getMonth() === currentMonth;
            }).length;
        },

        previousMonth() {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.calculateStats();
            this.renderCalendar();
        },

        nextMonth() {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.calculateStats();
            this.renderCalendar();
        },

        goToToday() {
            this.currentDate = new Date();
            this.calculateStats();
            this.renderCalendar();
        },

        renderCalendar() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();

            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'];
            this.currentMonthDisplay = `${monthNames[month]} ${year}`;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            this.calendarDays = [];

            // Fill empty days
            for (let i = 0; i < firstDay; i++) {
                this.calendarDays.push({
                    day: '',
                    date: '',
                    inCurrentMonth: false,
                    isToday: false,
                    assignments: [],
                    hasPending: false,
                    hasOngoing: false,
                    hasFinished: false
                });
            }

            // Month days
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                const dateString = this.formatDate(date);

                const dayAssignments = this.assignments.filter(a => a.event_date === dateString);
                
                const hasPending = dayAssignments.some(a => a.work_status === 'pending');
                const hasOngoing = dayAssignments.some(a => a.work_status === 'ongoing');
                const hasFinished = dayAssignments.some(a => a.work_status === 'finished');

                this.calendarDays.push({
                    day: day,
                    date: dateString,
                    inCurrentMonth: true,
                    isToday: date.toDateString() === today.toDateString(),
                    assignments: dayAssignments,
                    hasPending: hasPending,
                    hasOngoing: hasOngoing,
                    hasFinished: hasFinished
                });
            }
        },

        showDayModal(day) {
            this.selectedDayAssignments = day.assignments;
            const date = new Date(day.date);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            this.modalDate = date.toLocaleDateString('en-US', options);
            this.showModal = true;
        },

        formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
    }
}
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>