<x-admin.layouts.management>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Packages</h3>
                <p class="text-gray-500 mt-1">Manage event packages and pricing</p>
            </div>
            <a href="{{ route('admin.management.packages.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Package
            </a>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
            $totalPackages = $packages->total();
            $activePackages = \App\Models\Package::where('is_active', true)->count();
            $inactivePackages = \App\Models\Package::where('is_active', false)->count();
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-violet-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Total</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalPackages }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-emerald-50 rounded-xl shadow-sm border border-emerald-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-emerald-700 uppercase tracking-wide">Active</div>
                        <div class="text-2xl font-bold text-emerald-800">{{ $activePackages }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-700 uppercase tracking-wide">Inactive</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $inactivePackages }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" class="flex gap-3">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Search packages by name or description..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                    Search
                </button>
                @if(request('q'))
                <a href="{{ route('admin.management.packages.index') }}"
                    class="px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Clear
                </a>
                @endif
            </form>
        </div>

        {{-- Packages Grid --}}
        @if($packages->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <p class="text-gray-500 font-medium mb-1">
                @if(request('q'))
                No packages found matching "{{ request('q') }}"
                @else
                No packages yet
                @endif
            </p>
            <p class="text-gray-400 text-sm">
                @if(request('q'))
                Try a different search term
                @else
                Create your first package to get started
                @endif
            </p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($packages as $p)
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                {{-- Package Header --}}
                <div class="p-6">
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $p->name }}</h4>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $p->description ?? 'No description' }}</p>
                        </div>
                        @if($p->is_active)
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Active
                        </span>
                        @else
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            Inactive
                        </span>
                        @endif
                    </div>

                    {{-- Pricing --}}
                    <div class="bg-slate-50 rounded-lg p-4 mb-4">
                        <div class="flex items-baseline gap-2 mb-2">
                            <span class="text-2xl font-bold text-gray-900">₱{{ number_format($p->price, 0) }}</span>
                            <span class="text-sm text-gray-500">per event</span>
                        </div>
                        <div class="flex gap-4 text-xs text-gray-600">
                            <div>
                                <span class="text-gray-500">Coordination:</span>
                                <span class="font-medium">₱{{ number_format($p->coordination_price ?? 0, 0) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Styling:</span>
                                <span class="font-medium">₱{{ number_format($p->event_styling_price ?? 0, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Inclusions Count --}}
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        {{ $p->inclusions->count() }} {{ Str::plural('inclusion', $p->inclusions->count()) }}
                    </div>
                </div>

                {{-- Actions --}}
                <div class="border-t border-gray-200 bg-slate-50 px-6 py-4">
                    <div class="flex items-center justify-between gap-2">
                        <a href="{{ route('admin.management.packages.show', $p) }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-slate-700 bg-white border border-gray-200 rounded-lg hover:bg-slate-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View
                        </a>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.management.packages.edit', $p) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-violet-700 bg-violet-100 rounded-lg hover:bg-violet-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>

                            <form action="{{ route('admin.management.packages.toggle', $p) }}" method="POST"
                                class="inline">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium {{ $p->is_active ? 'text-amber-700 bg-amber-100 hover:bg-amber-200' : 'text-emerald-700 bg-emerald-100 hover:bg-emerald-200' }} rounded-lg transition">
                                    @if($p->is_active)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @endif
                                    {{ $p->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.management.packages.destroy', $p) }}" method="POST"
                                onsubmit="return confirm('Delete this package? This action cannot be undone.')"
                                class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-rose-700 bg-rose-100 rounded-lg hover:bg-rose-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($packages->hasPages())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-4">
            {{ $packages->links() }}
        </div>
        @endif
        @endif
    </div>
</x-admin.layouts.management>