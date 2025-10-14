<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">System Management</h2>
                <p class="text-sm text-gray-500 mt-1">Manage packages, inclusions, and system settings</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Sidebar --}}
            <aside class="lg:col-span-1">
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4">
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Navigation</h3>
                    </div>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.management.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.management.index') ? 'bg-slate-700 text-white shadow-sm' : 'text-gray-700 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="font-medium text-sm">Overview</span>
                        </a>

                        <a href="{{ route('admin.management.packages.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.management.packages.*') ? 'bg-violet-50 text-violet-700 font-medium' : 'text-gray-700 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span class="font-medium text-sm">Packages</span>
                        </a>

                        <a href="{{ route('admin.management.inclusions.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.management.inclusions.*') ? 'bg-sky-50 text-sky-700 font-medium' : 'text-gray-700 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span class="font-medium text-sm">Inclusions</span>
                        </a>

                        <a href="{{ route('admin.management.feedback.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.feedback.*') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-gray-700 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <span class="font-medium text-sm">Feedback</span>
                        </a>

                        <a href="{{ route('admin.management.showcases.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.management.showcases.*') ? 'bg-rose-50 text-rose-700 font-medium' : 'text-gray-700 hover:bg-slate-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium text-sm">Event Showcase</span>
                        </a>
                    </nav>
                </div>
            </aside>

            {{-- Main content --}}
            <div class="lg:col-span-3">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-app-layout>