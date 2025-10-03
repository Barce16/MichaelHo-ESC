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
            {{-- Stat cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Total Events</div>
                    <div class="text-2xl font-bold">{{ $totalEvents ?? '—' }}</div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Customers</div>
                    <div class="text-2xl font-bold">{{ $totalCustomers ?? '—' }}</div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Payments (This Month)</div>
                    <div class="text-2xl font-bold">
                        @if(isset($paymentsThisMonth))
                        ₱{{ number_format($paymentsThisMonth, 0) }}
                        @else
                        —
                        @endif
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Pending Tasks</div>
                    <div class="text-2xl font-bold">{{ $pendingTasks ?? '—' }}</div>
                </div>
            </div>

            {{-- Optional: show customer-specific cards if those vars exist --}}
            @if(isset($upcoming))
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Upcoming My Events</div>
                    <div class="text-2xl font-bold">{{ $upcoming }}</div>
                </div>
            </div>
            @endif


            {{-- Recent Events --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">Recent Events</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-600">
                            <tr>
                                <th class="text-left py-2">Customer</th>
                                <th class="text-left py-2">Event</th>
                                <th class="text-left py-2">Date</th>
                                <th class="text-left py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEvents ?? [] as $e)

                            @php
                            $cust = $e->customer;
                            $custName = $cust?->user?->name
                            ?? $cust?->name
                            ?? $cust?->customer_name
                            ?? 'Unknown';

                            $avatarUrl = $cust?->user?->profile_photo_url
                            ?? 'https://ui-avatars.com/api/?name=' . urlencode($custName) .
                            '&background=E5E7EB&color=374151&size=64';
                            @endphp
                            <tr class="border-t">
                                {{-- Customer --}}
                                <td class="py-2">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $avatarUrl }}" alt="Avatar"
                                            class="h-8 w-8 rounded-full object-cover">
                                        <span class="font-medium text-gray-900">{{ $custName }}</span>
                                    </div>
                                </td>

                                {{-- Event --}}
                                <td class="py-2">
                                    <a href="{{ route('admin.events.show', $e) }}"
                                        class="text-indigo-600 hover:underline">
                                        {{ $e->name }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ $e->venue ?: '—' }}</div>
                                </td>

                                {{-- Date --}}
                                <td>{{ \Illuminate\Support\Carbon::parse($e->event_date)->format('Y-m-d') }}</td>

                                {{-- Status --}}
                                <td>
                                    @php
                                    $color = match(strtolower($e->status)) {
                                    'requested' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-blue-100 text-blue-800',
                                    'scheduled' => 'bg-indigo-100 text-indigo-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                    };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs {{ $color }}">
                                        {{ ucwords(str_replace('_', ' ', strtolower($e->status))) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">No recent events.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            @else
            {{-- ================= CUSTOMER VIEW ================= --}}
            {{-- My Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">My Upcoming Events</div>
                    <div class="text-2xl font-bold">{{ $upcoming ?? 0 }}</div>
                </div>
            </div>

            {{-- Customer Quick Actions --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">Quick Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('customer.events.create') }}" class="bg-sky-900 text-white px-4 py-2 rounded">Book
                        an
                        Event</a>
                    <a href="{{ route('customer.events.index') }}"
                        class="bg-emerald-700 text-white px-4 py-2 rounded">My
                        Events</a>
                    <a href="#" class="bg-violet-700 text-white px-4 py-2 rounded">My
                        Payments</a>
                    <a href="{{ route('profile.edit') }}" class="bg-gray-800 text-white px-4 py-2 rounded">Edit
                        Profile</a>
                </div>
            </div>

            {{-- My Recent Events --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">My Recent Events</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-600">
                            <tr>
                                <th class="text-left py-2">Event</th>
                                <th class="text-left py-2">Date</th>
                                <th class="text-left py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEvents ?? [] as $e)
                            <tr class="border-t">
                                <td class="py-2">
                                    <a href="{{ route('customer.events.show', $e) }}"
                                        class="text-indigo-600 hover:underline">
                                        {{ $e->name }}
                                    </a>
                                </td>
                                <td>{{ \Illuminate\Support\Carbon::parse($e->event_date)->format('Y-m-d') }}</td>
                                <td>
                                    @php
                                    $color = match(strtolower($e->status)) {
                                    'requested' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-blue-100 text-blue-800',
                                    'scheduled' => 'bg-indigo-100 text-indigo-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                    };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs {{ $color }}">
                                        {{ ucwords(str_replace('_', ' ', strtolower($e->status))) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500">No recent events.</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            {{-- Available Packages (Customer) --}}
            @if(!empty($packages) && $packages->count())
            <div class="bg-white shadow-sm rounded-xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Available Packages</h3>

                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($packages as $package)
                    @php
                    $inclusions = $package->inclusions ?? collect();
                    $styling = is_array($package->event_styling ?? null) ? $package->event_styling : [];
                    $images = $package->images ?? collect();
                    $mainImage = $images->first();
                    @endphp

                    <div
                        class="group relative bg-white rounded-xl border-2 border-gray-200 hover:border-slate-400 hover:shadow-xl transition-all duration-300 overflow-hidden">
                        {{-- Featured Image --}}
                        <div class="relative h-48 overflow-hidden">
                            @if($mainImage)
                            <img src="{{ asset('storage/' . $mainImage->path) }}"
                                alt="{{ $mainImage->alt ?? $package->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-sm">No image</span>
                            </div>
                            @endif

                            {{-- Package Type Badge --}}
                            @if($package->type)
                            <div
                                class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-semibold text-gray-700 shadow-sm">
                                {{ $package->type }}
                            </div>
                            @endif
                        </div>

                        <div class="p-6 space-y-4">
                            {{-- Package Name & Price --}}
                            <div>
                                <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $package->name }}</h4>
                                <p class="text-2xl font-bold text-slate-600">₱{{ number_format($package->price, 2) }}
                                </p>
                            </div>

                            {{-- Service Prices --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                    <div class="text-xs text-gray-500 mb-1">Coordination</div>
                                    <div class="font-semibold text-gray-900">₱{{
                                        number_format($package->coordination_price ?? 25000, 2) }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                    <div class="text-xs text-gray-500 mb-1">Styling</div>
                                    <div class="font-semibold text-gray-900">₱{{
                                        number_format($package->event_styling_price ?? 55000, 2) }}</div>
                                </div>
                            </div>

                            {{-- Quick Info --}}
                            <div class="space-y-2 pt-2 border-t border-gray-200">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span>{{ $inclusions->count() }} Inclusions</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                    <span>{{ count($styling) }} Styling Items</span>
                                </div>
                            </div>

                            {{-- Book Button --}}
                            <a href="{{ route('customer.events.create', ['package_id' => $package->id]) }}"
                                class="block w-full mt-4 px-6 py-3 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-center font-semibold rounded-lg hover:from-slate-700 hover:to-gray-700 transition-all duration-300 shadow-md hover:shadow-lg">
                                Book This Package
                            </a>

                            {{-- View Details Link --}}
                            <button type="button" onclick="toggleDetails({{ $package->id }})"
                                class="w-full text-sm text-slate-600 hover:text-slate-800 font-medium flex items-center justify-center gap-1">
                                <span class="toggle-text-{{ $package->id }}">View Full Details</span>
                                <svg class="w-4 h-4 transition-transform duration-300 toggle-icon-{{ $package->id }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>

                        {{-- Expandable Details --}}
                        <div id="details-{{ $package->id }}"
                            class="overflow-hidden transition-all duration-500 ease-in-out max-h-0 opacity-0">
                            <div class="border-t border-gray-200 bg-gray-50 p-6">
                                {{-- Inclusions --}}
                                @if($inclusions->isNotEmpty())
                                <div class="mb-4">
                                    <h5 class="font-semibold text-gray-900 mb-2">Inclusions</h5>
                                    <ul class="space-y-2">
                                        @foreach($inclusions as $inclusion)
                                        <li class="flex items-start gap-2 text-sm">
                                            <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-gray-700">{{ $inclusion->name }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                {{-- Event Styling --}}
                                @if(!empty($styling))
                                <div class="mb-4">
                                    <h5 class="font-semibold text-gray-900 mb-2">Event Styling</h5>
                                    <ul class="space-y-1 text-sm text-gray-700 list-disc list-inside">
                                        @foreach($styling as $item)
                                        @if(trim($item) !== '')
                                        <li>{{ $item }}</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                {{-- Coordination --}}
                                @if($package->coordination)
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-2">Coordination</h5>
                                    <p class="text-sm text-gray-700">{{ $package->coordination }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <script>
                function toggleDetails(packageId) {
    const details = document.getElementById('details-' + packageId);
    const icon = document.querySelector('.toggle-icon-' + packageId);
    const text = document.querySelector('.toggle-text-' + packageId);
    
    if (details.classList.contains('max-h-0')) {
        // Expand
        details.style.maxHeight = details.scrollHeight + 'px';
        details.classList.remove('max-h-0', 'opacity-0');
        details.classList.add('opacity-100');
        icon.style.transform = 'rotate(180deg)';
        text.textContent = 'Hide Details';
    } else {
        // Collapse
        details.style.maxHeight = '0px';
        details.classList.add('max-h-0', 'opacity-0');
        details.classList.remove('opacity-100');
        icon.style.transform = 'rotate(0deg)';
        text.textContent = 'View Full Details';
    }
}
            </script>
            @endif

            @endif
        </div>
    </div>
</x-app-layout>