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
                    <p class="text-sm text-gray-500 mt-1">Monthly event bookings for the past 12 months</p>
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
                                    Customer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Event</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentEvents ?? [] as $e)
                            @php
                            $cust = $e->customer;
                            $custName = $cust?->user?->name ?? $cust?->customer_name ?? 'Unknown';
                            $avatarUrl = $cust?->user?->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' .
                            urlencode($custName) . '&background=8B5CF6&color=FFFFFF&size=64';
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $avatarUrl }}" alt="Avatar"
                                            class="h-10 w-10 rounded-full object-cover ring-2 ring-violet-200">
                                        <span class="font-medium text-gray-900">{{ $custName }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.events.show', $e) }}"
                                        class="font-medium text-gray-900 hover:text-violet-600 transition">
                                        {{ $e->name }}
                                    </a>
                                    <div class="text-xs text-gray-500 mt-1">{{ $e->venue ?: '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($e->event_date)->format('M d, Y') }}
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
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700 border border-indigo-200">
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
            {{-- ================= CUSTOMER VIEW ================= --}}

            {{-- Quick Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div
                    class="bg-gradient-to-br from-violet-500 to-purple-600 shadow-lg rounded-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-violet-100 uppercase tracking-wide">Upcoming Events
                            </div>
                            <div class="text-5xl font-bold mt-2">{{ $upcoming ?? 0 }}</div>
                            <div class="text-xs text-violet-100 mt-1">Scheduled bookings</div>
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
                    class="bg-white border-2 border-gray-200 hover:border-violet-300 shadow-sm hover:shadow-md rounded-xl p-6 transition-all duration-300">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Quick Actions</div>
                    <a href="{{ route('customer.events.create') }}"
                        class="flex items-center gap-2 text-gray-900 hover:text-violet-600 font-bold text-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Book New Event
                    </a>
                </div>

                <div
                    class="bg-white border-2 border-gray-200 hover:border-sky-300 shadow-sm hover:shadow-md rounded-xl p-6 transition-all duration-300">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">My Account</div>
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-2 text-gray-900 hover:text-sky-600 font-bold text-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            {{-- Customer Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- My Events Status --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            My Events Status
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Overview of your event bookings</p>
                    </div>
                    <div class="relative h-64">
                        <canvas id="customerStatusChart"></canvas>
                    </div>
                </div>

                {{-- Payment History --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">

                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 36 36"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z">
                                </path>
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

            {{-- Action Buttons --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Quick Navigation</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('customer.events.create') }}"
                        class="flex flex-col items-center justify-center gap-3 p-6 bg-gradient-to-br from-violet-500 to-purple-600 text-white rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-300 group">
                        <div
                            class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold">Book Event</span>
                    </a>

                    <a href="{{ route('customer.events.index') }}"
                        class="flex flex-col items-center justify-center gap-3 p-6 bg-white border-2 border-violet-200 text-gray-900 rounded-xl hover:border-violet-400 hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        <div class="w-12 h-12 bg-violet-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold">My Events</span>
                    </a>

                    <a href="{{ route('customer.billings') }}"
                        class="flex flex-col items-center justify-center gap-3 p-6 bg-white border-2 border-emerald-200 text-gray-900 rounded-xl hover:border-emerald-400 hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold">My Billings</span>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="flex flex-col items-center justify-center gap-3 p-6 bg-white border-2 border-sky-200 text-gray-900 rounded-xl hover:border-sky-400 hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        <div class="w-12 h-12 bg-sky-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold">Settings</span>
                    </a>
                </div>
            </div>

            {{-- My Recent Events Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        My Recent Events
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
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentEvents ?? [] as $e)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $e->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $e->venue ?: '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($e->event_date)->format('M d, Y') }}
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
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">No recent events.</td>
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
                    <p class="text-gray-500 mt-1">Choose the perfect package for your event</p>
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

                            @if($package->type)
                            <div
                                class="absolute top-3 right-3 px-3 py-1 bg-white/95 backdrop-blur-sm rounded-lg text-xs font-bold text-gray-900 shadow-lg">
                                {{ ucwords($package->type) }}
                            </div>
                            @endif
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $package->name }}</h4>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold text-violet-600">₱{{ number_format($package->price,
                                        0) }}</span>
                                    <span class="text-sm text-gray-500">per event</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 text-sm text-gray-600 pt-3 border-t border-gray-200">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span class="font-medium">{{ $inclusions->count() }} Items</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                    <span class="font-medium">{{ count($styling) }} Styling</span>
                                </div>
                            </div>

                            <a href="{{ route('customer.events.create', ['package_id' => $package->id]) }}"
                                class="block w-full text-center px-6 py-3 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-bold rounded-lg hover:from-violet-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-md hover:shadow-xl">
                                Select Package
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

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
                                        // If value is 1000 or more, show in k format
                                        if (value >= 1000) {
                                            return '₱' + (value / 1000) + 'k';
                                        }
                                        // Otherwise show the actual value
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

            // Events Timeline - Bar Chart
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
                                    stepSize: 5
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
            
            // Staff Schedule Chart - Horizontal Bar
            const staffScheduleCtx = document.getElementById('staffScheduleChart');
            if (staffScheduleCtx) {
                new Chart(staffScheduleCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($staffScheduleLabels ?? []) !!},
                        datasets: [{
                            label: 'Events',
                            data: {!! json_encode($staffScheduleData ?? []) !!},
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 2,
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
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
                                        return context.parsed.x + ' events';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            y: {
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
                            borderColor: 'rgb(251, 191, 36)',
                            backgroundColor: 'rgba(251, 191, 36, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgb(251, 191, 36)',
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
            
            @else
            // ============ CUSTOMER CHARTS ============
            
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
                                        // If value is 1000 or more, show in k format
                                        if (value >= 1000) {
                                            return '₱' + (value / 1000) + 'k';
                                        }
                                        // Otherwise show the actual value
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
        });
    </script>
</x-app-layout>