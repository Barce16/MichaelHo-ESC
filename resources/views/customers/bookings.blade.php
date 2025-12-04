<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Bookings') }}
            </h2>
            <a href="{{ route('customer.events.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-violet-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-violet-700 hover:to-purple-700 transition shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Book New Event
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div
                    class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-violet-100 uppercase tracking-wide">Total Events</p>
                            <p class="text-4xl font-bold mt-1">{{ $totalEvents }}</p>
                            <p class="text-xs text-violet-100 mt-1">All time bookings</p>
                        </div>
                        <div
                            class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-sky-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-sky-100 uppercase tracking-wide">Upcoming</p>
                            <p class="text-4xl font-bold mt-1">{{ $upcomingEvents }}</p>
                            <p class="text-xs text-sky-100 mt-1">Events scheduled</p>
                        </div>
                        <div
                            class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-amber-100 uppercase tracking-wide">Pending</p>
                            <p class="text-4xl font-bold mt-1">{{ $pendingEvents }}</p>
                            <p class="text-xs text-amber-100 mt-1">Awaiting approval</p>
                        </div>
                        <div
                            class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-emerald-100 uppercase tracking-wide">Completed</p>
                            <p class="text-4xl font-bold mt-1">{{ $completedEvents }}</p>
                            <p class="text-xs text-emerald-100 mt-1">Successful events</p>
                        </div>
                        <div
                            class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Events by Status Chart --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                            Events by Status
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Distribution of your event statuses</p>
                    </div>
                    <div class="relative h-64">
                        <canvas id="customerStatusChart"></canvas>
                    </div>
                </div>

                {{-- Payment History Chart --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 36 36"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z"></path>
                                <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                <path
                                    d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                </path>
                            </svg>
                            Payment Overview
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Your payment history</p>
                    </div>
                    <div class="relative h-64">
                        <canvas id="customerPaymentChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Payment Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-5 border border-emerald-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-emerald-700 font-semibold uppercase tracking-wide">Total Paid</p>
                            <p class="text-2xl font-bold text-emerald-900">₱{{ number_format($totalPaid, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-5 border border-amber-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-amber-700 font-semibold uppercase tracking-wide">Pending</p>
                            <p class="text-2xl font-bold text-amber-900">₱{{ number_format($totalPending, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl p-5 border border-rose-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-rose-700 font-semibold uppercase tracking-wide">Remaining Balance</p>
                            <p class="text-2xl font-bold text-rose-900">₱{{ number_format($totalBalance, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Events Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Recent Events
                        </h3>
                        <a href="{{ route('customer.events.index') }}"
                            class="text-sm text-violet-600 hover:text-violet-700 font-medium">
                            View All →
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Event</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Package</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentEvents as $e)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $e->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $e->venue ?: '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($e->event_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $e->package?->name ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $statusConfig = match(strtolower($e->status)) {
                                    'requested' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' =>
                                    'border-amber-200', 'dot' => 'bg-amber-500'],
                                    'approved' => ['bg' => 'bg-sky-100', 'text' => 'text-sky-700', 'border' =>
                                    'border-sky-200', 'dot' => 'bg-sky-500'],
                                    'meeting' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' =>
                                    'border-orange-200', 'dot' => 'bg-orange-500'],
                                    'request_meeting' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border'
                                    => 'border-orange-200', 'dot' => 'bg-orange-500'],
                                    'scheduled' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'border' =>
                                    'border-violet-200', 'dot' => 'bg-violet-500'],
                                    'ongoing' => ['bg' => 'bg-teal-100', 'text' => 'text-teal-700', 'border' =>
                                    'border-teal-200', 'dot' => 'bg-teal-500'],
                                    'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' =>
                                    'border-emerald-200', 'dot' => 'bg-emerald-500'],
                                    'cancelled' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'border' =>
                                    'border-rose-200', 'dot' => 'bg-rose-500'],
                                    'rejected' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'border' =>
                                    'border-rose-200', 'dot' => 'bg-rose-500'],
                                    default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' =>
                                    'border-slate-200', 'dot' => 'bg-slate-500'],
                                    };
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} border">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                        {{ ucwords(str_replace('_', ' ', strtolower($e->status))) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('customer.events.show', $e) }}"
                                        class="inline-flex items-center gap-1 text-violet-600 hover:text-violet-800 font-medium transition">
                                        View Details
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="font-medium">No events yet</p>
                                    <p class="text-sm mt-1">Book your first event to get started!</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Available Packages --}}
            @if(!empty($packages) && $packages->count())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Available Packages
                    </h3>
                    <p class="text-gray-500 mt-1">Choose the perfect package for your next event</p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($packages as $package)
                    @php
                    $inclusions = $package->inclusions ?? collect();
                    $styling = is_array($package->event_styling ?? null) ? $package->event_styling : [];
                    $images = $package->images ?? collect();
                    $mainImage = $images->first();
                    @endphp

                    <div
                        class="group bg-white border-2 border-gray-200 hover:border-violet-400 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                        {{-- Featured Image --}}
                        <div class="relative h-48 overflow-hidden bg-gradient-to-br from-violet-100 to-purple-100">
                            @if($mainImage)
                            <img src="{{ asset('storage/' . $mainImage->path) }}"
                                alt="{{ $mainImage->alt ?? $package->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-violet-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            @endif
                            <div class="absolute top-3 right-3">
                                <span
                                    class="px-3 py-1 bg-white/90 backdrop-blur-sm text-violet-700 text-xs font-bold rounded-full shadow">
                                    {{ $package->type ?? 'Package' }}
                                </span>
                            </div>
                        </div>

                        {{-- Package Info --}}
                        <div class="p-5">
                            <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $package->name }}</h4>

                            @if($inclusions->count() > 0)
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-2">Includes {{ $inclusions->count() }} items:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($inclusions->take(4) as $inc)
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">{{
                                        $inc->name }}</span>
                                    @endforeach
                                    @if($inclusions->count() > 4)
                                    <span class="px-2 py-0.5 bg-violet-100 text-violet-600 text-xs rounded-full">+{{
                                        $inclusions->count() - 4 }} more</span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <a href="{{ route('customer.events.create', ['package_id' => $package->id]) }}"
                                class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-violet-600 to-purple-600 text-white font-semibold rounded-lg hover:from-violet-700 hover:to-purple-700 transition shadow-md hover:shadow-lg">
                                Book This Package
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Chart.js Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Customer Events Status - Doughnut Chart
            const customerStatusCtx = document.getElementById('customerStatusChart');
            if (customerStatusCtx) {
                new Chart(customerStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Upcoming', 'Completed', 'Cancelled'],
                        datasets: [{
                            data: {!! json_encode($customerStatusData ?? [0, 0, 0]) !!},
                            backgroundColor: [
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                            ],
                            borderColor: [
                                'rgb(139, 92, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(239, 68, 68)',
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    font: {
                                        size: 12,
                                        weight: '600'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.parsed + ' events';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Customer Payment History - Bar Chart
            const customerPaymentCtx = document.getElementById('customerPaymentChart');
            if (customerPaymentCtx) {
                new Chart(customerPaymentCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($customerPaymentLabels ?? []) !!},
                        datasets: [{
                            label: 'Payments (₱)',
                            data: {!! json_encode($customerPaymentData ?? []) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        return '₱' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000) {
                                            return '₱' + (value / 1000) + 'k';
                                        }
                                        return '₱' + value.toLocaleString();
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>