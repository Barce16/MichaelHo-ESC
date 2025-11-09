@props(['events' => [], 'userType' => 'customer'])

@php
// convert to array format
if (is_object($events) && method_exists($events, 'items')) {
// paginator
$eventsCollection = collect($events->items());
} else {
// collection or arr
$eventsCollection = collect($events);
}

$eventsArray = $eventsCollection->map(function($event) use ($userType) {
// check obj
if (!is_object($event)) {
return null;
}

// strip time from date
$eventDate = $event->event_date;
if ($eventDate) {
// handle datetime
if ($eventDate instanceof \DateTime || $eventDate instanceof \Carbon\Carbon) {
$eventDate = $eventDate->format('Y-m-d');
} else {
// just date part
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

// admin needs customer name
if ($userType === 'admin') {
$data['customer_name'] = isset($event->customer->user) ? $event->customer->user->name : 'N/A';
}

return $data;
})->filter()->values()->toArray(); // remove nulls
@endphp

<div x-data="eventsCalendar()" x-init="initCalendar(@js($eventsArray))"
    class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden max-w-3xl mx-auto">

    {{-- header --}}
    <div class="bg-gradient-to-r from-slate-500 to-gray-600 p-3">
        <div class="flex items-center justify-between">
            <button type="button" @click="previousMonth()" class="p-1.5 rounded-lg hover:bg-white/20 transition">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="text-center">
                <h3 class="text-lg font-bold text-white" x-text="currentMonthDisplay"></h3>
                <p class="text-[10px] text-white/70">
                    <span x-text="eventsInMonth"></span> event<span x-show="eventsInMonth !== 1">s</span> this month
                </p>
            </div>

            <button type="button" @click="nextMonth()" class="p-1.5 rounded-lg hover:bg-white/20 transition">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    {{-- calendar --}}
    <div class="p-3">
        {{-- weekdays --}}
        <div class="grid grid-cols-7 gap-1 mb-1">
            <div class="text-center text-[10px] font-semibold text-gray-600 py-1">Sun</div>
            <div class="text-center text-[10px] font-semibold text-gray-600 py-1">Mon</div>
            <div class="text-center text-[10px] font-semibold text-gray-600 py-1">Tue</div>
            <div class="text-center text-[10px] font-semibold text-gray-600 py-1">Wed</div>
            <div class="text-center text-[10px] font-semibold text-gray-600 py-1">Thu</div>
            <div class="text-center text-[10px] font-semibold text-gray-600 py-1">Fri</div>
            <div class="text-center text-[10px] font-semibold text-gray-600 py-1">Sat</div>
        </div>

        {{-- grid --}}
        <div class="grid grid-cols-7 gap-1">
            <template x-for="(day, index) in calendarDays" :key="index">
                <div>
                    {{-- has events --}}
                    <button type="button" x-show="day.inCurrentMonth && day.events.length > 0"
                        @click="showDayEvents(day)" :class="{
                            'ring-1 ring-violet-400 ring-offset-1': day.isToday,
                            'bg-amber-50 hover:bg-amber-100 border-amber-200': day.events[0].status === 'requested',
                            'bg-blue-50 hover:bg-blue-100 border-blue-200': day.events[0].status === 'meeting' || day.events[0].status === 'request_meeting',
                            'bg-sky-50 hover:bg-sky-100 border-sky-200': day.events[0].status === 'approved',
                            'bg-violet-50 hover:bg-violet-100 border-violet-200': day.events[0].status === 'scheduled',
                            'bg-emerald-50 hover:bg-emerald-100 border-emerald-200': day.events[0].status === 'completed',
                            'bg-gray-50 hover:bg-gray-100 border-gray-200': day.events[0].status === 'cancelled' || day.events[0].status === 'rejected'
                        }"
                        class="w-full h-14 flex flex-col items-start justify-start p-1.5 rounded-md border transition-all cursor-pointer">

                        {{-- day num --}}
                        <span class="text-[10px] font-bold" :class="{
                                'text-amber-700': day.events[0].status === 'requested',
                                'text-blue-700': day.events[0].status === 'meeting' || day.events[0].status === 'request_meeting',
                                'text-sky-700': day.events[0].status === 'approved',
                                'text-violet-700': day.events[0].status === 'scheduled',
                                'text-emerald-700': day.events[0].status === 'completed',
                                'text-gray-600': day.events[0].status === 'cancelled' || day.events[0].status === 'rejected'
                            }" x-text="day.day"></span>

                        {{-- event title --}}
                        <p class="text-[9px] font-medium leading-tight line-clamp-1 w-full" :class="{
                                'text-amber-700': day.events[0].status === 'requested',
                                'text-blue-700': day.events[0].status === 'meeting' || day.events[0].status === 'request_meeting',
                                'text-sky-700': day.events[0].status === 'approved',
                                'text-violet-700': day.events[0].status === 'scheduled',
                                'text-emerald-700': day.events[0].status === 'completed',
                                'text-gray-600': day.events[0].status === 'cancelled' || day.events[0].status === 'rejected'
                            }" x-text="day.events[0].name"></p>

                        {{-- more badge --}}
                        <span x-show="day.events.length > 1" class="text-[8px] font-semibold mt-auto" :class="{
                                'text-amber-600': day.events[0].status === 'requested',
                                'text-blue-600': day.events[0].status === 'meeting' || day.events[0].status === 'request_meeting',
                                'text-sky-600': day.events[0].status === 'approved',
                                'text-violet-600': day.events[0].status === 'scheduled',
                                'text-emerald-600': day.events[0].status === 'completed',
                                'text-gray-500': day.events[0].status === 'cancelled' || day.events[0].status === 'rejected'
                            }" x-text="'+' + (day.events.length - 1)"></span>
                    </button>

                    {{-- empty day --}}
                    <div x-show="day.inCurrentMonth && day.events.length === 0"
                        :class="{'ring-1 ring-violet-400 ring-offset-1': day.isToday}"
                        class="w-full h-14 flex items-start justify-start p-1.5 rounded-md border border-gray-100 bg-white">
                        <span class="text-[10px] font-medium"
                            :class="day.isToday ? 'text-violet-700 font-bold' : 'text-gray-500'"
                            x-text="day.day"></span>
                    </div>

                    {{-- outside month --}}
                    <div x-show="!day.inCurrentMonth" class="w-full h-14"></div>
                </div>
            </template>
        </div>

        {{-- legend --}}
        <div class="mt-3 pt-2 border-t border-gray-200">
            <div class="flex flex-wrap items-center gap-2 text-[10px]">
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 rounded bg-amber-200 border border-amber-300"></div>
                    <span class="text-gray-600">Requested</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 rounded bg-blue-200 border border-blue-300"></div>
                    <span class="text-gray-600">Meeting</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 rounded bg-sky-200 border border-sky-300"></div>
                    <span class="text-gray-600">Approved</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 rounded bg-violet-200 border border-violet-300"></div>
                    <span class="text-gray-600">Scheduled</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 rounded bg-emerald-200 border border-emerald-300"></div>
                    <span class="text-gray-600">Completed</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 rounded bg-gray-200 border border-gray-300"></div>
                    <span class="text-gray-600">Cancelled</span>
                </div>
            </div>
        </div>
    </div>

    {{-- modal popup --}}
    <div x-show="showModal" x-cloak @click.away="showModal = false" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-center justify-center px-4 py-6 bg-black bg-opacity-50 z-50">
        <div @click.stop x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[70vh] overflow-hidden">

            <div class="bg-gradient-to-r from-slate-500 to-gray-600 px-4 py-3 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white">Events</h3>
                    <p class="text-xs text-white/80 mt-0.5" x-text="modalDate"></p>
                </div>
                <button type="button" @click="showModal = false" class="p-1.5 rounded-lg hover:bg-white/20 transition">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-4 py-3 max-h-[50vh] overflow-y-auto space-y-2.5">
                <template x-for="event in selectedDayEvents" :key="event.id">
                    <a :href="`/{{ $userType === 'admin' ? 'admin' : 'customer' }}/events/` + event.id"
                        class="block p-3 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 text-sm mb-1.5" x-text="event.name"></h4>
                                <div class="space-y-1 text-xs text-gray-600">
                                    @if($userType === 'admin')
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span x-text="event.customer_name"></span>
                                    </div>
                                    @endif
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        <span x-text="event.venue || 'Venue TBD'"></span>
                                    </div>
                                </div>
                            </div>

                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold whitespace-nowrap" :class="{
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

                // fill empty
                for (let i = 0; i < firstDay; i++) {
                    this.calendarDays.push({
                        day: '',
                        date: '',
                        inCurrentMonth: false,
                        isToday: false,
                        events: []
                    });
                }

                // month days
                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(year, month, day);
                    const dateString = this.formatDate(date);

                    // get events
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