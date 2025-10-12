<x-admin.layouts.management>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div>
            <h3 class="text-2xl font-bold text-gray-900">System Overview</h3>
            <p class="text-gray-500 mt-1">Quick insights into your event management system</p>
        </div>

        {{-- Main Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Total Events --}}
            <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-violet-100 text-sm font-medium uppercase tracking-wide">Total Events</p>
                        <p class="text-4xl font-bold mt-2">{{ number_format($totalEvents) }}</p>
                        <p class="text-violet-100 text-xs mt-1">All time bookings</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Customers --}}
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 text-sm font-medium uppercase tracking-wide">Total Customers</p>
                        <p class="text-4xl font-bold mt-2">{{ number_format($totalCustomers) }}</p>
                        <p class="text-emerald-100 text-xs mt-1">Registered clients</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Packages & Inclusions Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Total Packages --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-violet-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Packages</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalPackages }}</div>
                    </div>
                </div>
            </div>

            {{-- Active Packages --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Active</div>
                        <div class="text-2xl font-bold text-emerald-800">{{ $activePackages }}</div>
                    </div>
                </div>
            </div>

            {{-- Total Inclusions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Inclusions</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalInclusions }}</div>
                    </div>
                </div>
            </div>

            {{-- Available Inclusions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Available</div>
                        <div class="text-2xl font-bold text-amber-800">{{ $availableInclusions }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Packages --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Available Packages
                </h4>
                <a href="{{ route('admin.management.packages.index') }}"
                    class="text-sm text-slate-600 hover:text-slate-900 font-medium flex items-center gap-1">
                    View All
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="p-6">
                @if($recentPackages->isEmpty())
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <p class="text-gray-500 font-medium">No packages available</p>
                    <a href="{{ route('admin.management.packages.create') }}"
                        class="text-sm text-slate-600 hover:text-slate-900 mt-2 inline-block">
                        Create your first package
                    </a>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($recentPackages as $package)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-slate-50 transition">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex-1">
                                <h5 class="font-semibold text-gray-900 mb-1">{{ $package->name }}</h5>
                                <p class="text-xs text-gray-500 line-clamp-2">{{ $package->description ?? 'No
                                    description' }}</p>
                            </div>
                            @if($package->is_active)
                            <span
                                class="px-2 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full">Active</span>
                            @else
                            <span
                                class="px-2 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-full">Inactive</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <div class="text-sm">
                                <span class="text-gray-500">Base Price:</span>
                                <span class="font-bold text-gray-900 ml-1">₱{{
                                    number_format($package->coordination_price + $package->event_styling_price, 2)
                                    }}</span>
                            </div>
                            <a href="{{ route('admin.management.packages.edit', $package) }}"
                                class="text-xs text-slate-600 hover:text-slate-900 font-medium">
                                Edit →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Popular Inclusions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Most Used Inclusions
                </h4>
                <a href="{{ route('admin.management.inclusions.index') }}"
                    class="text-sm text-slate-600 hover:text-slate-900 font-medium flex items-center gap-1">
                    View All
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="p-6">
                @if($popularInclusions->isEmpty())
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <p class="text-gray-500 font-medium">No inclusions available</p>
                    <a href="{{ route('admin.management.inclusions.create') }}"
                        class="text-sm text-slate-600 hover:text-slate-900 mt-2 inline-block">
                        Create your first inclusion
                    </a>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($popularInclusions as $inclusion)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-slate-50 transition">
                        <div class="flex items-start gap-3 mb-2">
                            <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h5 class="font-semibold text-gray-900 text-sm mb-1">{{ $inclusion->name }}</h5>
                                @if($inclusion->category)
                                <span
                                    class="inline-block px-2 py-0.5 bg-sky-50 text-sky-700 text-xs font-medium rounded">
                                    {{ $inclusion->category }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <div class="text-sm">
                                <span class="font-bold text-gray-900">₱{{ number_format($inclusion->price, 2) }}</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                Used {{ $inclusion->events_count ?? 0 }}x
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-gradient-to-br from-slate-50 to-gray-100 rounded-xl border border-gray-200 p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <a href="{{ route('admin.management.packages.create') }}"
                    class="flex items-center gap-3 px-4 py-3 bg-white border border-gray-200 rounded-lg hover:shadow-md transition">
                    <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">Create Package</div>
                        <div class="text-xs text-gray-500">Add a new event package</div>
                    </div>
                </a>

                <a href="{{ route('admin.management.inclusions.create') }}"
                    class="flex items-center gap-3 px-4 py-3 bg-white border border-gray-200 rounded-lg hover:shadow-md transition">
                    <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">Add Inclusion</div>
                        <div class="text-xs text-gray-500">Create new service inclusion</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-admin.layouts.management>