<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800">Individual Event Report</h2>
            </div>
        </div>
    </x-slot>

    {{-- Print Styles --}}
    <style>
        @media print {

            nav,
            header,
            .no-print,
            .no-print * {
                display: none !important;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .print-container {
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }

            .print-content {
                box-shadow: none !important;
            }

            .overflow-x-auto,
            .overflow-y-auto,
            .overflow-auto {
                overflow: visible !important;
            }

            .max-h-64,
            .max-h-96,
            [class*="max-h-"] {
                max-height: none !important;
            }
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print-container">

            {{-- Event Selection (if no event selected) --}}
            @if(!isset($event))
            <div class="bg-white rounded-xl shadow-sm overflow-hidden no-print">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-8 text-white">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Generate Event Report</h3>
                            <p class="text-violet-100 mt-1">Select an event to view complete details and history</p>
                        </div>
                    </div>
                </div>

                {{-- Search & Filter Section --}}
                <div class="p-6 border-b border-gray-200 bg-gray-50" x-data="eventSearch()">
                    <div class="flex flex-col md:flex-row gap-4">
                        {{-- Search Input --}}
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" x-model="search" placeholder="Search by event name or customer..."
                                style="padding-left: 3rem;"
                                class="w-full py-3 rounded-xl border-gray-300 focus:ring-2 focus:ring-violet-200 focus:border-violet-400 text-gray-900">
                        </div>

                        {{-- Status Filter --}}
                        <div class="md:w-48">
                            <select x-model="statusFilter" @change="filterEvents()"
                                class="w-full py-3 px-4 rounded-xl border-gray-300 focus:ring-2 focus:ring-violet-200 focus:border-violet-400">
                                <option value="">All Statuses</option>
                                <option value="requested">Requested</option>
                                <option value="approved">Approved</option>
                                <option value="request_meeting">Request Meeting</option>
                                <option value="meeting">Meeting</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        {{-- Sort Dropdown --}}
                        <div class="md:w-48">
                            <select x-model="sortBy" @change="sortEvents()"
                                class="w-full py-3 px-4 rounded-xl border-gray-300 focus:ring-2 focus:ring-violet-200 focus:border-violet-400">
                                <option value="date_desc">Date (Newest)</option>
                                <option value="date_asc">Date (Oldest)</option>
                                <option value="name">Event Name</option>
                                <option value="amount">Amount (Highest)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="mt-4 flex flex-wrap gap-4 text-sm">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200">
                            <span class="w-2 h-2 bg-violet-500 rounded-full"></span>
                            <span class="text-gray-600">Total Events:</span>
                            <span class="font-semibold text-gray-900">{{ $events->count() }}</span>
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span class="text-gray-600">Completed:</span>
                            <span class="font-semibold text-gray-900">{{ $events->where('status', 'completed')->count()
                                }}</span>
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            <span class="text-gray-600">Scheduled:</span>
                            <span class="font-semibold text-gray-900">{{ $events->where('status', 'scheduled')->count()
                                }}</span>
                        </span>
                        <span class="text-gray-400 ml-auto" x-show="search || statusFilter"
                            x-text="visibleCount + ' results found'"></span>
                    </div>

                    {{-- Event List --}}
                    <div class="mt-6 grid gap-3 max-h-[500px] overflow-y-auto pr-2" id="eventList">
                        @foreach($events as $e)
                        <a href="{{ route('admin.reports.event-detail', ['event_id' => $e->id]) }}"
                            class="event-card block bg-white rounded-xl border-2 border-gray-200 hover:border-violet-400 hover:shadow-md transition-all p-4"
                            data-name="{{ strtolower($e->name) }}"
                            data-customer="{{ strtolower($e->customer->customer_name ?? '') }}"
                            data-status="{{ $e->status }}" data-date="{{ $e->event_date }}"
                            data-amount="{{ $e->billing->total_amount ?? 0 }}"
                            x-show="filterEvent('{{ addslashes(strtolower($e->name)) }}', '{{ addslashes(strtolower($e->customer->customer_name ?? '')) }}', '{{ $e->status }}')"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0">
                            <div class="flex items-center gap-4">
                                {{-- Event Icon --}}
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-violet-400 to-purple-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="font-semibold text-gray-900 truncate">{{ $e->name }}</h4>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                            @if($e->status === 'completed') bg-green-100 text-green-800
                                            @elseif($e->status === 'scheduled') bg-blue-100 text-blue-800
                                            @elseif(in_array($e->status, ['rejected', 'cancelled'])) bg-red-100 text-red-800
                                            @elseif(in_array($e->status, ['approved', 'request_meeting', 'meeting'])) bg-yellow-100 text-yellow-800
                                            @elseif($e->status === 'ongoing') bg-indigo-100 text-indigo-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucwords(str_replace('_', ' ', $e->status)) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="truncate">{{ $e->customer->customer_name ?? 'N/A' }}</span>
                                        </span>
                                        <span class="flex items-center gap-1 flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($e->event_date)->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Amount --}}
                                @if($e->billing)
                                <div class="text-right hidden sm:block">
                                    <div class="text-xs text-gray-500">Total Amount</div>
                                    <div class="font-bold text-violet-600">₱{{ number_format($e->billing->total_amount
                                        ?? 0, 2) }}</div>
                                </div>
                                @endif

                                {{-- Arrow --}}
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    {{-- No Results --}}
                    <div x-show="noResults" x-cloak class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500">No events found matching your criteria.</p>
                    </div>
                </div>
            </div>

            <script>
                function eventSearch() {
                    return {
                        search: '',
                        statusFilter: '',
                        sortBy: 'date_desc',
                        noResults: false,
                        visibleCount: {{ $events->count() }},
                        filterEvent(name, customer, status) {
                            let matchesSearch = true;
                            let matchesStatus = true;
                            
                            if (this.search) {
                                const query = this.search.toLowerCase().trim();
                                matchesSearch = name.includes(query) || customer.includes(query);
                            }
                            
                            if (this.statusFilter) {
                                matchesStatus = status === this.statusFilter;
                            }
                            
                            return matchesSearch && matchesStatus;
                        },
                        filterEvents() {
                            this.$nextTick(() => this.updateCount());
                        },
                        sortEvents() {
                            const list = document.getElementById('eventList');
                            const cards = Array.from(list.querySelectorAll('.event-card'));
                            
                            cards.sort((a, b) => {
                                switch(this.sortBy) {
                                    case 'date_asc':
                                        return new Date(a.dataset.date) - new Date(b.dataset.date);
                                    case 'name':
                                        return a.dataset.name.localeCompare(b.dataset.name);
                                    case 'amount':
                                        return parseFloat(b.dataset.amount) - parseFloat(a.dataset.amount);
                                    default: // date_desc
                                        return new Date(b.dataset.date) - new Date(a.dataset.date);
                                }
                            });
                            
                            cards.forEach(card => list.appendChild(card));
                        },
                        updateCount() {
                            const cards = document.querySelectorAll('.event-card');
                            let visible = 0;
                            cards.forEach(card => {
                                if (card.style.display !== 'none') visible++;
                            });
                            this.visibleCount = visible;
                            this.noResults = visible === 0 && (this.search.length > 0 || this.statusFilter);
                        },
                        init() {
                            this.$watch('search', () => this.$nextTick(() => this.updateCount()));
                            this.$watch('statusFilter', () => this.$nextTick(() => this.updateCount()));
                        }
                    }
                }
            </script>
            @else

            {{-- Export Buttons --}}
            <div
                class="bg-white rounded-lg shadow-sm p-6 mb-6 flex flex-wrap justify-between items-center gap-4 no-print">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-violet-400 to-purple-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Viewing report for</div>
                        <div class="font-semibold text-gray-900">{{ $event->name }}</div>
                    </div>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('admin.reports.event-detail') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Change Event
                    </a>
                    <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                    <form method="GET" class="inline">
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <input type="hidden" name="export" value="pdf">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Export PDF
                        </button>
                    </form>
                </div>
            </div>

            {{-- Report Content --}}
            <div class="bg-white rounded-lg shadow-sm p-8 print-content">

                {{-- Report Header --}}
                <div class="border-b-2 border-gray-300 pb-6 mb-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-600">MH</span>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">MichaelHo Events</h1>
                                    <p class="text-sm text-gray-600">Event Management System</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <h2 class="text-xl font-bold text-gray-900">Event Detail Report</h2>
                            <p class="text-sm text-gray-600">Generated: {{ now()->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Event Overview --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Event Overview
                    </h3>
                    <div class="bg-gradient-to-br from-violet-50 to-purple-50 rounded-xl p-6 border border-violet-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-2xl font-bold text-gray-900">{{ $event->name }}</h4>
                            <span class="px-4 py-2 rounded-full text-sm font-semibold
                                @if($event->status === 'completed') bg-green-100 text-green-800
                                @elseif($event->status === 'scheduled') bg-blue-100 text-blue-800
                                @elseif(in_array($event->status, ['rejected', 'cancelled'])) bg-red-100 text-red-800
                                @elseif(in_array($event->status, ['approved', 'request_meeting', 'meeting'])) bg-yellow-100 text-yellow-800
                                @elseif($event->status === 'ongoing') bg-indigo-100 text-indigo-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $event->status)) }}
                            </span>
                        </div>
                        <div class="grid md:grid-cols-3 gap-6">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Event Date</div>
                                <div class="text-lg font-semibold text-gray-900">{{
                                    \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Venue</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $event->venue ?? 'TBD' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Theme</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $event->theme ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Package</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $event->package->name ?? 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Expected Guests</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $event->guests ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Booked On</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $event->created_at->format('M d, Y')
                                    }}</div>
                            </div>
                        </div>
                        @if($event->notes)
                        <div class="mt-4 pt-4 border-t border-violet-200">
                            <div class="text-sm text-gray-500 mb-1">Notes</div>
                            <div class="text-gray-700">{{ $event->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Customer Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Customer Information
                    </h3>
                    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-200">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Customer Name</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $event->customer->customer_name ??
                                    'N/A' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Email Address</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $event->customer->email ?? 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Phone Number</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $event->customer->phone ??
                                    $event->customer->contact_number ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Address</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $event->customer->address ?? 'N/A'
                                    }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Financial Summary --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Financial Summary
                    </h3>
                    <div class="grid md:grid-cols-4 gap-4">
                        <div
                            class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg p-5 border border-amber-200">
                            <div class="text-xs font-semibold text-amber-700 uppercase tracking-wide mb-1">Total Amount
                            </div>
                            <div class="text-2xl font-bold text-amber-900">₱{{ number_format($stats['total_amount'], 2)
                                }}</div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-5 border border-green-200">
                            <div class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-1">Total Paid
                            </div>
                            <div class="text-2xl font-bold text-green-900">₱{{ number_format($stats['total_paid'], 2) }}
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-lg p-5 border border-red-200">
                            <div class="text-xs font-semibold text-red-700 uppercase tracking-wide mb-1">Remaining
                                Balance</div>
                            <div class="text-2xl font-bold text-red-900">₱{{ number_format($stats['remaining_balance'],
                                2) }}</div>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-5 border border-blue-200">
                            <div class="text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">Payment
                                Progress</div>
                            <div class="text-2xl font-bold text-blue-900">{{ $stats['payment_percentage'] }}%</div>
                            <div class="mt-2 h-2 bg-blue-200 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600 rounded-full"
                                    style="width: {{ $stats['payment_percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Billing & Inclusions --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Billing Breakdown
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Inclusions --}}
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Inclusions ({{ $event->inclusions->count() }})
                            </h4>
                            @if($event->inclusions->count() > 0)
                            <div class="max-h-64 overflow-y-auto">
                                <table class="w-full text-sm">
                                    <thead class="sticky top-0 bg-gray-50">
                                        <tr class="border-b border-gray-300">
                                            <th class="py-2 text-left font-medium text-gray-700">Item</th>
                                            <th class="py-2 text-left font-medium text-gray-700">Category</th>
                                            <th class="py-2 text-right font-medium text-gray-700">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($event->inclusions as $inclusion)
                                        <tr class="border-b border-gray-200 last:border-0">
                                            <td class="py-2 text-gray-700">{{ $inclusion->name }}</td>
                                            <td class="py-2 text-gray-500 text-xs">{{ $inclusion->category ?
                                                ucfirst($inclusion->category->value) : 'N/A' }}</td>
                                            <td class="py-2 text-right font-medium text-gray-900">
                                                ₱{{ number_format($inclusion->pivot->price_snapshot ??
                                                $inclusion->price, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-sm text-gray-500 italic">No inclusions recorded</p>
                            @endif
                        </div>

                        {{-- Billing Summary --}}
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Cost Breakdown
                            </h4>
                            @if($event->billing)
                            @php
                            $inclTotal = $event->inclusions->sum(fn($i) => $i->pivot->price_snapshot ?? $i->price);
                            $coordPrice = $event->package->coordination_price ?? 25000;
                            $stylingPrice = $event->package->event_styling_price ?? 55000;
                            @endphp
                            <table class="w-full text-sm">
                                <tr class="border-b border-gray-200">
                                    <td class="py-3 text-gray-600">Coordination Fee</td>
                                    <td class="py-3 text-right font-medium">₱{{ number_format($coordPrice, 2) }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="py-3 text-gray-600">Event Styling Fee</td>
                                    <td class="py-3 text-right font-medium">₱{{ number_format($stylingPrice, 2) }}</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="py-3 text-gray-600">Inclusions Subtotal</td>
                                    <td class="py-3 text-right font-medium">₱{{ number_format($inclTotal, 2) }}</td>
                                </tr>
                                <tr class="bg-amber-50">
                                    <td class="py-3 font-bold text-gray-900">Grand Total</td>
                                    <td class="py-3 text-right font-bold text-amber-700 text-lg">₱{{
                                        number_format($event->billing->total_amount, 2) }}</td>
                                </tr>
                            </table>
                            <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Introductory:</span>
                                    <span
                                        class="font-semibold {{ $event->billing->intro_paid ? 'text-green-600' : 'text-gray-600' }}">
                                        ₱{{ number_format($event->billing->intro_amount ?? 5000, 2) }}
                                        @if($event->billing->intro_paid) ✓ @endif
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Downpayment:</span>
                                    <span
                                        class="font-semibold {{ $event->billing->downpayment_paid ? 'text-green-600' : 'text-gray-600' }}">
                                        ₱{{ number_format($event->billing->downpayment_amount ?? 0, 2) }}
                                        @if($event->billing->downpayment_paid) ✓ @endif
                                    </span>
                                </div>
                            </div>
                            @else
                            <p class="text-sm text-gray-500 italic">No billing information</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Payment History --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Payment History
                    </h3>
                    @if($payments->count() > 0)
                    <div class="overflow-x-auto bg-gray-50 rounded-xl p-4">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-300">
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Date</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Type</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Method</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Reference</th>
                                    <th class="py-3 px-4 text-right font-semibold text-gray-700">Amount</th>
                                    <th class="py-3 px-4 text-center font-semibold text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr class="border-b border-gray-200 hover:bg-white">
                                    <td class="py-3 px-4">{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="py-3 px-4 capitalize">{{ str_replace('_', ' ', $payment->payment_type) }}
                                    </td>
                                    <td class="py-3 px-4 capitalize">{{ str_replace('_', ' ', $payment->payment_method)
                                        }}</td>
                                    <td class="py-3 px-4 font-mono text-xs">{{ $payment->reference_number ?? '-' }}</td>
                                    <td class="py-3 px-4 text-right font-semibold">₱{{ number_format($payment->amount,
                                        2) }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($payment->status === 'approved') bg-green-100 text-green-800
                                            @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($payment->status === 'rejected' && $payment->rejection_reason)
                                <tr class="bg-red-50">
                                    <td colspan="6" class="py-2 px-4 text-sm text-red-700">
                                        <span class="font-medium">Rejection reason:</span> {{ $payment->rejection_reason
                                        }}
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-gray-300 bg-green-50">
                                    <td colspan="4" class="py-3 px-4 text-right font-bold text-gray-900">Total Paid
                                        (Approved):</td>
                                    <td class="py-3 px-4 text-right font-bold text-green-700 text-lg">₱{{
                                        number_format($stats['total_paid'], 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8 bg-gray-50 rounded-xl">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-gray-500">No payments recorded yet.</p>
                    </div>
                    @endif
                </div>

                {{-- Event Progress / Timeline --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Event Progress & Updates
                    </h3>
                    @if($progressUpdates->count() > 0)
                    <div class="relative">
                        {{-- Timeline Line --}}
                        <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                        <div class="space-y-6">
                            @foreach($progressUpdates as $progress)
                            <div class="relative flex gap-4">
                                {{-- Timeline Dot --}}
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 z-10 border-4 border-white">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-gray-900">{{ $progress->status }}</span>
                                        <span class="text-sm text-gray-500">{{ $progress->created_at->format('M d, Y h:i
                                            A') }}</span>
                                    </div>
                                    <p class="text-gray-600">{{ $progress->details }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8 bg-gray-50 rounded-xl">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-500">No progress updates recorded.</p>
                    </div>
                    @endif
                </div>

                {{-- Staff Assignments --}}
                @if($staffAssignments->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Staff Assignments
                    </h3>
                    <div class="overflow-x-auto bg-gray-50 rounded-xl p-4">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-300">
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Staff Member</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Role</th>
                                    <th class="py-3 px-4 text-right font-semibold text-gray-700">Pay Rate</th>
                                    <th class="py-3 px-4 text-center font-semibold text-gray-700">Work Status</th>
                                    <th class="py-3 px-4 text-center font-semibold text-gray-700">Pay Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staffAssignments as $assignment)
                                <tr class="border-b border-gray-200 hover:bg-white">
                                    <td class="py-3 px-4 font-medium">{{ $assignment->user->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 capitalize">{{ $assignment->pivot->assignment_role ?? 'Staff'
                                        }}</td>
                                    <td class="py-3 px-4 text-right">₱{{ number_format($assignment->pivot->pay_rate ??
                                        0, 2) }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($assignment->pivot->work_status === 'finished') bg-green-100 text-green-800
                                            @elseif($assignment->pivot->work_status === 'ongoing') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($assignment->pivot->work_status ?? 'Assigned') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($assignment->pivot->pay_status === 'paid') bg-green-100 text-green-800
                                            @elseif($assignment->pivot->pay_status === 'approved') bg-blue-100 text-blue-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($assignment->pivot->pay_status ?? 'Pending') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-gray-300 bg-orange-50">
                                    <td colspan="2" class="py-3 px-4 text-right font-bold text-gray-900">Total Payroll:
                                    </td>
                                    <td class="py-3 px-4 text-right font-bold text-orange-700">₱{{
                                        number_format($staffAssignments->sum(fn($s) => $s->pivot->pay_rate ?? 0), 2) }}
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Feedback --}}
                @if($event->feedback)
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        Customer Feedback
                    </h3>
                    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-6 border border-yellow-200">
                        <div class="flex items-center gap-2 mb-3">
                            @for($i = 1; $i <= 5; $i++) <svg
                                class="w-6 h-6 {{ $i <= $event->feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                @endfor
                                <span class="ml-2 font-semibold text-gray-700">{{ $event->feedback->rating }}/5</span>
                        </div>
                        @if($event->feedback->comment)
                        <p class="text-gray-700 italic">"{{ $event->feedback->comment }}"</p>
                        @endif
                        <p class="text-sm text-gray-500 mt-3">Submitted on {{ $event->feedback->created_at->format('M d,
                            Y') }}</p>
                    </div>
                </div>
                @endif

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t border-gray-300 text-center text-sm text-gray-600">
                    <p>MichaelHo Events - Event Management System</p>
                    <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>