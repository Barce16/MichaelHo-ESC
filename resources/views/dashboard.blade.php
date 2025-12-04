<x-app-layout>
    @php
    $user = auth()->user();
    $isCustomer = $user->user_type === 'customer';
    $isStaff = $user->user_type === 'staff';
    $isAdmin = $user->user_type === 'admin';
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $isCustomer ? __('My Dashboard') : ($isStaff ? __('My Workspace') : __('Dashboard')) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if($isAdmin)
            {{-- ================= ADMIN VIEW ================= --}}

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div
                    class="bg-gradient-to-br from-violet-500 to-purple-600 shadow-lg rounded-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-violet-100 uppercase tracking-wide">Total Events
                            </div>
                            <div class="text-4xl font-bold mt-2">{{ $totalEvents ?? 0 }}</div>
                            <div class="text-xs text-violet-100 mt-1">All time bookings</div>
                        </div>
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg rounded-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-emerald-100 uppercase tracking-wide">Customers</div>
                            <div class="text-4xl font-bold mt-2">{{ $totalCustomers ?? 0 }}</div>
                            <div class="text-xs text-emerald-100 mt-1">Registered clients</div>
                        </div>
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-sky-500 to-blue-600 shadow-lg rounded-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-sky-100 uppercase tracking-wide">This Month</div>
                            <div class="text-3xl font-bold mt-2">₱{{ number_format($paymentsThisMonth ?? 0, 0) }}</div>
                            <div class="text-xs text-sky-100 mt-1">Revenue collected</div>
                        </div>
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 36 36">
                                <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z"></path>
                                <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                <path
                                    d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                </path>
                            </svg>

                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg rounded-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-amber-100 uppercase tracking-wide">Pending Tasks
                            </div>
                            <div class="text-4xl font-bold mt-2">{{ $pendingTasks ?? 0 }}</div>
                            <div class="text-xs text-amber-100 mt-1">Require attention</div>
                        </div>
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                        <p class="text-sm text-gray-500 mt-1">Distribution of event statuses</p>
                    </div>
                    <div class="relative h-64">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                {{-- Monthly Revenue Chart --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Monthly Revenue
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Revenue trend over the past 6 months</p>
                    </div>
                    <div class="relative h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Events per Month Chart --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Events Timeline
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Number of events per month this year</p>
                </div>
                <div class="relative h-80">
                    <canvas id="eventsChart"></canvas>
                </div>
            </div>

            {{-- Recent Events Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Recent Events
                    </h3>
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
                                    Customer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentEvents ?? [] as $event)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $event->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $event->venue ?: '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $event->customer->customer_name ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $statusConfig = match(strtolower($event->status)) {
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
                                        {{ ucwords(str_replace('_', ' ', strtolower($event->status))) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">No recent events.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @elseif($isStaff)
            {{-- ================= STAFF VIEW ================= --}}

            {{-- Welcome Message --}}
            <div class="bg-gradient-to-r from-slate-500 via-gray-500 to-gray-500 rounded-xl shadow-lg p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Welcome back, {{ $user->name }}! 👋</h2>
                        <p class="text-indigo-100">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
                    </div>
                    <div class="hidden md:block">
                        <svg class="w-24 h-24 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Staff Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div
                    class="bg-gradient-to-br from-blue-500 to-cyan-600 shadow-lg rounded-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-blue-100 uppercase tracking-wide">Assigned Events
                            </div>
                            <div class="text-4xl font-bold mt-2">{{ $staffAssignedEvents ?? 0 }}</div>
                            <div class="text-xs text-blue-100 mt-1">Active assignments</div>
                        </div>
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg rounded-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-emerald-100 uppercase tracking-wide">Completed Tasks
                            </div>
                            <div class="text-4xl font-bold mt-2">{{ $staffCompletedTasks ?? 0 }}</div>
                            <div class="text-xs text-emerald-100 mt-1">This month</div>
                        </div>
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-violet-500 to-purple-600 shadow-lg rounded-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-violet-100 uppercase tracking-wide">Hours This Month
                            </div>
                            <div class="text-4xl font-bold mt-2">{{ $staffHoursThisMonth ?? 0 }}</div>
                            <div class="text-xs text-violet-100 mt-1">Tracked hours</div>
                        </div>
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg rounded-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-amber-100 uppercase tracking-wide">Monthly Earnings
                            </div>
                            <div class="text-3xl font-bold mt-2">₱{{ number_format($staffEarningsThisMonth ?? 0, 0) }}
                            </div>
                            <div class="text-xs text-amber-100 mt-1">This month</div>
                        </div>
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 36 36">
                                <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z"></path>
                                <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                <path
                                    d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Staff Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Upcoming Schedule --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            My Weekly Schedule
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Events assigned to you this month</p>
                    </div>
                    <div class="relative h-64">
                        <canvas id="staffScheduleChart"></canvas>
                    </div>
                </div>

                {{-- Monthly Earnings Chart --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Earnings Trend
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Your earnings over the past 6 months</p>
                    </div>
                    <div class="relative h-64">
                        <canvas id="staffEarningsChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Today's Schedule --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Today's Schedule
                    </h3>
                </div>
                <div class="p-6">
                    @if(!empty($todaysSchedule) && count($todaysSchedule) > 0)
                    <div class="space-y-4">
                        @foreach($todaysSchedule as $event)
                        <div
                            class="flex items-start gap-4 p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg border border-blue-200 hover:shadow-md transition">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                                    <div class="text-center">
                                        <div class="text-xs font-semibold">{{
                                            \Carbon\Carbon::parse($event->event_date)->format('M') }}</div>
                                        <div class="text-2xl font-bold">{{
                                            \Carbon\Carbon::parse($event->event_date)->format('d') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">{{ $event->name }}</h4>
                                <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $event->venue ?? 'Location TBD' }}
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $event->customer->customer_name ?? 'Client' }}
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                @php
                                $statusConfig = match(strtolower($event->status)) {
                                'scheduled' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'border' =>
                                'border-violet-200'],
                                'approved' => ['bg' => 'bg-sky-100', 'text' => 'text-sky-700', 'border' =>
                                'border-sky-200'],
                                'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' =>
                                'border-emerald-200'],
                                default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' =>
                                'border-slate-200'],
                                };
                                @endphp
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} border">
                                    {{ ucwords(str_replace('_', ' ', $event->status)) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500 font-medium">No events scheduled for today</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- My Assigned Events --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        My Assigned Events
                    </h3>
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
                                    Customer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Role</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($staffAssignedEventsList ?? [] as $event)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $event->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $event->venue ?? '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $event->customer->customer_name ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-lg">
                                        {{ $event->assignment_role ?? 'Staff' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $statusConfig = match(strtolower($event->status)) {
                                    'scheduled' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'border' =>
                                    'border-violet-200', 'dot' => 'bg-violet-500'],
                                    'approved' => ['bg' => 'bg-sky-100', 'text' => 'text-sky-700', 'border' =>
                                    'border-sky-200', 'dot' => 'bg-sky-500'],
                                    'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' =>
                                    'border-emerald-200', 'dot' => 'bg-emerald-500'],
                                    default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' =>
                                    'border-slate-200', 'dot' => 'bg-slate-500'],
                                    };
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} border">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                        {{ ucwords(str_replace('_', ' ', $event->status)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">No assigned events yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Payroll Summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Payroll Summary
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Your earnings breakdown</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-lg p-5 border border-emerald-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 36 36"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                    </path>
                                    <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"></path>
                                    <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"></path>
                                    <path
                                        d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-emerald-700 font-semibold uppercase tracking-wide">This Month
                                </div>
                                <div class="text-2xl font-bold text-emerald-900">₱{{
                                    number_format($staffEarningsThisMonth ?? 0, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-5 border border-blue-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-blue-700 font-semibold uppercase tracking-wide">Last Month
                                </div>
                                <div class="text-2xl font-bold text-blue-900">₱{{ number_format($staffEarningsLastMonth
                                    ?? 0, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-violet-50 to-purple-50 rounded-lg p-5 border border-violet-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-violet-700 font-semibold uppercase tracking-wide">Total Earned
                                </div>
                                <div class="text-2xl font-bold text-violet-900">₱{{ number_format($staffTotalEarnings ??
                                    0, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @else
            {{-- ================= CUSTOMER VIEW - NEW SCHEDULES & PROGRESS FOCUSED ================= --}}

            {{-- Welcome Section --}}
            <div class="bg-gradient-to-r from-violet-600 to-purple-700 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                        <p class="text-violet-100">Here's what's happening with your events</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('customer.events.create') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-violet-700 font-semibold rounded-xl hover:bg-violet-50 transition shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Book New Event
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalEvents ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Total Events</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $upcoming ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Upcoming</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $upcomingSchedules->count() ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Schedules</p>
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
                            <p class="text-2xl font-bold text-gray-900">{{ $completed ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Completed</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Event Progress Panel - Unified Timeline --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Event Progress</h3>
                                <p class="text-sm text-white/80">Schedules & preparation milestones</p>
                            </div>
                        </div>

                        {{-- Event Filter Button & Modal --}}
                        @if(isset($activeEvents))
                        <div x-data="{ open: false, selectedEvent: 'all', selectedName: 'All Events' }"
                            class="relative">
                            {{-- Filter Button --}}
                            <button @click="open = !open"
                                class="flex items-center gap-2 px-3 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg transition border border-white/30">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                <span class="text-sm font-medium text-white truncate max-w-[120px]"
                                    x-text="selectedName"></span>
                                <svg class="w-4 h-4 text-white/80 transition-transform" :class="{ 'rotate-180': open }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Floating Modal Dropdown --}}
                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1" @click.away="open = false"
                                class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden z-50">

                                {{-- Modal Header --}}
                                <div
                                    class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-semibold text-gray-900 text-sm">Filter by Event</h4>
                                        <button @click="open = false"
                                            class="text-gray-400 hover:text-gray-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Event List --}}
                                <div class="max-h-64 overflow-y-auto p-2">
                                    {{-- All Events Option --}}
                                    <button
                                        @click="selectedEvent = 'all'; selectedName = 'All Events'; filterTimeline('all'); open = false"
                                        :class="selectedEvent === 'all' ? 'bg-indigo-50 border-indigo-200' : 'bg-white border-transparent hover:bg-gray-50'"
                                        class="w-full flex items-center gap-3 p-3 rounded-lg border-2 transition mb-2">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 text-left">
                                            <p class="font-semibold text-gray-900 text-sm">All Events</p>
                                            <p class="text-xs text-gray-500">View all activity</p>
                                        </div>
                                        <div x-show="selectedEvent === 'all'" class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>

                                    {{-- Individual Events --}}
                                    @foreach($activeEvents as $evt)
                                    @php
                                    $statusColors = [
                                    'requested' => 'from-amber-400 to-orange-500',
                                    'approved' => 'from-sky-400 to-blue-500',
                                    'request_meeting' => 'from-orange-400 to-red-500',
                                    'meeting' => 'from-blue-400 to-indigo-500',
                                    'scheduled' => 'from-violet-400 to-purple-500',
                                    'ongoing' => 'from-teal-400 to-emerald-500',
                                    ];
                                    $gradientClass = $statusColors[$evt->status] ?? 'from-gray-400 to-gray-500';
                                    @endphp
                                    <button
                                        @click="selectedEvent = '{{ $evt->id }}'; selectedName = '{{ Str::limit($evt->name, 20) }}'; filterTimeline('{{ $evt->id }}'); open = false"
                                        :class="selectedEvent === '{{ $evt->id }}' ? 'bg-indigo-50 border-indigo-200' : 'bg-white border-transparent hover:bg-gray-50'"
                                        class="w-full flex items-center gap-3 p-3 rounded-lg border-2 transition mb-2 last:mb-0">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br {{ $gradientClass }} rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 text-left min-w-0">
                                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $evt->name }}</p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-xs text-gray-500">{{
                                                    \Carbon\Carbon::parse($evt->event_date)->format('M d, Y') }}</span>
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium
                                                    {{ $evt->status === 'scheduled' ? 'bg-violet-100 text-violet-700' : '' }}
                                                    {{ $evt->status === 'ongoing' ? 'bg-teal-100 text-teal-700' : '' }}
                                                    {{ $evt->status === 'meeting' ? 'bg-blue-100 text-blue-700' : '' }}
                                                    {{ $evt->status === 'approved' ? 'bg-sky-100 text-sky-700' : '' }}
                                                    {{ $evt->status === 'request_meeting' ? 'bg-orange-100 text-orange-700' : '' }}
                                                    {{ $evt->status === 'requested' ? 'bg-amber-100 text-amber-700' : '' }}">
                                                    {{ ucwords(str_replace('_', ' ', $evt->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div x-show="selectedEvent === '{{ $evt->id }}'" class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                    @endforeach
                                </div>

                                {{-- Modal Footer --}}
                                <div class="bg-gray-50 px-4 py-2 border-t border-gray-200">
                                    <p class="text-xs text-gray-500 text-center">{{ $activeEvents->count() }} active
                                        events</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Unified Timeline Content --}}
                <div class="max-h-[500px] overflow-y-auto">
                    <div class="p-5">
                        @php
                        // Merge schedules and progress into unified timeline
                        $timelineItems = collect();

                        // Add schedules
                        foreach($upcomingSchedules ?? [] as $schedule) {
                        $timelineItems->push([
                        'type' => 'schedule',
                        'date' => \Carbon\Carbon::parse($schedule->scheduled_date),
                        'data' => $schedule,
                        'event_id' => $schedule->event_id,
                        'event_name' => $schedule->event->name ?? 'Event',
                        ]);
                        }

                        // Add progress from all active events
                        foreach($activeEvents ?? [] as $event) {
                        foreach($event->progress ?? [] as $progress) {
                        $timelineItems->push([
                        'type' => 'progress',
                        'date' => \Carbon\Carbon::parse($progress->progress_date),
                        'data' => $progress,
                        'event_id' => $event->id,
                        'event_name' => $event->name,
                        ]);
                        }
                        }

                        // Sort by date descending (newest first)
                        $timelineItems = $timelineItems->sortByDesc('date')->take(15);
                        @endphp

                        @if($timelineItems->count() > 0)
                        <div class="relative">
                            {{-- Timeline Line --}}
                            <div
                                class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-indigo-500 via-purple-400 to-gray-200">
                            </div>

                            <div class="space-y-4">
                                @foreach($timelineItems as $index => $item)
                                <div class="relative pl-10 group timeline-item" data-event-id="{{ $item['event_id'] }}">
                                    @if($item['type'] === 'schedule')
                                    @php
                                    $schedule = $item['data'];
                                    $scheduleDate = $item['date'];
                                    $isToday = $scheduleDate->isToday();
                                    $isPast = $scheduleDate->isPast() && !$isToday;
                                    $hasProof = !empty($schedule->proof_image);
                                    $dotColor = $hasProof ? 'bg-emerald-500' : ($isPast ? 'bg-rose-500' : ($isToday ?
                                    'bg-blue-500' : 'bg-amber-500'));
                                    $ringColor = $hasProof ? 'ring-emerald-100' : ($isPast ? 'ring-rose-100' : ($isToday
                                    ? 'ring-blue-100' : 'ring-amber-100'));
                                    @endphp

                                    {{-- Timeline Dot --}}
                                    <div
                                        class="absolute left-2 top-2 w-5 h-5 rounded-full border-4 border-white shadow-md transition-all {{ $dotColor }} {{ $loop->first ? 'ring-4 ' . $ringColor : '' }}">
                                    </div>

                                    {{-- Schedule Card --}}
                                    <div
                                        class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition group-hover:border-indigo-200">
                                        {{-- Type Badge --}}
                                        <div class="flex items-center gap-2 mb-3">
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Schedule
                                            </span>
                                            <span class="text-xs text-gray-400">{{ $item['event_name'] }}</span>
                                            @if($loop->first)
                                            <span
                                                class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">Latest</span>
                                            @endif
                                        </div>

                                        <div class="flex items-start gap-3">
                                            {{-- Inclusion Image --}}
                                            <div class="flex-shrink-0">
                                                @if($schedule->inclusion && $schedule->inclusion->image)
                                                <img src="{{ asset('storage/' . $schedule->inclusion->image) }}"
                                                    alt="{{ $schedule->inclusion->name }}"
                                                    class="w-12 h-12 rounded-lg object-cover border-2 {{ $hasProof ? 'border-emerald-300' : ($isPast ? 'border-rose-300' : ($isToday ? 'border-blue-300' : 'border-amber-300')) }}">
                                                @else
                                                <div
                                                    class="w-12 h-12 rounded-lg {{ $hasProof ? 'bg-emerald-100' : ($isPast ? 'bg-rose-100' : ($isToday ? 'bg-blue-100' : 'bg-amber-100')) }} flex items-center justify-center">
                                                    <svg class="w-6 h-6 {{ $hasProof ? 'text-emerald-500' : ($isPast ? 'text-rose-500' : ($isToday ? 'text-blue-500' : 'text-amber-500')) }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                    </svg>
                                                </div>
                                                @endif
                                            </div>

                                            {{-- Details --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2">
                                                    <h4 class="font-semibold text-gray-900 text-sm">
                                                        {{ $schedule->inclusion->name ?? 'Unknown' }}
                                                    </h4>
                                                    {{-- Status Badge --}}
                                                    @if($hasProof)
                                                    <span
                                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Done
                                                    </span>
                                                    @elseif($isPast)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Overdue</span>
                                                    @elseif($isToday)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Today</span>
                                                    @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Upcoming</span>
                                                    @endif
                                                </div>

                                                {{-- Date & Time --}}
                                                <div
                                                    class="flex flex-wrap items-center gap-3 mt-2 text-xs text-gray-500">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ $scheduleDate->format('M d, Y') }}
                                                    </span>
                                                    @if($schedule->scheduled_time)
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i
                                                        A') }}
                                                    </span>
                                                    @endif
                                                    @if($schedule->venue)
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        </svg>
                                                        {{ Str::limit($schedule->venue, 20) }}
                                                    </span>
                                                    @endif
                                                </div>

                                                @if($schedule->remarks)
                                                <p class="text-xs text-gray-600 mt-2 leading-relaxed">{{
                                                    $schedule->remarks }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @else
                                    @php
                                    $progress = $item['data'];
                                    @endphp

                                    {{-- Timeline Dot --}}
                                    <div
                                        class="absolute left-2 top-2 w-5 h-5 rounded-full border-4 border-white shadow-md transition-all
                                            {{ $loop->first ? 'bg-indigo-500 ring-4 ring-indigo-100' : 'bg-indigo-400 group-hover:bg-indigo-500' }}">
                                    </div>

                                    {{-- Progress Card --}}
                                    <div
                                        class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition group-hover:border-indigo-200">
                                        {{-- Type Badge --}}
                                        <div class="flex items-center gap-2 mb-3">
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Progress
                                            </span>
                                            <span class="text-xs text-gray-400">{{ $item['event_name'] }}</span>
                                            @if($loop->first)
                                            <span
                                                class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">Latest</span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800">
                                                {{ $progress->status }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ $item['date']->format('M d, Y') }}
                                            </span>
                                        </div>
                                        @if($progress->details)
                                        <p class="text-gray-600 text-sm mt-2 leading-relaxed">{{ $progress->details }}
                                        </p>
                                        @endif
                                        <div class="text-xs text-gray-400 mt-2">
                                            Added {{ \Carbon\Carbon::parse($progress->created_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-500 font-medium mb-2">No activity yet</p>
                            <p class="text-gray-400 text-sm mb-4">Progress and schedules will appear as we prepare your
                                event</p>
                            <a href="{{ route('customer.events.create') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Book Your First Event
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Footer Legend --}}
                @if($timelineItems->count() > 0)
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Progress
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Schedule
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Completed
                        </span>
                    </div>
                </div>
                @endif
            </div>

            {{-- Event Filter Script --}}
            <script>
                function filterTimeline(eventId) {
                    const items = document.querySelectorAll('.timeline-item');
                    items.forEach(item => {
                        if (eventId === 'all' || item.dataset.eventId === eventId) {
                            item.style.display = '';
                            item.style.opacity = '0';
                            item.style.transform = 'translateX(-10px)';
                            setTimeout(() => {
                                item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                                item.style.opacity = '1';
                                item.style.transform = 'translateX(0)';
                            }, 50);
                        } else {
                            item.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                            item.style.opacity = '0';
                            item.style.transform = 'translateX(-10px)';
                            setTimeout(() => {
                                item.style.display = 'none';
                            }, 200);
                        }
                    });
                }
            </script>

            {{-- Action Required Section --}}
            @if(isset($pendingActions) && $pendingActions->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-rose-50 to-pink-50 border-b border-gray-200 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-rose-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Action Required</h3>
                            <p class="text-xs text-gray-500">These items need your attention</p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($pendingActions as $action)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                {{ $action['type'] === 'payment' ? 'bg-emerald-100' : 'bg-amber-100' }}">
                                @if($action['type'] === 'payment')
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                @else
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 text-sm">{{ $action['title'] }}</h4>
                                <p class="text-xs text-gray-500">{{ $action['description'] }}</p>
                            </div>
                        </div>
                        <a href="{{ $action['url'] }}"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-violet-600 to-purple-600 rounded-lg hover:from-violet-700 hover:to-purple-700 transition shadow-sm">
                            {{ $action['button'] }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quick Links --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Quick Navigation</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('customer.events.index') }}"
                        class="flex flex-col items-center justify-center gap-2 p-4 bg-violet-50 border border-violet-200 rounded-xl hover:bg-violet-100 hover:border-violet-300 transition group">
                        <div
                            class="w-10 h-10 bg-violet-100 group-hover:bg-violet-200 rounded-lg flex items-center justify-center transition">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">My Events</span>
                    </a>

                    <a href="{{ route('customer.bookings') }}"
                        class="flex flex-col items-center justify-center gap-2 p-4 bg-sky-50 border border-sky-200 rounded-xl hover:bg-sky-100 hover:border-sky-300 transition group">
                        <div
                            class="w-10 h-10 bg-sky-100 group-hover:bg-sky-200 rounded-lg flex items-center justify-center transition">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">My Bookings</span>
                    </a>

                    <a href="{{ route('customer.billings') }}"
                        class="flex flex-col items-center justify-center gap-2 p-4 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 hover:border-emerald-300 transition group">
                        <div
                            class="w-10 h-10 bg-emerald-100 group-hover:bg-emerald-200 rounded-lg flex items-center justify-center transition">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Billings</span>
                    </a>

                    <a href="{{ route('customer.payments.index') }}"
                        class="flex flex-col items-center justify-center gap-2 p-4 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 hover:border-amber-300 transition group">
                        <div
                            class="w-10 h-10 bg-amber-100 group-hover:bg-amber-200 rounded-lg flex items-center justify-center transition">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Payments</span>
                    </a>
                </div>
            </div>

            @endif
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    {{-- Chart Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($isAdmin)
            // ============ ADMIN CHARTS ============
            
            // Events by Status - Doughnut Chart
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Requested', 'Approved', 'Scheduled', 'Completed', 'Cancelled'],
                        datasets: [{
                            data: {!! json_encode($statusData ?? [0, 0, 0, 0, 0]) !!},
                            backgroundColor: [
                                'rgba(251, 191, 36, 0.8)',
                                'rgba(14, 165, 233, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                            ],
                            borderColor: [
                                'rgb(251, 191, 36)',
                                'rgb(14, 165, 233)',
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
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 13
                                },
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

            // Monthly Revenue - Line Chart
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($revenueLabels ?? []) !!},
                        datasets: [{
                            label: 'Revenue (₱)',
                            data: {!! json_encode($revenueData ?? []) !!},
                            borderColor: 'rgb(14, 165, 233)',
                            backgroundColor: 'rgba(14, 165, 233, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgb(14, 165, 233)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
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

            // Events per Month - Bar Chart
            const eventsCtx = document.getElementById('eventsChart');
            if (eventsCtx) {
                new Chart(eventsCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($eventsLabels ?? []) !!},
                        datasets: [{
                            label: 'Events',
                            data: {!! json_encode($eventsData ?? []) !!},
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
                                        return context.parsed.y + ' events';
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
                                    stepSize: 1
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
            
            @elseif($isStaff)
            // ============ STAFF CHARTS ============
            
            // Staff Schedule Chart - Bar
            const staffScheduleCtx = document.getElementById('staffScheduleChart');
            if (staffScheduleCtx) {
                new Chart(staffScheduleCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($staffScheduleLabels ?? ['Week 1', 'Week 2', 'Week 3', 'Week 4']) !!},
                        datasets: [{
                            label: 'Events',
                            data: {!! json_encode($staffScheduleData ?? [0, 0, 0, 0]) !!},
                            backgroundColor: 'rgba(14, 165, 233, 0.8)',
                            borderColor: 'rgb(14, 165, 233)',
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
                                        return context.parsed.y + ' events';
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
                                    stepSize: 1
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

            // Staff Earnings Chart - Line
            const staffEarningsCtx = document.getElementById('staffEarningsChart');
            if (staffEarningsCtx) {
                new Chart(staffEarningsCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($staffEarningsLabels ?? []) !!},
                        datasets: [{
                            label: 'Earnings (₱)',
                            data: {!! json_encode($staffEarningsData ?? []) !!},
                            borderColor: 'rgb(245, 158, 11)',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgb(245, 158, 11)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
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
            @endif
            // Note: Customer charts are now on the My Bookings page
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>