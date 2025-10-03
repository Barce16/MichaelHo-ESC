<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Reports</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-4 md:grid-cols-3">

            <div>
                <h3 class="font-semibold text-lg">Event Reports</h3>
                <a href="{{ route('admin.reports.event.generate', ['format' => 'csv']) }}"
                    class="px-4 py-2 text-sm bg-gray-700 text-white rounded-md hover:bg-gray-600">
                    Download CSV Report
                </a>
                <a href="{{ route('admin.reports.event.generate', ['format' => 'pdf']) }}"
                    class="px-4 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-500">
                    Download PDF Report
                </a>
            </div>

            <div>
                <h3 class="font-semibold text-lg">Customer Reports</h3>
                <a href="{{ route('admin.reports.customer.generate', ['format' => 'csv']) }}"
                    class="px-4 py-2 text-sm bg-gray-700 text-white rounded-md hover:bg-gray-600">
                    Download CSV Report
                </a>
                <a href="{{ route('admin.reports.customer.generate', ['format' => 'pdf']) }}"
                    class="px-4 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-500">
                    Download PDF Report
                </a>
            </div>

            <!-- Staff Report Generation -->
            <div>
                <h3 class="font-semibold text-lg">Staff Reports</h3>
                <a href="{{ route('admin.reports.staff.generate', ['format' => 'csv']) }}"
                    class="px-4 py-2 text-sm bg-gray-700 text-white rounded-md hover:bg-gray-600">
                    Download CSV Report
                </a>
                <a href="{{ route('admin.reports.staff.generate', ['format' => 'pdf']) }}"
                    class="px-4 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-500">
                    Download PDF Report
                </a>
            </div>

        </div>
    </div>
</x-app-layout>