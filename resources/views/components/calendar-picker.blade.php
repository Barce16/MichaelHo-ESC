@props(['name' => 'event_date', 'value' => null, 'required' => false])

<div x-data="calendarPickerData" x-init="initCalendar('{{ $name }}', '{{ $value }}')" class="relative">
    <!-- Hidden input that stores the actual date value -->
    <input type="hidden" :name="inputName" x-model="selectedDate" {{ $required ? 'required' : '' }}>

    <!-- Display Input (read-only) -->
    <button type="button" @click="showCalendar = !showCalendar"
        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition text-left bg-white hover:border-gray-400">
        <div class="flex items-center justify-between">
            <span x-text="selectedDate ? formatDisplayDate(selectedDate) : 'Select event date'"
                :class="selectedDate ? 'text-gray-900' : 'text-gray-400'">
                Select event date
            </span>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
    </button>

    <!-- Calendar Dropdown Portal -->
    <div x-show="showCalendar" @click.away="showCalendar = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[9999] flex items-start justify-center p-4 sm:p-8 overflow-y-auto"
        style="display: none;">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/20 backdrop-blur-sm" @click="showCalendar = false"></div>

        <!-- Calendar Card -->
        <div class="relative bg-white rounded-xl shadow-2xl border border-gray-200 p-4 w-full max-w-md mt-20 sm:mt-24"
            @click.stop>

            <!-- Close Button -->
            <button type="button" @click="showCalendar = false"
                class="absolute top-3 right-3 p-1 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Month Navigation -->
            <div class="flex items-center justify-between mb-4 pr-8">
                <button type="button" @click="previousMonth()" :disabled="!canGoPrevious()"
                    :class="{ 'opacity-50 cursor-not-allowed': !canGoPrevious() }"
                    class="p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <h3 class="text-lg font-bold text-gray-900" x-text="currentMonthDisplay">Loading...</h3>

                <button type="button" @click="nextMonth()" class="p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- 3 Month Notice -->
            <div class="mb-3 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-xs text-blue-700 text-center">
                    📅 Events must be booked at least 3 months in advance
                </p>
            </div>

            <!-- Day Headers -->
            <div class="grid grid-cols-7 gap-1 mb-2">
                <div class="text-center text-xs font-semibold text-gray-600 py-2">Sun</div>
                <div class="text-center text-xs font-semibold text-gray-600 py-2">Mon</div>
                <div class="text-center text-xs font-semibold text-gray-600 py-2">Tue</div>
                <div class="text-center text-xs font-semibold text-gray-600 py-2">Wed</div>
                <div class="text-center text-xs font-semibold text-gray-600 py-2">Thu</div>
                <div class="text-center text-xs font-semibold text-gray-600 py-2">Fri</div>
                <div class="text-center text-xs font-semibold text-gray-600 py-2">Sat</div>
            </div>

            <!-- Calendar Days -->
            <div class="grid grid-cols-7 gap-1">
                <template x-for="(day, index) in calendarDays" :key="index">
                    <div>
                        <button type="button" x-show="day.inCurrentMonth" @click="selectDate(day)"
                            :disabled="day.status === 'past' || day.status === 'full' || day.status === 'too_soon'"
                            :class="{
                                    'bg-emerald-50 text-emerald-900 border-2 border-emerald-200 hover:bg-emerald-100': day.status === 'available',
                                    'bg-amber-50 text-amber-900 border-2 border-amber-300 hover:bg-amber-100': day.status === 'partial',
                                    'bg-rose-100 text-rose-400 border-2 border-rose-200 cursor-not-allowed': day.status === 'full',
                                    'bg-gray-100 text-gray-400 cursor-not-allowed': day.status === 'past' || day.status === 'too_soon',
                                    'ring-4 ring-violet-400 ring-opacity-50': day.isToday,
                                    '!bg-violet-500 !text-white !border-2 !border-violet-600 hover:!bg-violet-600': day.isSelected
                                }"
                            class="w-full aspect-square flex flex-col items-center justify-center rounded-lg text-sm font-medium transition-all duration-200 relative">
                            <span x-text="day.day"></span>
                            <template x-if="day.count > 0 && day.status !== 'past' && day.status !== 'too_soon'">
                                <div class="absolute bottom-1 flex gap-0.5">
                                    <template x-for="i in day.count">
                                        <span class="w-1 h-1 rounded-full" :class="{
                                                  'bg-emerald-500': day.status === 'available',
                                                  'bg-amber-500': day.status === 'partial',
                                                  'bg-rose-500': day.status === 'full'
                                              }"></span>
                                    </template>
                                </div>
                            </template>
                        </button>
                        <div x-show="!day.inCurrentMonth" class="w-full aspect-square"></div>
                    </div>
                </template>
            </div>

            <!-- Legend -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-emerald-50 border-2 border-emerald-200"></div>
                        <span class="text-gray-600">Available</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-amber-50 border-2 border-amber-300"></div>
                        <span class="text-gray-600">Limited</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-rose-100 border-2 border-rose-200"></div>
                        <span class="text-gray-600">Full</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-gray-100"></div>
                        <span class="text-gray-600">Unavailable</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
    Alpine.data('calendarPickerData', () => ({
        inputName: '',
        selectedDate: '',
        showCalendar: false,
        currentDate: new Date(),
        availabilityData: {},
        calendarDays: [],
        currentMonthDisplay: '',
        minAllowedDate: null,

        async initCalendar(name, initialValue) {
            this.inputName = name;
            this.selectedDate = initialValue || '';
            
            const today = new Date();
            this.minAllowedDate = new Date(today.getFullYear(), today.getMonth() + 3, 1);
            // this.minAllowedDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
            
            if (!this.selectedDate) {
                this.currentDate = new Date(this.minAllowedDate);
            }
            
            await this.loadAvailability();
            this.renderCalendar();
        },

        canGoPrevious() {
            const currentYear = this.currentDate.getFullYear();
            const currentMonth = this.currentDate.getMonth();
            const minYear = this.minAllowedDate.getFullYear();
            const minMonth = this.minAllowedDate.getMonth();
            
            return (currentYear > minYear) || (currentYear === minYear && currentMonth > minMonth);
        },

        async loadAvailability() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth() + 1;

            try {
                const response = await fetch(`/api/availability?year=${year}&month=${month}`);
                const data = await response.json();
                this.availabilityData = data.availability;
            } catch (error) {
                console.error('Error loading availability:', error);
                this.availabilityData = {};
            }
        },

        async previousMonth() {
            if (!this.canGoPrevious()) return;
            
            // FIX: Create new Date object for proper Alpine.js reactivity
            this.currentDate = new Date(
                this.currentDate.getFullYear(), 
                this.currentDate.getMonth() - 1, 
                1
            );
            await this.loadAvailability();
            this.renderCalendar();
        },

        async nextMonth() {
            // FIX: Create new Date object for proper Alpine.js reactivity
            this.currentDate = new Date(
                this.currentDate.getFullYear(), 
                this.currentDate.getMonth() + 1, 
                1
            );
            await this.loadAvailability();
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

            for (let i = 0; i < firstDay; i++) {
                this.calendarDays.push({
                    day: '',
                    date: '',
                    inCurrentMonth: false,
                    status: 'past',
                    count: 0,
                    available: 0,
                    isToday: false,
                    isSelected: false
                });
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                const dateString = this.formatDate(date);
                const dayData = this.availabilityData[dateString] || { status: 'available', count: 0, available: 2 };

                let status = dayData.status;
                if (date < this.minAllowedDate) {
                    status = 'too_soon';
                }

                this.calendarDays.push({
                    day: day,
                    date: dateString,
                    inCurrentMonth: true,
                    status: status,
                    count: dayData.count,
                    available: dayData.available,
                    isToday: date.toDateString() === today.toDateString(),
                    isSelected: dateString === this.selectedDate
                });
            }
        },

        selectDate(day) {
            if (day.status === 'past' || day.status === 'full' || day.status === 'too_soon' || !day.inCurrentMonth) {
                return;
            }

            this.selectedDate = day.date;
            this.showCalendar = false;
            this.renderCalendar();
        },

        formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },

        formatDisplayDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString + 'T00:00:00');
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }
    }));
});
</script>