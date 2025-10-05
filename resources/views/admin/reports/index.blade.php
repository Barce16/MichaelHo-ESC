<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Reports</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid md:grid-cols-2 gap-6">
                {{-- Events Report Card --}}
                <a href="{{ route('admin.reports.events') }}"
                    class="block bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-indigo-400 hover:shadow-lg transition-all p-8">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Events Report</h3>
                            <p class="text-gray-600 text-sm">View events by date range, status, and package type</p>
                        </div>
                    </div>
                </a>

                {{-- Revenue Report Card --}}
                <a href="{{ route(name: 'admin.reports.revenue') }}"
                    class="block bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-green-400 hover:shadow-lg transition-all p-8">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Revenue Report</h3>
                            <p class="text-gray-600 text-sm">Track payments and revenue by date and payment method</p>
                        </div>
                    </div>
                </a>

                {{-- Customers Report Card --}}
                <a href="{{ route('admin.reports.customers') }}"
                    class="block bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-purple-400 hover:shadow-lg transition-all p-8">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Customers Report</h3>
                            <p class="text-gray-600 text-sm">View customer list with booking history and revenue</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>