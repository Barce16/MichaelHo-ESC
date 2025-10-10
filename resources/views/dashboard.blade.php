<x-app-layout>
    @php
    $isCustomer = auth()->user()->user_type === 'customer';
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $isCustomer ? __('My Dashboard') : __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(!$isCustomer)
            {{-- ================= ADMIN / STAFF VIEW ================= --}}

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white border-2 border-gray-900 shadow-sm rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Events</div>
                            <div class="text-3xl font-bold text-gray-900 mt-2">{{ $totalEvents ?? '—' }}</div>
                        </div>
                        <div class="w-12 h-12 bg-gray-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Customers</div>
                            <div class="text-3xl font-bold text-gray-900 mt-2">{{ $totalCustomers ?? '—' }}</div>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">This Month</div>
                            <div class="text-2xl font-bold text-gray-900 mt-2">
                                @if(isset($paymentsThisMonth))
                                ₱{{ number_format($paymentsThisMonth, 0) }}
                                @else
                                —
                                @endif
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64" fill="none"
                                    class="w-6 h-6 text-gray-600">
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill="none" stroke="currentColor" stroke-width="2.88"
                                            stroke-miterlimit="10" d="M53.92,10.081c12.107,12.105,12.107,31.732,0,43.838 
                c-12.106,12.108-31.734,12.108-43.839,0c-12.107-12.105-12.107-31.732,0-43.838
                C22.186-2.027,41.813-2.027,53.92,10.081z">
                                        </path>
                                        <line fill="none" stroke="currentColor" stroke-width="2.88"
                                            stroke-miterlimit="10" x1="24" y1="48" x2="24" y2="16"></line>
                                        <path fill="none" stroke="currentColor" stroke-width="2.88"
                                            stroke-miterlimit="10" d="M24,17h7c0,0,11-1,11,9s-11,9-11,9h-7"></path>
                                        <line fill="none" stroke="currentColor" stroke-width="2.88"
                                            stroke-miterlimit="10" x1="19" y1="24" x2="47" y2="24"></line>
                                        <line fill="none" stroke="currentColor" stroke-width="2.88"
                                            stroke-miterlimit="10" x1="19" y1="28" x2="47" y2="28"></line>
                                    </g>
                                </svg>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pending Tasks</div>
                            <div class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingTasks ?? '—' }}</div>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Events --}}
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="font-semibold text-gray-900">Recent Events</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
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
                            $custName = $cust?->user?->name ?? $cust?->name ?? $cust?->customer_name ?? 'Unknown';
                            $avatarUrl = $cust?->user?->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' .
                            urlencode($custName) . '&background=1F2937&color=FFFFFF&size=64';
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $avatarUrl }}" alt="Avatar"
                                            class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-200">
                                        <span class="font-medium text-gray-900">{{ $custName }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.events.show', $e) }}"
                                        class="font-medium text-gray-900 hover:text-black">
                                        {{ $e->name }}
                                    </a>
                                    <div class="text-xs text-gray-500 mt-1">{{ $e->venue ?: '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Illuminate\Support\Carbon::parse($e->event_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $statusClasses = match(strtolower($e->status)) {
                                    'requested' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                    'approved' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                    'meeting' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                    'scheduled' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                                    'completed' => 'bg-green-100 text-green-800 border border-green-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border border-red-200',
                                    'rejected' => 'bg-red-100 text-red-800 border border-red-200',
                                    default => 'bg-gray-100 text-gray-800 border border-gray-200',
                                    };
                                    @endphp
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
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

            @else
            {{-- ================= CUSTOMER VIEW ================= --}}

            {{-- Quick Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-900 hover:bg-black transition text-white shadow-sm rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-gray-300 uppercase tracking-wide">Upcoming Events
                            </div>
                            <div class="text-4xl font-bold mt-2">{{ $upcoming ?? 0 }}</div>
                        </div>
                        <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Quick Actions</div>
                    <a href="{{ route('customer.events.create') }}"
                        class="flex items-center gap-2 text-gray-900 hover:text-black font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Book New Event
                    </a>
                </div>

                <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">My Account</div>
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-2 text-gray-900 hover:text-black font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Navigation</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('customer.events.create') }}"
                        class="flex flex-col items-center justify-center gap-2 p-4 bg-gray-900 text-white rounded-lg hover:bg-black transition group">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-sm font-semibold">Book Event</span>
                    </a>

                    <a href="{{ route('customer.events.index') }}"
                        class="flex flex-col items-center justify-center gap-2 p-4 bg-white border-2 border-gray-900 text-gray-900 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-semibold">My Events</span>
                    </a>

                    <a href="{{ route('customer.billings') }}"
                        class="flex flex-col items-center justify-center gap-2 p-4 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-sm font-semibold">My Billings</span>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="flex flex-col items-center justify-center gap-2 p-4 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-sm font-semibold">Settings</span>
                    </a>
                </div>
            </div>

            {{-- My Recent Events --}}
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="font-semibold text-gray-900">My Recent Events</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
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
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $e->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $e->venue ?: '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Illuminate\Support\Carbon::parse($e->event_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $statusClasses = match(strtolower($e->status)) {
                                    'requested' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                    'approved' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                    'meeting' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                    'scheduled' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                                    'completed' => 'bg-green-100 text-green-800 border border-green-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border border-red-200',
                                    'rejected' => 'bg-red-100 text-red-800 border border-red-200',
                                    default => 'bg-gray-100 text-gray-800 border border-gray-200',
                                    };
                                    @endphp
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                        {{ ucwords(str_replace('_', ' ', strtolower($e->status))) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('customer.events.show', $e) }}"
                                        class="text-gray-900 hover:text-black font-medium">
                                        View Details →
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
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Available Packages</h3>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($packages as $package)
                    @php
                    $inclusions = $package->inclusions ?? collect();
                    $styling = is_array($package->event_styling ?? null) ? $package->event_styling : [];
                    $images = $package->images ?? collect();
                    $mainImage = $images->first();
                    @endphp

                    <div
                        class="group bg-white border-2 border-gray-200 hover:border-gray-900 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg">
                        {{-- Featured Image --}}
                        <div class="relative h-48 overflow-hidden bg-gray-100">
                            @if($mainImage)
                            <img src="{{ asset('storage/' . $mainImage->path) }}"
                                alt="{{ $mainImage->alt ?? $package->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-gray-400 text-sm">No image</span>
                            </div>
                            @endif

                            @if($package->type)
                            <div
                                class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded text-xs font-semibold text-gray-900">
                                {{ $package->type }}
                            </div>
                            @endif
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $package->name }}</h4>
                                <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($package->price, 2)
                                    }}</p>
                            </div>

                            <div class="flex items-center gap-4 text-sm text-gray-600 pt-3 border-t border-gray-200">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    {{ $inclusions->count() }} Items
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                    {{ count($styling) }} Styling
                                </div>
                            </div>

                            <a href="{{ route('customer.events.create', ['package_id' => $package->id]) }}"
                                class="block w-full text-center px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-black transition">
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
</x-app-layout>