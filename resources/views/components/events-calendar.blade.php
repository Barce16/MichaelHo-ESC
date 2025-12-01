@props(['events' => [], 'schedules' => [], 'userType' => 'customer'])

@php
// Convert events to array format
if (is_object($events) && method_exists($events, 'items')) {
$eventsCollection = collect($events->items());
} else {
$eventsCollection = collect($events);
}

$eventsArray = $eventsCollection->map(function($event) use ($userType) {
if (!is_object($event)) return null;

$eventDate = $event->event_date;
if ($eventDate) {
if ($eventDate instanceof \DateTime || $eventDate instanceof \Carbon\Carbon) {
$eventDate = $eventDate->format('Y-m-d');
} else {
$eventDate = substr($eventDate, 0, 10);
}
}

$data = [
'id' => $event->id ?? 0,
'name' => $event->name ?? 'Untitled Event',
'event_date' => $eventDate ?? now()->format('Y-m-d'),
'status' => $event->status ?? 'requested',
'venue' => $event->venue ?? '',
'type' => 'event',
];

if ($userType === 'admin') {
$data['customer_name'] = isset($event->customer->user) ? $event->customer->user->name : 'N/A';
}

return $data;
})->filter()->values()->toArray();

// Convert schedules to array format with image
$schedulesCollection = collect($schedules);
$schedulesArray = $schedulesCollection->map(function($schedule) {
if (!is_object($schedule)) return null;

$scheduledDate = $schedule->scheduled_date;
if ($scheduledDate) {
if ($scheduledDate instanceof \DateTime || $scheduledDate instanceof \Carbon\Carbon) {
$scheduledDate = $scheduledDate->format('Y-m-d');
} else {
$scheduledDate = substr($scheduledDate, 0, 10);
}
}

// Get inclusion image URL
$imageUrl = null;
if ($schedule->inclusion && $schedule->inclusion->image) {
$imageUrl = Storage::url($schedule->inclusion->image);
}

return [
'id' => $schedule->id ?? 0,
'name' => $schedule->inclusion->name ?? 'Unknown',
'event_name' => $schedule->event->name ?? 'Unknown Event',
'event_id' => $schedule->event_id,
'scheduled_date' => $scheduledDate,
'scheduled_time' => $schedule->scheduled_time ? \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A') :
null,
'remarks' => $schedule->remarks ?? null,
'image' => $imageUrl,
'category' => $schedule->inclusion->category ?? null,
'type' => 'schedule',
];
})->filter()->values()->toArray();
@endphp

<div x-data="eventsCalendar()" x-init="initCalendar(@js($eventsArray), @js($schedulesArray))"
    class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-4">
        <div class="flex items-center justify-between">
            <button type="button" @click="previousMonth()"
                class="p-2 rounded-lg hover:bg-white/20 transition text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="text-center">
                <h3 class="text-xl font-bold text-white" x-text="currentMonthDisplay"></h3>
                <div class="flex items-center justify-center gap-3 mt-1">
                    <span class="text-xs text-white/70">
                        <span x-text="eventsInMonth"></span> event<span x-show="eventsInMonth !== 1">s</span>
                    </span>
                    <span class="text-white/40">•</span>
                    <span class="text-xs text-white/70">
                        <span x-text="schedulesInMonth"></span> schedule<span x-show="schedulesInMonth !== 1">s</span>
                    </span>
                </div>
            </div>

            <button type="button" @click="nextMonth()"
                class="p-2 rounded-lg hover:bg-white/20 transition text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Calendar Grid --}}
    <div class="p-4">
        {{-- Weekday Headers --}}
        <div class="grid grid-cols-7 gap-1 mb-2">
            <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']">
                <div class="text-center text-xs font-semibold text-gray-500 py-2" x-text="day"></div>
            </template>
        </div>

        {{-- Days Grid --}}
        <div class="grid grid-cols-7 gap-1">
            <template x-for="(day, index) in calendarDays" :key="index">
                <div>
                    {{-- Day Cell --}}
                    <div x-show="day.inCurrentMonth"
                        @click="(day.events.length > 0 || day.schedules.length > 0) && showDayModal(day)" :class="{
                            'ring-2 ring-violet-500 ring-offset-1': day.isToday,
                            'cursor-pointer hover:shadow-md': day.events.length > 0 || day.schedules.length > 0,
                            'bg-violet-50 border-violet-300': day.events.length > 0,
                            'bg-amber-50 border-amber-300': day.events.length === 0 && day.schedules.length > 0,
                            'border-gray-200': day.events.length === 0 && day.schedules.length === 0
                        }" class="min-h-[90px] p-1.5 rounded-lg border transition-all relative">

                        {{-- Day Number Row --}}
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold" :class="day.isToday ? 'text-violet-600' : 'text-gray-700'"
                                x-text="day.day"></span>

                            {{-- Badges --}}
                            <div class="flex items-center gap-0.5">
                                <span x-show="day.events.length > 0"
                                    class="w-4 h-4 flex items-center justify-center text-[8px] font-bold text-white bg-violet-500 rounded-full"
                                    x-text="day.events.length"></span>
                                <span x-show="day.schedules.length > 0"
                                    class="w-4 h-4 flex items-center justify-center text-[8px] font-bold text-white bg-amber-500 rounded-full"
                                    x-text="day.schedules.length"></span>
                            </div>
                        </div>

                        {{-- Event Name (if any) --}}
                        <template x-if="day.events.length > 0">
                            <div class="text-[9px] font-semibold text-violet-700 truncate mb-1"
                                x-text="day.events[0]?.name"></div>
                        </template>

                        {{-- Schedule Thumbnails with Names --}}
                        <template x-if="day.schedules.length > 0">
                            <div class="space-y-0.5">
                                <template x-for="(sched, i) in day.schedules.slice(0, 2)" :key="i">
                                    <div class="flex items-center gap-1">
                                        {{-- Thumbnail --}}
                                        <div
                                            class="w-5 h-5 rounded overflow-hidden border border-amber-300 bg-amber-100 flex-shrink-0">
                                            <template x-if="sched.image">
                                                <img :src="sched.image" :alt="sched.name"
                                                    class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!sched.image">
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-2.5 h-2.5 text-amber-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>
                                        {{-- Name --}}
                                        <span class="text-[8px] text-amber-700 font-medium truncate"
                                            x-text="sched.name"></span>
                                    </div>
                                </template>
                                {{-- More indicator --}}
                                <div x-show="day.schedules.length > 2"
                                    class="text-[8px] text-amber-600 font-semibold pl-6">
                                    +<span x-text="day.schedules.length - 2"></span> more
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Outside month (empty) --}}
                    <div x-show="!day.inCurrentMonth" class="min-h-[90px]"></div>
                </div>
            </template>
        </div>

        {{-- Legend --}}
        <div class="mt-4 pt-3 border-t border-gray-200">
            <div class="flex flex-wrap items-center justify-center gap-4 text-xs">
                <div class="flex items-center gap-1.5">
                    <div
                        class="w-4 h-4 rounded bg-violet-100 border-2 border-violet-400 flex items-center justify-center text-[8px] font-bold text-violet-600">
                        1</div>
                    <span class="text-gray-600">Events</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div
                        class="w-4 h-4 rounded bg-amber-100 border-2 border-amber-400 flex items-center justify-center text-[8px] font-bold text-amber-600">
                        2</div>
                    <span class="text-gray-600">Schedules</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-4 h-4 rounded border-2 border-violet-500"></div>
                    <span class="text-gray-600">Today</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Day Detail Modal --}}
    <div x-show="showModal" x-cloak @click.self="showModal = false" @keydown.escape.window="showModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.stop class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[85vh] overflow-hidden"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-4 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-white" x-text="modalDate"></h4>
                    <p class="text-xs text-white/70 mt-0.5">
                        <span x-text="selectedDayEvents.length"></span> event(s),
                        <span x-text="selectedDaySchedules.length"></span> schedule(s)
                    </p>
                </div>
                <button @click="showModal = false" class="text-white/80 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto max-h-[65vh]">
                {{-- Events Section --}}
                <div x-show="selectedDayEvents.length > 0" class="p-4 border-b border-gray-100">
                    <h5
                        class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 rounded bg-violet-500"></span>
                        Events
                    </h5>
                    <div class="space-y-2">
                        <template x-for="event in selectedDayEvents" :key="event.id">
                            <a :href="'{{ $userType === 'admin' ? '/admin/events/' : '/customer/events/' }}' + event.id"
                                class="block p-3 rounded-lg bg-violet-50 hover:bg-violet-100 border border-violet-200 transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h6 class="font-semibold text-violet-900 text-sm truncate" x-text="event.name">
                                        </h6>
                                        @if($userType === 'admin')
                                        <p class="text-xs text-violet-600 mt-0.5" x-text="event.customer_name"></p>
                                        @endif
                                        <p class="text-xs text-violet-500 mt-0.5" x-text="event.venue || 'Venue TBD'">
                                        </p>
                                    </div>
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-violet-200 text-violet-800 capitalize"
                                        x-text="event.status.replace('_', ' ')"></span>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

                {{-- Schedules Section --}}
                <div x-show="selectedDaySchedules.length > 0" class="p-4">
                    <h5
                        class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Inclusion Schedules
                    </h5>
                    <div class="space-y-3">
                        <template x-for="sched in selectedDaySchedules" :key="sched.id">
                            <a :href="'{{ $userType === 'admin' ? '/admin/events/' : '/customer/events/' }}' + sched.event_id"
                                class="block rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-200 transition overflow-hidden">
                                <div class="flex gap-3">
                                    {{-- Image --}}
                                    <div class="w-16 h-16 flex-shrink-0 bg-amber-100">
                                        <template x-if="sched.image">
                                            <img :src="sched.image" :alt="sched.name"
                                                class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!sched.image">
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </template>
                                    </div>
                                    {{-- Content --}}
                                    <div class="flex-1 py-2 pr-3 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0">
                                                <h6 class="font-semibold text-amber-900 text-sm truncate"
                                                    x-text="sched.name"></h6>
                                                <p class="text-xs text-amber-600 truncate" x-text="sched.event_name">
                                                </p>
                                            </div>
                                            <span x-show="sched.scheduled_time"
                                                class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-200 text-amber-800 whitespace-nowrap flex-shrink-0"
                                                x-text="sched.scheduled_time"></span>
                                        </div>
                                        {{-- Remarks --}}
                                        <p x-show="sched.remarks" class="text-xs text-amber-700 mt-1 line-clamp-2"
                                            x-text="sched.remarks"></p>
                                        {{-- Category badge --}}
                                        <span x-show="sched.category"
                                            class="inline-block mt-1 px-1.5 py-0.5 text-[9px] font-medium bg-amber-200/50 text-amber-700 rounded"
                                            x-text="sched.category"></span>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

                {{-- Empty State --}}
                <div x-show="selectedDayEvents.length === 0 && selectedDaySchedules.length === 0"
                    class="p-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p>Nothing scheduled for this day</p>
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
        schedules: [],
        showModal: false,
        selectedDayEvents: [],
        selectedDaySchedules: [],
        modalDate: '',
        eventsInMonth: 0,
        schedulesInMonth: 0,

        initCalendar(eventsData, schedulesData) {
            this.events = Array.isArray(eventsData) ? eventsData : [];
            this.schedules = Array.isArray(schedulesData) ? schedulesData : [];
            this.calculateStats();
            this.renderCalendar();
        },

        calculateStats() {
            const currentYear = this.currentDate.getFullYear();
            const currentMonth = this.currentDate.getMonth();

            this.eventsInMonth = this.events.filter(e => {
                const eventDate = new Date(e.event_date);
                return eventDate.getFullYear() === currentYear && eventDate.getMonth() === currentMonth;
            }).length;

            this.schedulesInMonth = this.schedules.filter(s => {
                const schedDate = new Date(s.scheduled_date);
                return schedDate.getFullYear() === currentYear && schedDate.getMonth() === currentMonth;
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
                    events: [],
                    schedules: []
                });
            }

            // Month days
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                const dateString = this.formatDate(date);

                const dayEvents = this.events.filter(e => e.event_date === dateString);
                const daySchedules = this.schedules.filter(s => s.scheduled_date === dateString);

                this.calendarDays.push({
                    day: day,
                    date: dateString,
                    inCurrentMonth: true,
                    isToday: date.toDateString() === today.toDateString(),
                    events: dayEvents,
                    schedules: daySchedules
                });
            }
        },

        showDayModal(day) {
            this.selectedDayEvents = day.events;
            this.selectedDaySchedules = day.schedules;
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