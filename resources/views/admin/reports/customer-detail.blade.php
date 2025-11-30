<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800">Individual Customer Report</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Customer Selection (if no customer selected) --}}
            @if(!isset($customer))
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-8 text-white">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Generate Customer Report</h3>
                            <p class="text-teal-100 mt-1">Select a customer to view their complete breakdown</p>
                        </div>
                    </div>
                </div>

                {{-- Search & Filter Section --}}
                <div class="p-6 border-b border-gray-200 bg-gray-50" x-data="customerSearch()">
                    <div class="flex flex-col md:flex-row gap-4">
                        {{-- Search Input --}}
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-4 ml-4 flex items-center pointer-events-none z-10">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" x-model="search" placeholder="Search by name, email, or phone..."
                                style="padding-left: 3rem;"
                                class="w-full py-3 rounded-xl border-gray-300 focus:ring-2 focus:ring-teal-200 focus:border-teal-400 text-gray-900">
                        </div>

                        {{-- Sort Dropdown --}}
                        <div class="md:w-56">
                            <select x-model="sortBy" @change="sortCustomers()"
                                class="w-full py-3 px-4 rounded-xl border-gray-300 focus:ring-2 focus:ring-teal-200 focus:border-teal-400">
                                <option value="name">Sort by Name</option>
                                <option value="events">Sort by Events (Most)</option>
                                <option value="spent">Sort by Spent (Highest)</option>
                                <option value="recent">Sort by Recent</option>
                            </select>
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="mt-4 flex flex-wrap gap-4 text-sm">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200">
                            <span class="w-2 h-2 bg-teal-500 rounded-full"></span>
                            <span class="text-gray-600">Total Customers:</span>
                            <span class="font-semibold text-gray-900">{{ $customers->count() }}</span>
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-gray-200">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            <span class="text-gray-600">With Events:</span>
                            <span class="font-semibold text-gray-900">{{ $customers->filter(fn($c) => ($c->events_count
                                ?? 0) > 0)->count() }}</span>
                        </span>
                        <span class="text-gray-400 ml-auto" x-show="search"
                            x-text="visibleCount + ' results found'"></span>
                    </div>

                    {{-- Customer List --}}
                    <div class="mt-6 grid gap-3 max-h-[500px] overflow-y-auto pr-2" id="customerList">
                        @foreach($customers as $c)
                        <a href="{{ route('admin.reports.customer-detail', ['customer_id' => $c->id]) }}"
                            class="customer-card block bg-white rounded-xl border-2 border-gray-200 hover:border-teal-400 hover:shadow-md transition-all p-4"
                            data-name="{{ strtolower($c->customer_name) }}" data-email="{{ strtolower($c->email) }}"
                            data-phone="{{ strtolower($c->phone ?? $c->contact_number ?? '') }}"
                            data-events="{{ $c->events_count ?? 0 }}" data-spent="{{ $c->total_spent ?? 0 }}"
                            x-show="filterCustomer('{{ addslashes(strtolower($c->customer_name)) }}', '{{ strtolower($c->email) }}', '{{ strtolower($c->phone ?? $c->contact_number ?? '') }}')"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0">
                            <div class="flex items-center gap-4">
                                {{-- Avatar --}}
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-lg">{{ strtoupper(substr($c->customer_name,
                                        0, 1)) }}</span>
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-semibold text-gray-900 truncate">{{ $c->customer_name }}</h4>
                                        @if(($c->events_count ?? 0) > 0)
                                        <span
                                            class="px-2 py-0.5 bg-teal-100 text-teal-700 text-xs font-medium rounded-full">
                                            {{ $c->events_count }} {{ Str::plural('event', $c->events_count) }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                                        <span class="flex items-center gap-1 truncate">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <span class="truncate">{{ $c->email }}</span>
                                        </span>
                                        @if($c->phone ?? $c->contact_number)
                                        <span class="flex items-center gap-1 flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $c->phone ?? $c->contact_number }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Total Spent --}}
                                @if(($c->total_spent ?? 0) > 0)
                                <div class="text-right hidden sm:block">
                                    <div class="text-xs text-gray-500">Total Spent</div>
                                    <div class="font-bold text-green-600">₱{{ number_format($c->total_spent ?? 0, 2) }}
                                    </div>
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
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <p class="text-gray-500">No customers found matching "<span x-text="search"></span>"</p>
                    </div>
                </div>
            </div>

            <script>
                function customerSearch() {
                    return {
                        search: '',
                        sortBy: 'name',
                        noResults: false,
                        visibleCount: {{ $customers->count() }},
                        filterCustomer(name, email, phone) {
                            if (!this.search) return true;
                            const query = this.search.toLowerCase().trim();
                            return name.includes(query) || email.includes(query) || phone.includes(query);
                        },
                        sortCustomers() {
                            const list = document.getElementById('customerList');
                            const cards = Array.from(list.querySelectorAll('.customer-card'));
                            
                            cards.sort((a, b) => {
                                switch(this.sortBy) {
                                    case 'events':
                                        return parseInt(b.dataset.events) - parseInt(a.dataset.events);
                                    case 'spent':
                                        return parseFloat(b.dataset.spent) - parseFloat(a.dataset.spent);
                                    case 'recent':
                                        return new Date(b.dataset.recent || 0) - new Date(a.dataset.recent || 0);
                                    default:
                                        return a.dataset.name.localeCompare(b.dataset.name);
                                }
                            });
                            
                            cards.forEach(card => list.appendChild(card));
                        },
                        init() {
                            this.$watch('search', () => {
                                this.$nextTick(() => {
                                    const cards = document.querySelectorAll('.customer-card');
                                    let visible = 0;
                                    cards.forEach(card => {
                                        if (card.style.display !== 'none') visible++;
                                    });
                                    this.visibleCount = visible;
                                    this.noResults = visible === 0 && this.search.length > 0;
                                });
                            });
                        }
                    }
                }
            </script>
            @else

            {{-- Export Buttons --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">{{ strtoupper(substr($customer->customer_name, 0, 1))
                            }}</span>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Viewing report for</div>
                        <div class="font-semibold text-gray-900">{{ $customer->customer_name }}</div>
                    </div>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('admin.reports.customer-detail') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Change Customer
                    </a>
                    <form method="GET" class="inline">
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="hidden" name="export" value="csv">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export CSV
                        </button>
                    </form>
                    <form method="GET" class="inline">
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
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
            <div class="bg-white rounded-lg shadow-sm p-8">

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
                            <h2 class="text-xl font-bold text-gray-900">Customer Detail Report</h2>
                            <p class="text-sm text-gray-600">
                                Generated: {{ now()->format('M d, Y g:i A') }}
                            </p>
                        </div>
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
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Full Name</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $customer->customer_name }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Email Address</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $customer->email }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Phone Number</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $customer->phone ??
                                    $customer->contact_number ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Address</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $customer->address ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Customer Since</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $customer->created_at->format('M d,
                                    Y') }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Account Status</div>
                                <div class="text-lg font-semibold">
                                    @if($customer->user)
                                    <span
                                        class="px-3 py-1 rounded-full text-sm {{ $customer->user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($customer->user->status) }}
                                    </span>
                                    @else
                                    <span class="px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800">No
                                        Account</span>
                                    @endif
                                </div>
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
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-5 border border-blue-200">
                            <div class="text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">Total Events
                            </div>
                            <div class="text-3xl font-bold text-blue-900">{{ $stats['total_events'] }}</div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg p-5 border border-amber-200">
                            <div class="text-xs font-semibold text-amber-700 uppercase tracking-wide mb-1">Total Billed
                            </div>
                            <div class="text-2xl font-bold text-amber-900">₱{{ number_format($stats['total_billed'], 2)
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
                            <div class="text-xs font-semibold text-red-700 uppercase tracking-wide mb-1">Outstanding
                                Balance</div>
                            <div class="text-2xl font-bold text-red-900">₱{{ number_format($stats['total_balance'], 2)
                                }}</div>
                        </div>
                    </div>
                </div>

                {{-- Events Breakdown --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Events Breakdown
                    </h3>

                    @forelse($events as $event)
                    <div class="border border-gray-200 rounded-xl mb-6 overflow-hidden">
                        {{-- Event Header --}}
                        <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ $event->name }}</h4>
                                    <div class="flex items-center gap-4 mt-1 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $event->venue ?? 'TBD' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($event->status === 'completed') bg-green-100 text-green-800
                                        @elseif($event->status === 'scheduled') bg-blue-100 text-blue-800
                                        @elseif($event->status === 'rejected' || $event->status === 'cancelled') bg-red-100 text-red-800
                                        @elseif(in_array($event->status, ['approved', 'request_meeting', 'meeting'])) bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucwords(str_replace('_', ' ', $event->status)) }}
                                    </span>
                                    @if($event->package)
                                    <div class="text-sm text-gray-600 mt-1">{{ $event->package->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Inclusions --}}
                                <div>
                                    <h5 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Inclusions ({{ $event->inclusions->count() }})
                                    </h5>
                                    @if($event->inclusions->count() > 0)
                                    <div class="bg-gray-50 rounded-lg p-4 max-h-48 overflow-y-auto">
                                        <table class="w-full text-sm">
                                            @foreach($event->inclusions as $inclusion)
                                            <tr class="border-b border-gray-200 last:border-0">
                                                <td class="py-2 text-gray-700">{{ $inclusion->name }}</td>
                                                <td class="py-2 text-right font-medium text-gray-900">
                                                    ₱{{ number_format($inclusion->pivot->price_snapshot ??
                                                    $inclusion->price, 2) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    @else
                                    <p class="text-sm text-gray-500 italic">No inclusions recorded</p>
                                    @endif
                                </div>

                                {{-- Billing Breakdown --}}
                                <div>
                                    <h5 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Billing Breakdown
                                    </h5>
                                    @if($event->billing)
                                    @php
                                    $inclTotal = $event->inclusions->sum(fn($i) => $i->pivot->price_snapshot ??
                                    $i->price);
                                    $coordPrice = $event->package->coordination_price ?? 25000;
                                    $stylingPrice = $event->package->event_styling_price ?? 55000;
                                    @endphp
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <table class="w-full text-sm">
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 text-gray-600">Coordination Fee</td>
                                                <td class="py-2 text-right font-medium">₱{{ number_format($coordPrice,
                                                    2) }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 text-gray-600">Event Styling Fee</td>
                                                <td class="py-2 text-right font-medium">₱{{ number_format($stylingPrice,
                                                    2) }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 text-gray-600">Inclusions Total</td>
                                                <td class="py-2 text-right font-medium">₱{{ number_format($inclTotal, 2)
                                                    }}</td>
                                            </tr>
                                            <tr class="bg-amber-50">
                                                <td class="py-2 font-semibold text-gray-900">Grand Total</td>
                                                <td class="py-2 text-right font-bold text-amber-700">₱{{
                                                    number_format($event->billing->total_amount, 2) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    @else
                                    <p class="text-sm text-gray-500 italic">No billing information</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Payments for this event --}}
                            @if($event->billing && $event->billing->payments->count() > 0)
                            <div class="mt-6">
                                <h5 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Payment History
                                </h5>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-gray-300 bg-gray-50">
                                                <th class="py-2 px-3 text-left font-medium text-gray-700">Date</th>
                                                <th class="py-2 px-3 text-left font-medium text-gray-700">Type</th>
                                                <th class="py-2 px-3 text-left font-medium text-gray-700">Method</th>
                                                <th class="py-2 px-3 text-right font-medium text-gray-700">Amount</th>
                                                <th class="py-2 px-3 text-center font-medium text-gray-700">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($event->billing->payments as $payment)
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 px-3">{{ $payment->created_at->format('M d, Y') }}</td>
                                                <td class="py-2 px-3 capitalize">{{ str_replace('_', ' ',
                                                    $payment->payment_type) }}</td>
                                                <td class="py-2 px-3 capitalize">{{ str_replace('_', ' ',
                                                    $payment->payment_method) }}</td>
                                                <td class="py-2 px-3 text-right font-medium">₱{{
                                                    number_format($payment->amount, 2) }}</td>
                                                <td class="py-2 px-3 text-center">
                                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                                        @if($payment->status === 'approved') bg-green-100 text-green-800
                                                        @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Event Payment Summary --}}
                                @php
                                $eventPaid = $event->billing->payments->where('status', 'approved')->sum('amount');
                                $eventBalance = $event->billing->total_amount - $eventPaid;
                                @endphp
                                <div class="mt-4 flex justify-end gap-6 text-sm">
                                    <div>
                                        <span class="text-gray-600">Paid:</span>
                                        <span class="font-bold text-green-700 ml-1">₱{{ number_format($eventPaid, 2)
                                            }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Balance:</span>
                                        <span
                                            class="font-bold {{ $eventBalance > 0 ? 'text-red-700' : 'text-green-700' }} ml-1">
                                            ₱{{ number_format($eventBalance, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 bg-gray-50 rounded-xl">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500">This customer has no events yet.</p>
                    </div>
                    @endforelse
                </div>

                {{-- All Payments Summary --}}
                @if($allPayments->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Complete Payment History
                    </h3>
                    <div class="overflow-x-auto bg-gray-50 rounded-xl p-4">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-300">
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Date</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Event</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Type</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Method</th>
                                    <th class="py-3 px-4 text-right font-semibold text-gray-700">Amount</th>
                                    <th class="py-3 px-4 text-center font-semibold text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allPayments as $payment)
                                <tr class="border-b border-gray-200 hover:bg-white">
                                    <td class="py-3 px-4">{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td class="py-3 px-4 font-medium">{{ $payment->billing->event->name ?? '-' }}</td>
                                    <td class="py-3 px-4 capitalize">{{ str_replace('_', ' ', $payment->payment_type) }}
                                    </td>
                                    <td class="py-3 px-4 capitalize">{{ str_replace('_', ' ', $payment->payment_method)
                                        }}</td>
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