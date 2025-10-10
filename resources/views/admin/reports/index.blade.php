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
                <a href="{{ route('admin.reports.revenue') }}"
                    class="block bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-green-400 hover:shadow-lg transition-all p-8">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 36 36"
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

                {{-- 🧾 Customer Spending Report --}}
                <a href="{{ route('admin.reports.customer-spending') }}"
                    class="block bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-amber-400 hover:shadow-lg transition-all p-8">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 36 36"
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
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Customer Spending</h3>
                            <p class="text-gray-600 text-sm">Analyze total spending per customer over time</p>
                        </div>
                    </div>
                </a>

                {{-- 📦 Package Usage Report --}}
                <a href="{{ route('admin.reports.package-usage') }}"
                    class="block bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-blue-400 hover:shadow-lg transition-all p-8">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Package Usage</h3>
                            <p class="text-gray-600 text-sm">Check how often and which packages are used</p>
                        </div>
                    </div>
                </a>

                {{-- 💳 Payment Method Report --}}
                <a href="{{ route('admin.reports.payment-method') }}"
                    class="block bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-cyan-400 hover:shadow-lg transition-all p-8">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-cyan-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8h18M3 12h18m-2 4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Payment Method</h3>
                            <p class="text-gray-600 text-sm">Breakdown of revenue by payment channels</p>
                        </div>
                    </div>
                </a>

                {{-- 📊 Event Status Report --}}
                <a href="{{ route('admin.reports.event-status') }}"
                    class="block bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-pink-400 hover:shadow-lg transition-all p-8">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-pink-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m2 6H7a2 2 0 01-2-2V8a2 2 0 012-2h2l2-2h2l2 2h2a2 2 0 012 2v8a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Event Status</h3>
                            <p class="text-gray-600 text-sm">Monitor active, pending, and completed events</p>
                        </div>
                    </div>
                </a>

                {{-- 💰 Remaining Balances Report --}}
                <a href="{{ route('admin.reports.remaining-balances') }}"
                    class="block bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-red-400 hover:shadow-lg transition-all p-8">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Remaining Balances</h3>
                            <p class="text-gray-600 text-sm">Track unpaid amounts and outstanding balances</p>
                        </div>
                    </div>
                </a>

                {{-- 🧮 System Summary Report --}}
                <a href="{{ route('admin.reports.system-summary') }}"
                    class="block bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-gray-400 hover:shadow-lg transition-all p-8">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h4a1 1 0 011 1v16H4a1 1 0 01-1-1V4zm7 0a1 1 0 011-1h4a1 1 0 011 1v16h-6V4zm7 0a1 1 0 011-1h4a1 1 0 011 1v16h-6V4z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">System Summary</h3>
                            <p class="text-gray-600 text-sm">Overview of total events, revenue, and users</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>