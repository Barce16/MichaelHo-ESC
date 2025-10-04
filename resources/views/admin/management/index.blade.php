<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Management</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- Vertical Nav --}}
                <aside class="md:col-span-1 bg-white shadow-sm rounded-lg p-4">
                    <nav class="space-y-1">
                        <a href="{{ route('admin.management.index') }}"
                            class="{{ request()->routeIs('admin.management.index') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }} block px-3 py-2 rounded">
                            Overview
                        </a>
                        <a href="{{ route('admin.management.packages.index') }}"
                            class="block px-3 py-2 rounded {{ request()->routeIs('admin.management.packages.*') ? 'bg-gray-100 font-medium' : '' }}">
                            Packages
                        </a>
                        <a href="{{ route('admin.management.inclusions.index') }}"
                            class="block px-3 py-2 rounded {{ request()->routeIs('admin.management.inclusions.*') ? 'bg-gray-100 font-medium' : '' }}">
                            Inclusions
                        </a>
                    </nav>
                </aside>


            </div>
        </div>
    </div>
</x-app-layout>