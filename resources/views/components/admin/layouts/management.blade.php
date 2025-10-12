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