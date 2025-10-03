<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                Guests for Event: {{ $event->name }}
            </h2>

            <a href="{{ route('admin.events.show', $event) }}"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-opacity-50 transition duration-200">
                Go Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="font-semibold text-lg mb-6 text-gray-800">Guests List</h3>

                @if($guests->isEmpty())
                <p class="text-gray-500">No guests added yet.</p>
                @else
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Contact Number</th>
                            <th class="px-4 py-2 text-left">Party Size</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($guests as $guest)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $guest->name }}</td>
                            <td class="px-4 py-2">{{ $guest->email }}</td>
                            <td class="px-4 py-2">{{ $guest->contact_number }}</td>
                            <td class="px-4 py-2">{{ $guest->party_size }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>