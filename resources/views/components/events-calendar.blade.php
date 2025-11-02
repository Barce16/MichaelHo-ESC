@props(['events' => [], 'userType' => 'customer'])

@php
// Safely convert events to array, handling Paginator, Collection, or array
if (is_object($events) && method_exists($events, 'items')) {
// It's a Paginator
$eventsCollection = collect($events->items());
} else {
// It's a Collection or array
$eventsCollection = collect($events);
}

$eventsArray = $eventsCollection->map(function($event) use ($userType) {
// Safety check - make sure $event is an object
if (!is_object($event)) {
return null;
}

// Convert event_date to Y-m-d format (strip time portion)
$eventDate = $event->event_date;
if ($eventDate) {
// Handle both DateTime objects and strings
if ($eventDate instanceof \DateTime || $eventDate instanceof \Carbon\Carbon) {
$eventDate = $eventDate->format('Y-m-d');
} else {
// Strip time from ISO string like "2026-02-19T16:00:00.000000Z"
// Extract just the date part (first 10 characters)
$eventDate = substr($eventDate, 0, 10);
}
}

$data = [
'id' => $event->id ?? 0,
'name' => $event->name ?? 'Untitled Event',
'event_date' => $eventDate ?? now()->format('Y-m-d'),
'status' => $event->status ?? 'requested',
'venue' => $event->venue ?? '',
];

// Add customer name for admin view
if ($userType === 'admin') {
$data['customer_name'] = isset($event->customer->user) ? $event->customer->user->name : 'N/A';
}

return $data;
})->filter()->values()->toArray(); // Filter out null values
@endphp

<div x-data="eventsCalendar()" x-init="initCalendar(@js($eventsArray))"
    class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden max-w-5xl mx-auto">

    {{-- Compact Header --}}
    <div class="bg-gradient-to-r from-slate-500 to-gray-600 p-4">
        <div class="flex items-center justify-between">
            <button type="button" @click="previousMonth()" class="p-2 rounded-lg hover:bg-white/20 transition">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="text-center">
                <h3 class="text-xl font-bold text-white" x-text="currentMonthDisplay"></h3>
                <p class="text-xs text-white/70 mt-0.5">
                    <span x-text="eventsInMonth"></span> event<span x-show="eventsInMonth !== 1">s</span> this month
                </p>
            </div>

            <button type="button" @click="nextMonth()" class="p-2 rounded-lg hover:bg-white/20 transition">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Calendar Body --}}
    <div class="p-4">
        {{-- Day Headers --}}
        <div class="grid grid-cols-7 gap-1 mb-2">
            <div class="text-center text-xs font-semibold text-gray-600 py-2">Sun</div>
            <div class="text-center text-xs font-semibold text-gray-600 py-2">Mon</div>
            <div class="text-center text-xs font-semibold text-gray-600 py-2">Tue</div>
            <div class="text-center text-xs font-semibold text-gray-600 py-2">Wed</div>
            <div class="text-center text-xs font-semibold text-gray-600 py-2">Thu</div>
            <div class="text-center text-xs font-semibold text-gray-600 py-2">Fri</div>
            <div class="text-center text-xs font-semibold text-gray-600 py-2">Sat</div>
        </div>

        {{-- Calendar Grid --}}
        <div class="grid grid-cols-7 gap-1">
            <template x-for="(day, index) in calendarDays" :key="index">
                <div>
                    {{-- Day with events --}}
                    <button type="button" x-show="day.inCurrentMonth && day.events.length > 0"
                        @click="showDayEvents(day)" :class="{
                            'ring-2 ring-violet-400 ring-offset-1': day.isToday,
                            'bg-amber-50 hover:bg-amber-100 border-amber-200': day.events[0].status === 'requested',
                            'bg-blue-50 hover:bg-blue-100 border-blue-200': day.events[0].status === 'meeting' || day.events[0].status === 'request_meeting',
                            'bg-sky-50 hover:bg-sky-100 border-sky-200': day.events[0].status === 'approved',
                            'bg-violet-50 hover:bg-violet-100 border-violet-200': day.events[0].status === 'scheduled',
                            'bg-emerald-50 hover:bg-emerald-100 border-emerald-200': day.events[0].status === 'completed',
                            'bg-gray-50 hover:bg-gray-100 border-gray-200': day.events[0].status === 'cancelled' || day.events[0].status === 'rejected'
                        }"
                        class="w-full h-20 flex flex-col items-start justify-start p-2 rounded-lg border transition-all cursor-pointer">

                        {{-- Day number --}}
                        <span class="text-xs font-bold mb-1" :class="{
                                'text-amber-700': day.events[0].status === 'requested',
                                'text-blue-700': day.events[0].status === 'meeting' || day.events[0].status === 'request_meeting',
                                'text-sky-700': day.events[0].status === 'approved',
                                'text-violet-700': day.events[0].status === 'scheduled',
                                'text-emerald-700': day.events[0].status === 'completed',
                                'text-gray-600': day.events[0].status === 'cancelled' || day.events[0].status === 'rejected'
                            }" x-text="day.day"></span>

                        {{-- Event name (truncated) --}}
                        <p class="text-[10px] font-medium leading-tight line-clamp-2 w-full" :class="{
                                'text-amber-700': day.events[0].status === 'requested',
                                'text-blue-700': day.events[0].status === 'meeting' || day.events[0].status === 'request_meeting',
                                'text-sky-700': day.events[0].status === 'approved',
                                'text-violet-700': day.events[0].status === 'scheduled',
                                'text-emerald-700': day.events[0].status === 'completed',
                                'text-gray-600': day.events[0].status === 'cancelled' || day.events[0].status === 'rejected'
                            }" x-text="day.events[0].name"></p>

                        {{-- Multiple events indicator --}}
                        <span x-show="day.events.length > 1" class="text-[9px] font-semibold mt-auto" :class="{
                                'text-amber-600': day.events[0].status === 'requested',
                                'text-blue-600': day.events[0].status === 'meeting' || day.events[0].status === 'request_meeting',
                                'text-sky-600': day.events[0].status === 'approved',
                                'text-violet-600': day.events[0].status === 'scheduled',
                                'text-emerald-600': day.events[0].status === 'completed',
                                'text-gray-500': day.events[0].status === 'cancelled' || day.events[0].status === 'rejected'
                            }" x-text="'+' + (day.events.length - 1) + ' more'"></span>
                    </button>

                    {{-- Day without events --}}
                    <div x-show="day.inCurrentMonth && day.events.length === 0"
                        :class="{'ring-2 ring-violet-400 ring-offset-1': day.isToday}"
                        class="w-full h-20 flex items-start justify-start p-2 rounded-lg border border-gray-100 bg-white">
                        <span class="text-xs font-medium"
                            :class="day.isToday ? 'text-violet-700 font-bold' : 'text-gray-500'"
                            x-text="day.day"></span>
                    </div>

                    {{-- Empty cell for days outside current month --}}
                    <div x-show="!day.inCurrentMonth" class="w-full h-20"></div>
                </div>
            </template>
        </div>

        {{-- Compact Legend --}}
        <div class="mt-4 pt-3 border-t border-gray-200">
            <div class="flex flex-wrap items-center gap-3 text-xs">
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded bg-amber-200 border border-amber-300"></div>
                    <span class="text-gray-600">Requested</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded bg-blue-200 border border-blue-300"></div>
                    <span class="text-gray-600">Meeting</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded bg-sky-200 border border-sky-300"></div>
                    <span class="text-gray-600">Approved</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded bg-violet-200 border border-violet-300"></div>
                    <span class="text-gray-600">Scheduled</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded bg-emerald-200 border border-emerald-300"></div>
                    <span class="text-gray-600">Completed</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded bg-gray-200 border border-gray-300"></div>
                    <span class="text-gray-600">Cancelled</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Event Details Modal --}}
    <div x-show="showModal" x-cloak @click.self="showModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100">

        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden" @click.stop
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-violet-500 to-purple-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white" x-text="modalDate"></h3>
                        <p class="text-sm text-white/80 mt-0.5">
                            <span x-text="selectedDayEvents.length"></span> event<span
                                x-show="selectedDayEvents.length !== 1">s</span>
                        </p>
                    </div>
                    <button type="button" @click="showModal = false"
                        class="p-2 rounded-lg hover:bg-white/20 transition">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="p-4 overflow-y-auto max-h-[calc(80vh-120px)]">
                <div class="space-y-3">
                    <template x-for="event in selectedDayEvents" :key="event.id">
                        <a :href="'/{{ strtolower($userType) }}/events/' + event.id"
                            class="block bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition border border-gray-200">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="w-2 h-2 rounded-full flex-shrink-0" :class="{
                                                'bg-amber-500': event.status === 'requested',
                                                'bg-blue-500': event.status === 'meeting' || event.status === 'request_meeting',
                                                'bg-sky-500': event.status === 'approved',
                                                'bg-violet-500': event.status === 'scheduled',
                                                'bg-emerald-500': event.status === 'completed',
                                                'bg-gray-400': event.status === 'cancelled' || event.status === 'rejected'
                                            }"></span>
                                        <h4 class="font-bold text-gray-900 truncate" x-text="event.name"></h4>
                                    </div>

                                    <div class="space-y-1 text-sm text-gray-600">
                                        @if($userType === 'admin')
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span x-text="event.customer_name"></span>
                                        </div>
                                        @endif
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            <span x-text="event.venue || 'Venue TBD'"></span>
                                        </div>
                                    </div>
                                </div>

                                <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap" :class="{
                                        'bg-amber-100 text-amber-700': event.status === 'requested',
                                        'bg-blue-100 text-blue-700': event.status === 'meeting' || event.status === 'request_meeting',
                                        'bg-sky-100 text-sky-700': event.status === 'approved',
                                        'bg-violet-100 text-violet-700': event.status === 'scheduled',
                                        'bg-emerald-100 text-emerald-700': event.status === 'completed',
                                        'bg-gray-100 text-gray-700': event.status === 'cancelled' || event.status === 'rejected'
                                    }"
                                    x-text="event.status.replace('_', ' ').charAt(0).toUpperCase() + event.status.replace('_', ' ').slice(1)"></span>
                            </div>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function eventsCalendar() {
        return {
            currentDate: new Date(),
            currentMonthDisplay: '',
            calendarDays: [],
            events: [],
            showModal: false,
            selectedDayEvents: [],
            modalDate: '',
            eventsInMonth: 0,
            stats: {
                upcoming: 0,
                thisMonth: 0,
                pending: 0
            },

            initCalendar(eventsData) {
                this.events = Array.isArray(eventsData) ? eventsData : [];
                this.calculateStats();
                this.renderCalendar();
            },

            calculateStats() {
                const now = new Date();
                now.setHours(0, 0, 0, 0);
                const currentYear = this.currentDate.getFullYear();
                const currentMonth = this.currentDate.getMonth();

                this.stats.upcoming = this.events.filter(e => {
                    const eventDate = new Date(e.event_date);
                    return eventDate >= now && ['approved', 'meeting', 'scheduled'].includes(e.status);
                }).length;

                this.stats.thisMonth = this.events.filter(e => {
                    const eventDate = new Date(e.event_date);
                    return eventDate.getFullYear() === currentYear &&
                        eventDate.getMonth() === currentMonth;
                }).length;

                this.stats.pending = this.events.filter(e => e.status === 'requested').length;

                this.eventsInMonth = this.stats.thisMonth;
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

            renderCalendar() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();

                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];
                this.currentMonthDisplay = `${monthNames[month]} ${year}`;

                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                this.calendarDays = [];

                // Empty cells before month starts
                for (let i = 0; i < firstDay; i++) {
                    this.calendarDays.push({
                        day: '',
                        date: '',
                        inCurrentMonth: false,
                        isToday: false,
                        events: []
                    });
                }

                // Days of month
                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(year, month, day);
                    const dateString = this.formatDate(date);

                    // Get events for this day
                    const dayEvents = this.events.filter(e => e.event_date === dateString);

                    this.calendarDays.push({
                        day: day,
                        date: dateString,
                        inCurrentMonth: true,
                        isToday: date.toDateString() === today.toDateString(),
                        events: dayEvents
                    });
                }
            },

            showDayEvents(day) {
                this.selectedDayEvents = day.events;
                const date = new Date(day.date);
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
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