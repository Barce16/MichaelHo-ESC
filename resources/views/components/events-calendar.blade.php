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

// Get progress data
$progressData = [];
if ($event->progress && $event->progress->count() > 0) {
$progressData = $event->progress->sortByDesc('progress_date')->map(function($p) {
return [
'id' => $p->id,
'status' => $p->status,
'details' => $p->details,
'progress_date' => $p->progress_date ? $p->progress_date->format('M d, Y') : null,
'progress_date_raw' => $p->progress_date ? $p->progress_date->format('Y-m-d') : null,
];
})->values()->toArray();
}

$data = [
'id' => $event->id ?? 0,
'name' => $event->name ?? 'Untitled Event',
'event_date' => $eventDate ?? now()->format('Y-m-d'),
'status' => $event->status ?? 'requested',
'venue' => $event->venue ?? '',
'type' => 'event',
'progress' => $progressData,
'progress_count' => count($progressData),
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

// Build unique events list for filter dropdown
$eventFilterList = $eventsCollection->map(function($event) {
if (!is_object($event)) return null;
return [
'id' => $event->id ?? 0,
'name' => $event->name ?? 'Untitled Event',
];
})->filter()->unique('id')->values()->toArray();
@endphp

<div x-data="eventsCalendar()" x-init="initCalendar(@js($eventsArray), @js($schedulesArray), @js($eventFilterList))"
    class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">

    {{-- Header - Compact --}}
    <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-3 py-2.5">
        <div class="flex items-center justify-between">
            <button type="button" @click="previousMonth()"
                class="p-1.5 rounded-lg hover:bg-white/20 transition text-white/80 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="text-center">
                <h3 class="text-base font-bold text-white" x-text="currentMonthDisplay"></h3>
                <div class="flex items-center justify-center gap-2 mt-0.5">
                    <span class="text-[10px] text-white/70">
                        <span x-text="eventsInMonth"></span> event<span x-show="eventsInMonth !== 1">s</span>
                    </span>
                    <span class="text-white/40">•</span>
                    <span class="text-[10px] text-white/70">
                        <span x-text="progressInMonth"></span> progress
                    </span>
                    <span class="text-white/40">•</span>
                    <span class="text-[10px] text-white/70">
                        <span x-text="schedulesInMonth"></span> schedule<span x-show="schedulesInMonth !== 1">s</span>
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-1">
                {{-- Track Schedules Button --}}
                <button type="button" @click="showSchedulesList = true"
                    class="p-1.5 rounded-lg hover:bg-white/20 transition text-white/80 hover:text-white flex items-center gap-2 pe-2"
                    title="Track Schedules">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <div class="text-sm">View Schedules</div>
                </button>
                {{-- Next Month Button --}}
                <button type="button" @click="nextMonth()"
                    class="p-1.5 rounded-lg hover:bg-white/20 transition text-white/80 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Event Filter Button --}}
    <div class="px-3 py-2 bg-gray-50 border-b border-gray-200">
        <div class="flex items-center gap-2 max-w-xs">
            <button type="button" @click="showEventFilter = true"
                class="flex-1 flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-300 rounded-lg hover:border-violet-400 hover:bg-violet-50 transition text-left group">
                <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-violet-500 transition" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span class="flex-1 text-xs truncate"
                    :class="selectedEventId === '' ? 'text-gray-500' : 'text-gray-900 font-medium'"
                    x-text="selectedEventName"></span>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <button type="button" x-show="selectedEventId !== ''" @click="clearFilter()"
                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                title="Clear filter">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Event Filter Modal --}}
    <div x-show="showEventFilter" x-cloak @click.self="showEventFilter = false"
        @keydown.escape.window="showEventFilter = false"
        class="fixed inset-0 z-50 flex items-start justify-center pt-20 sm:pt-32 p-4 bg-black/40 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.stop class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-sm">Filter by Event</h4>
                            <p class="text-[10px] text-white/70"><span x-text="eventFilterList.length"></span> event(s)
                                available</p>
                        </div>
                    </div>
                    <button @click="showEventFilter = false" class="text-white/80 hover:text-white transition p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Search Box --}}
            <div class="p-3 border-b border-gray-200">
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="eventSearchQuery" placeholder="Search events..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                </div>
            </div>

            {{-- Events List --}}
            <div class="max-h-[50vh] overflow-y-auto">
                {{-- All Events Option --}}
                <button type="button" @click="selectEvent('', 'All Events')"
                    class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-50 transition border-b border-gray-100"
                    :class="selectedEventId === '' ? 'bg-violet-50' : ''">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <div class="text-sm font-semibold text-gray-900">All Events</div>
                        <div class="text-[10px] text-gray-500">Show all events and schedules</div>
                    </div>
                    <div x-show="selectedEventId === ''"
                        class="w-5 h-5 bg-violet-500 rounded-full flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </button>

                {{-- Individual Events --}}
                <template x-for="event in filteredEventList" :key="event.id">
                    <button type="button" @click="selectEvent(event.id, event.name)"
                        class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-50 transition border-b border-gray-100"
                        :class="selectedEventId == event.id ? 'bg-violet-50' : ''">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" :class="{
                                'bg-gradient-to-br from-amber-100 to-amber-200': event.status === 'requested',
                                'bg-gradient-to-br from-emerald-100 to-emerald-200': event.status === 'approved',
                                'bg-gradient-to-br from-blue-100 to-blue-200': event.status === 'meeting',
                                'bg-gradient-to-br from-violet-100 to-violet-200': event.status === 'scheduled',
                                'bg-gradient-to-br from-green-100 to-green-200': event.status === 'completed',
                                'bg-gradient-to-br from-gray-100 to-gray-200': !['requested','approved','meeting','scheduled','completed'].includes(event.status)
                            }">
                            <svg class="w-5 h-5" :class="{
                                'text-amber-600': event.status === 'requested',
                                'text-emerald-600': event.status === 'approved',
                                'text-blue-600': event.status === 'meeting',
                                'text-violet-600': event.status === 'scheduled',
                                'text-green-600': event.status === 'completed',
                                'text-gray-600': !['requested','approved','meeting','scheduled','completed'].includes(event.status)
                            }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1 text-left min-w-0">
                            <div class="text-sm font-semibold text-gray-900 truncate" x-text="event.name"></div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[10px] text-gray-500"
                                    x-text="formatEventDate(event.event_date)"></span>
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full font-medium" :class="{
                                        'bg-amber-100 text-amber-700': event.status === 'requested',
                                        'bg-emerald-100 text-emerald-700': event.status === 'approved',
                                        'bg-blue-100 text-blue-700': event.status === 'meeting',
                                        'bg-violet-100 text-violet-700': event.status === 'scheduled',
                                        'bg-green-100 text-green-700': event.status === 'completed',
                                        'bg-gray-100 text-gray-700': !['requested','approved','meeting','scheduled','completed'].includes(event.status)
                                    }" x-text="event.status.charAt(0).toUpperCase() + event.status.slice(1)"></span>
                            </div>
                        </div>
                        <div x-show="selectedEventId == event.id"
                            class="w-5 h-5 bg-violet-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </button>
                </template>

                {{-- No Results --}}
                <div x-show="filteredEventList.length === 0 && eventSearchQuery !== ''" class="py-8 text-center">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-gray-500">No events found</p>
                    <p class="text-xs text-gray-400 mt-1">Try a different search term</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Calendar Grid - Compact --}}
    <div class="p-2">
        {{-- Weekday Headers --}}
        <div class="grid grid-cols-7 gap-0.5 mb-1">
            <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']">
                <div class="text-center text-[10px] font-semibold text-gray-500 py-1" x-text="day"></div>
            </template>
        </div>

        {{-- Days Grid --}}
        <div class="grid grid-cols-7 gap-0.5">
            <template x-for="(day, index) in calendarDays" :key="index">
                <div>
                    {{-- Day Cell --}}
                    <div x-show="day.inCurrentMonth"
                        @click="(day.events.length > 0 || day.schedules.length > 0 || day.progressUpdates.length > 0) && showDayModal(day)"
                        :class="{
                            'ring-2 ring-violet-500 ring-offset-1': day.isToday,
                            'cursor-pointer hover:shadow-md hover:scale-[1.02]': day.events.length > 0 || day.schedules.length > 0 || day.progressUpdates.length > 0,
                            'bg-violet-50 border-violet-300': day.events.length > 0,
                            'bg-indigo-50 border-indigo-300': day.events.length === 0 && day.progressUpdates.length > 0 && day.schedules.length === 0,
                            'bg-amber-50 border-amber-300': day.events.length === 0 && day.progressUpdates.length === 0 && day.schedules.length > 0,
                            'border-gray-200': day.events.length === 0 && day.schedules.length === 0 && day.progressUpdates.length === 0
                        }" class="h-[75px] p-1 rounded border transition-all relative overflow-hidden">

                        {{-- Day Number with Badges --}}
                        <div class="flex items-center justify-between mb-0.5">
                            <span class="text-[10px] font-bold"
                                :class="day.isToday ? 'text-violet-600' : 'text-gray-700'" x-text="day.day"></span>

                            <div class="flex items-center gap-0.5">
                                <span x-show="day.events.length > 0"
                                    class="w-3.5 h-3.5 flex items-center justify-center text-[7px] font-bold text-white bg-violet-500 rounded-full"
                                    x-text="day.events.length"></span>
                                <span x-show="day.progressUpdates.length > 0"
                                    class="w-3.5 h-3.5 flex items-center justify-center text-[7px] font-bold text-white bg-indigo-500 rounded-full"
                                    x-text="day.progressUpdates.length"></span>
                                <span x-show="day.schedules.length > 0"
                                    class="w-3.5 h-3.5 flex items-center justify-center text-[7px] font-bold text-white bg-amber-500 rounded-full"
                                    x-text="day.schedules.length"></span>
                            </div>
                        </div>

                        {{-- Event Name (if any) --}}
                        <template x-if="day.events.length > 0">
                            <div class="text-[8px] font-semibold text-violet-700 truncate leading-tight"
                                x-text="day.events[0]?.name"></div>
                        </template>

                        {{-- Progress Update (if no event but has progress) --}}
                        <template x-if="day.events.length === 0 && day.progressUpdates.length > 0">
                            <div class="flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span class="text-[8px] font-semibold text-indigo-700 truncate"
                                    x-text="day.progressUpdates[0]?.status"></span>
                            </div>
                        </template>

                        {{-- Progress indicator when event exists --}}
                        <template x-if="day.events.length > 0 && day.progressUpdates.length > 0">
                            <div class="flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span class="text-[7px] text-indigo-600 truncate"
                                    x-text="day.progressUpdates[0]?.status"></span>
                            </div>
                        </template>

                        {{-- Schedule - Tall image on left, name on right --}}
                        <template
                            x-if="day.schedules.length > 0 && day.events.length === 0 && day.progressUpdates.length === 0">
                            <div class="flex items-stretch gap-1 mt-0.5 h-[45px]">
                                {{-- First schedule --}}
                                <template x-if="day.schedules[0]">
                                    <div class="flex gap-1 flex-1 min-w-0">
                                        {{-- Tall Image --}}
                                        <div
                                            class="w-11 h-full rounded overflow-hidden border border-amber-300 bg-amber-100 flex-shrink-0">
                                            <template x-if="day.schedules[0].image">
                                                <img :src="day.schedules[0].image" :alt="day.schedules[0].name"
                                                    class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!day.schedules[0].image">
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-amber-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>
                                        {{-- Name --}}
                                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                                            <span
                                                class="text-[8px] text-amber-800 font-semibold leading-tight line-clamp-2"
                                                x-text="day.schedules[0].name"></span>
                                            <template x-if="day.schedules.length > 1">
                                                <span class="text-[7px] text-amber-600 mt-0.5">+<span
                                                        x-text="day.schedules.length - 1"></span> more</span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    {{-- Outside month (empty) --}}
                    <div x-show="!day.inCurrentMonth" class="h-[75px]"></div>
                </div>
            </template>
        </div>

        {{-- Legend - Compact --}}
        <div class="mt-2 pt-2 border-t border-gray-200">
            <div class="flex flex-wrap items-center justify-center gap-3 text-[10px]">
                <div class="flex items-center gap-1">
                    <div class="w-4 h-4 rounded bg-violet-100 border border-violet-400 flex items-center justify-center text-[8px] font-bold text-violet-600"
                        x-text="eventsInMonth"></div>
                    <span class="text-gray-600">Events</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-4 h-4 rounded bg-indigo-100 border border-indigo-400 flex items-center justify-center text-[8px] font-bold text-indigo-600"
                        x-text="progressInMonth"></div>
                    <span class="text-gray-600">Progress</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-4 h-4 rounded bg-amber-100 border border-amber-400 flex items-center justify-center text-[8px] font-bold text-amber-600"
                        x-text="schedulesInMonth"></div>
                    <span class="text-gray-600">Schedules</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-4 h-4 rounded border border-violet-500 ring-1 ring-violet-500 ring-offset-1"></div>
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
            <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-4 py-3 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-white text-sm" x-text="modalDate"></h4>
                    <p class="text-[10px] text-white/70 mt-0.5">
                        <span x-text="selectedDayEvents.length"></span> event(s),
                        <span x-text="selectedDayProgress.length"></span> progress,
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
                <div x-show="selectedDayEvents.length > 0" class="p-3 border-b border-gray-100">
                    <h5
                        class="text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded bg-violet-500"></span>
                        Events
                    </h5>
                    <div class="space-y-2">
                        <template x-for="event in selectedDayEvents" :key="event.id">
                            <div class="p-2.5 rounded-lg bg-violet-50 border border-violet-200">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <h6 class="font-semibold text-violet-900 text-xs truncate" x-text="event.name">
                                        </h6>
                                        @if($userType === 'admin')
                                        <p class="text-[10px] text-violet-600 mt-0.5" x-text="event.customer_name"></p>
                                        @endif
                                        <p class="text-[10px] text-violet-600 mt-0.5"
                                            x-text="event.venue || 'No venue'"></p>
                                    </div>
                                    <span class="shrink-0 px-2 py-0.5 text-[9px] font-semibold rounded-full" :class="{
                                            'bg-amber-100 text-amber-700': event.status === 'requested',
                                            'bg-emerald-100 text-emerald-700': event.status === 'approved',
                                            'bg-blue-100 text-blue-700': event.status === 'meeting',
                                            'bg-violet-100 text-violet-700': event.status === 'scheduled',
                                            'bg-green-100 text-green-700': event.status === 'completed',
                                            'bg-gray-100 text-gray-700': !['requested','approved','meeting','scheduled','completed'].includes(event.status)
                                        }" x-text="event.status.charAt(0).toUpperCase() + event.status.slice(1)">
                                    </span>
                                </div>
                                {{-- Action Buttons --}}
                                <div class="flex items-center gap-2 mt-2 pt-2 border-t border-violet-200">
                                    <a :href="'{{ $userType === 'admin' ? '/admin/events/' : '/customer/events/' }}' + event.id"
                                        class="flex-1 inline-flex items-center justify-center gap-1 px-2 py-1.5 text-[10px] font-medium text-violet-700 bg-white border border-violet-300 rounded-lg hover:bg-violet-100 transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Event
                                    </a>
                                    <button type="button" x-show="event.progress_count > 0"
                                        @click="showProgressModal(event)"
                                        class="flex-1 inline-flex items-center justify-center gap-1 px-2 py-1.5 text-[10px] font-medium text-indigo-700 bg-indigo-100 border border-indigo-300 rounded-lg hover:bg-indigo-200 transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Progress
                                        <span class="px-1 py-0.5 text-[8px] bg-indigo-200 rounded-full"
                                            x-text="event.progress_count"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Progress Updates Section --}}
                <div x-show="selectedDayProgress.length > 0" class="p-3 border-b border-gray-100">
                    <h5
                        class="text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded bg-indigo-500"></span>
                        Progress Updates
                    </h5>
                    <div class="space-y-2">
                        <template x-for="progress in selectedDayProgress" :key="progress.id">
                            <div class="p-2.5 rounded-lg bg-indigo-50 border border-indigo-200">
                                <div class="flex items-start justify-between gap-2 mb-1.5">
                                    <span class="text-[10px] font-semibold text-indigo-800 truncate"
                                        x-text="progress.event_name"></span>
                                    <span
                                        class="shrink-0 px-2 py-0.5 text-[9px] font-semibold rounded-full bg-indigo-100 text-indigo-700"
                                        x-text="progress.status"></span>
                                </div>
                                <p x-show="progress.details" class="text-[10px] text-indigo-700 leading-relaxed"
                                    x-text="progress.details"></p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Schedules Section with Images --}}
                <div x-show="selectedDaySchedules.length > 0" class="p-3">
                    <h5
                        class="text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded bg-amber-500"></span>
                        Schedules
                    </h5>
                    <div class="space-y-2">
                        <template x-for="schedule in selectedDaySchedules" :key="schedule.id">
                            <div class="flex gap-3 p-2.5 rounded-lg bg-amber-50 border border-amber-200">
                                {{-- Image --}}
                                <div
                                    class="w-14 h-14 rounded-lg overflow-hidden border border-amber-300 bg-amber-100 flex-shrink-0">
                                    <template x-if="schedule.image">
                                        <img :src="schedule.image" :alt="schedule.name"
                                            class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!schedule.image">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </template>
                                </div>
                                {{-- Details --}}
                                <div class="flex-1 min-w-0">
                                    <h6 class="font-semibold text-amber-900 text-xs" x-text="schedule.name"></h6>
                                    <p class="text-[10px] text-amber-700 mt-0.5" x-text="schedule.event_name"></p>
                                    <template x-if="schedule.scheduled_time">
                                        <p class="text-[10px] text-amber-600 mt-0.5 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span x-text="schedule.scheduled_time"></span>
                                        </p>
                                    </template>
                                    <template x-if="schedule.category">
                                        <span
                                            class="inline-block mt-1 px-1.5 py-0.5 text-[8px] font-medium bg-amber-200 text-amber-800 rounded"
                                            x-text="schedule.category"></span>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Track Schedules Modal --}}
    <div x-show="showSchedulesList" x-cloak @click.self="showSchedulesList = false"
        @keydown.escape.window="showSchedulesList = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.stop class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[85vh] overflow-hidden"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-sm">All Schedules</h4>
                        <p class="text-[10px] text-white/80">
                            <span x-text="filteredSchedules.length"></span> schedule(s) <span
                                x-show="selectedEventId !== ''" class="text-white/60">• filtered</span>
                        </p>
                    </div>
                </div>
                <button @click="showSchedulesList = false" class="text-white/80 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Schedules List --}}
            <div class="overflow-y-auto max-h-[70vh] p-4">
                <template x-if="filteredSchedules.length > 0">
                    <div class="space-y-2">
                        <template x-for="schedule in sortedFilteredSchedules" :key="schedule.id">
                            <div class="flex gap-3 p-3 rounded-lg border transition-all" :class="{
                                'bg-rose-50 border-rose-200': isOverdue(schedule.scheduled_date),
                                'bg-blue-50 border-blue-200': isToday(schedule.scheduled_date),
                                'bg-amber-50 border-amber-200': !isOverdue(schedule.scheduled_date) && !isToday(schedule.scheduled_date)
                            }">
                                {{-- Image --}}
                                <div class="w-16 h-16 rounded-lg overflow-hidden border flex-shrink-0" :class="{
                                    'border-rose-300 bg-rose-100': isOverdue(schedule.scheduled_date),
                                    'border-blue-300 bg-blue-100': isToday(schedule.scheduled_date),
                                    'border-amber-300 bg-amber-100': !isOverdue(schedule.scheduled_date) && !isToday(schedule.scheduled_date)
                                }">
                                    <template x-if="schedule.image">
                                        <img :src="schedule.image" :alt="schedule.name"
                                            class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!schedule.image">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6" :class="{
                                                'text-rose-400': isOverdue(schedule.scheduled_date),
                                                'text-blue-400': isToday(schedule.scheduled_date),
                                                'text-amber-400': !isOverdue(schedule.scheduled_date) && !isToday(schedule.scheduled_date)
                                            }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </template>
                                </div>

                                {{-- Details --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h6 class="font-semibold text-sm truncate" :class="{
                                                'text-rose-900': isOverdue(schedule.scheduled_date),
                                                'text-blue-900': isToday(schedule.scheduled_date),
                                                'text-amber-900': !isOverdue(schedule.scheduled_date) && !isToday(schedule.scheduled_date)
                                            }" x-text="schedule.name"></h6>
                                            <p class="text-xs mt-0.5" :class="{
                                                'text-rose-700': isOverdue(schedule.scheduled_date),
                                                'text-blue-700': isToday(schedule.scheduled_date),
                                                'text-amber-700': !isOverdue(schedule.scheduled_date) && !isToday(schedule.scheduled_date)
                                            }" x-text="schedule.event_name"></p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <div class="text-xs font-semibold" :class="{
                                                'text-rose-700': isOverdue(schedule.scheduled_date),
                                                'text-blue-700': isToday(schedule.scheduled_date),
                                                'text-amber-700': !isOverdue(schedule.scheduled_date) && !isToday(schedule.scheduled_date)
                                            }" x-text="formatScheduleDate(schedule.scheduled_date)"></div>
                                            <template x-if="schedule.scheduled_time">
                                                <div class="text-[10px] mt-0.5" :class="{
                                                    'text-rose-600': isOverdue(schedule.scheduled_date),
                                                    'text-blue-600': isToday(schedule.scheduled_date),
                                                    'text-amber-600': !isOverdue(schedule.scheduled_date) && !isToday(schedule.scheduled_date)
                                                }" x-text="schedule.scheduled_time"></div>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <template x-if="schedule.category">
                                            <span class="px-1.5 py-0.5 text-[9px] font-medium rounded" :class="{
                                                'bg-rose-200 text-rose-800': isOverdue(schedule.scheduled_date),
                                                'bg-blue-200 text-blue-800': isToday(schedule.scheduled_date),
                                                'bg-amber-200 text-amber-800': !isOverdue(schedule.scheduled_date) && !isToday(schedule.scheduled_date)
                                            }" x-text="schedule.category"></span>
                                        </template>
                                        <span class="px-1.5 py-0.5 text-[9px] font-semibold rounded-full" :class="{
                                            'bg-rose-500 text-white': isOverdue(schedule.scheduled_date),
                                            'bg-blue-500 text-white': isToday(schedule.scheduled_date),
                                            'bg-amber-500 text-white': !isOverdue(schedule.scheduled_date) && !isToday(schedule.scheduled_date)
                                        }">
                                            <span x-show="isOverdue(schedule.scheduled_date)">Overdue</span>
                                            <span x-show="isToday(schedule.scheduled_date)">Today</span>
                                            <span
                                                x-show="!isOverdue(schedule.scheduled_date) && !isToday(schedule.scheduled_date)">Upcoming</span>
                                        </span>
                                    </div>
                                    <template x-if="schedule.remarks">
                                        <p class="text-[10px] mt-1.5 italic" :class="{
                                            'text-rose-600': isOverdue(schedule.scheduled_date),
                                            'text-blue-600': isToday(schedule.scheduled_date),
                                            'text-amber-600': !isOverdue(schedule.scheduled_date) && !isToday(schedule.scheduled_date)
                                        }" x-text="schedule.remarks"></p>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Empty State --}}
                <div x-show="filteredSchedules.length === 0" class="py-12 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-sm font-medium">No schedules found</p>
                    <p class="text-xs text-gray-400 mt-1" x-show="selectedEventId !== ''">Try selecting a different
                        event or clear the filter</p>
                    <p class="text-xs text-gray-400 mt-1" x-show="selectedEventId === ''">Schedules will appear here
                        once set</p>
                </div>
            </div>

            {{-- Legend --}}
            <div class="border-t border-gray-200 px-4 py-2 bg-gray-50">
                <div class="flex items-center justify-center gap-4 text-[10px]">
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span class="text-gray-600">Overdue</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span class="text-gray-600">Today</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="text-gray-600">Upcoming</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Event Progress Modal --}}
    <div x-show="showProgress" x-cloak @click.self="showProgress = false" @keydown.escape.window="showProgress = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.stop class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[85vh] flex flex-col"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-5 py-4 rounded-t-2xl flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Event Progress</h3>
                            <p class="text-xs text-white/80" x-text="progressEventName"></p>
                        </div>
                    </div>
                    <button type="button" @click="showProgress = false"
                        class="text-white/80 hover:text-white transition p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="flex-1 overflow-y-auto p-5">
                <template x-if="progressData.length > 0">
                    <div class="relative">
                        {{-- Timeline Line --}}
                        <div
                            class="absolute left-3 top-0 bottom-0 w-0.5 bg-gradient-to-b from-indigo-500 via-purple-400 to-gray-200">
                        </div>

                        <div class="space-y-4">
                            <template x-for="(progress, idx) in progressData" :key="progress.id">
                                <div class="relative pl-8 pr-1">
                                    {{-- Timeline Dot --}}
                                    <div class="absolute left-1 top-1.5 w-4 h-4 rounded-full border-2 border-white shadow"
                                        :class="idx === 0 ? 'bg-indigo-500 ring-2 ring-indigo-100' : 'bg-gray-400'">
                                    </div>

                                    {{-- Progress Card --}}
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                        <div class="flex items-center gap-2 flex-wrap mb-2">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                                                :class="idx === 0 ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-200 text-gray-700'"
                                                x-text="progress.status"></span>
                                            <span class="text-xs text-gray-500" x-text="progress.progress_date"></span>
                                            <span x-show="idx === 0"
                                                class="text-xs bg-green-100 text-green-700 px-1.5 py-0.5 rounded">Latest</span>
                                        </div>
                                        <p x-show="progress.details"
                                            class="text-gray-600 text-sm leading-relaxed whitespace-normal"
                                            style="word-wrap: break-word; overflow-wrap: break-word;"
                                            x-text="progress.details"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Empty State --}}
                <div x-show="progressData.length === 0" class="py-8 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-sm text-gray-500 font-medium">No progress updates yet</p>
                    <p class="text-xs text-gray-400 mt-1">Progress updates will appear here</p>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div
                class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between rounded-b-2xl flex-shrink-0">
                <span class="text-xs text-gray-500"><span x-text="progressData.length"></span> update(s)</span>
                <button type="button" @click="showProgress = false"
                    class="px-4 py-1.5 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition">
                    Close
                </button>
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
        allEvents: [],
        allSchedules: [],
        eventFilterList: [],
        selectedEventId: '',
        selectedEventName: 'All Events',
        eventSearchQuery: '',
        showModal: false,
        showSchedulesList: false,
        showEventFilter: false,
        showProgress: false,
        progressData: [],
        progressEventName: '',
        selectedDayEvents: [],
        selectedDaySchedules: [],
        selectedDayProgress: [],
        modalDate: '',
        eventsInMonth: 0,
        schedulesInMonth: 0,
        progressInMonth: 0,

        get filteredEventList() {
            if (this.eventSearchQuery === '') {
                return this.eventFilterList;
            }
            const query = this.eventSearchQuery.toLowerCase();
            return this.eventFilterList.filter(e => 
                e.name.toLowerCase().includes(query)
            );
        },

        get filteredSchedules() {
            if (this.selectedEventId === '') {
                return this.schedules;
            }
            return this.schedules.filter(s => s.event_id == this.selectedEventId);
        },

        get sortedFilteredSchedules() {
            return [...this.filteredSchedules].sort((a, b) => {
                const dateA = new Date(a.scheduled_date);
                const dateB = new Date(b.scheduled_date);
                return dateA - dateB;
            });
        },

        get sortedSchedules() {
            return [...this.schedules].sort((a, b) => {
                const dateA = new Date(a.scheduled_date);
                const dateB = new Date(b.scheduled_date);
                return dateA - dateB;
            });
        },

        initCalendar(eventsData, schedulesData, eventFilterListData) {
            this.allEvents = Array.isArray(eventsData) ? eventsData : [];
            this.allSchedules = Array.isArray(schedulesData) ? schedulesData : [];
            this.eventFilterList = Array.isArray(eventFilterListData) ? eventFilterListData.map(e => {
                // Find full event data to get status
                const fullEvent = this.allEvents.find(ae => ae.id === e.id);
                return {
                    ...e,
                    status: fullEvent?.status || 'unknown',
                    event_date: fullEvent?.event_date || ''
                };
            }) : [];
            this.events = [...this.allEvents];
            this.schedules = [...this.allSchedules];
            this.calculateStats();
            this.renderCalendar();
        },

        selectEvent(eventId, eventName) {
            this.selectedEventId = eventId === '' ? '' : eventId;
            this.selectedEventName = eventName;
            this.eventSearchQuery = '';
            this.showEventFilter = false;
            this.applyFilter();
        },

        clearFilter() {
            this.selectedEventId = '';
            this.selectedEventName = 'All Events';
            this.eventSearchQuery = '';
            this.applyFilter();
        },

        showProgressModal(event) {
            this.progressEventName = event.name;
            this.progressData = event.progress || [];
            this.showProgress = true;
        },

        formatEventDate(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            const options = { month: 'short', day: 'numeric', year: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        },

        applyFilter() {
            if (this.selectedEventId === '') {
                this.events = [...this.allEvents];
                this.schedules = [...this.allSchedules];
            } else {
                const eventId = parseInt(this.selectedEventId);
                this.events = this.allEvents.filter(e => e.id === eventId);
                this.schedules = this.allSchedules.filter(s => s.event_id === eventId);
            }
            this.calculateStats();
            this.renderCalendar();
        },

        isToday(dateStr) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const date = new Date(dateStr);
            date.setHours(0, 0, 0, 0);
            return date.getTime() === today.getTime();
        },

        isOverdue(dateStr) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const date = new Date(dateStr);
            date.setHours(0, 0, 0, 0);
            return date < today;
        },

        formatScheduleDate(dateStr) {
            const date = new Date(dateStr);
            const options = { weekday: 'short', month: 'short', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
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

            // Count progress updates in current month
            let progressCount = 0;
            this.events.forEach(event => {
                if (event.progress && event.progress.length > 0) {
                    event.progress.forEach(p => {
                        if (p.progress_date_raw) {
                            const progressDate = new Date(p.progress_date_raw);
                            if (progressDate.getFullYear() === currentYear && progressDate.getMonth() === currentMonth) {
                                progressCount++;
                            }
                        }
                    });
                }
            });
            this.progressInMonth = progressCount;
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
                    schedules: [],
                    progressUpdates: []
                });
            }

            // Month days
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                const dateString = this.formatDate(date);

                const dayEvents = this.events.filter(e => e.event_date === dateString);
                const daySchedules = this.schedules.filter(s => s.scheduled_date === dateString);
                
                // Gather progress updates for this day from all events
                const dayProgress = [];
                this.events.forEach(event => {
                    if (event.progress && event.progress.length > 0) {
                        event.progress.forEach(p => {
                            if (p.progress_date_raw === dateString) {
                                dayProgress.push({
                                    ...p,
                                    event_id: event.id,
                                    event_name: event.name
                                });
                            }
                        });
                    }
                });

                this.calendarDays.push({
                    day: day,
                    date: dateString,
                    inCurrentMonth: true,
                    isToday: date.toDateString() === today.toDateString(),
                    events: dayEvents,
                    schedules: daySchedules,
                    progressUpdates: dayProgress
                });
            }
        },

        showDayModal(day) {
            this.selectedDayEvents = day.events;
            this.selectedDaySchedules = day.schedules;
            this.selectedDayProgress = day.progressUpdates || [];
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