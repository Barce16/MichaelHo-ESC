<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Customers</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <form method="GET" class="flex gap-2">
                <input name="q" value="{{ request('q') }}" placeholder="Search name, email, phone"
                    class="border rounded px-3 py-2 w-full">
                <button class="border px-3 py-2 rounded">Search</button>
            </form>

            <div class="bg-white shadow-sm rounded-lg p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-gray-600">
                        <tr>
                            <th class="text-left py-2">Name</th>
                            <th class="text-left py-2">Email</th>
                            <th class="text-left py-2">Phone</th>
                            <th class="text-left py-2">Address</th>
                            <th class="text-left py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $c)
                        <tr class="border-t">
                            <td class="py-2">{{ $c->customer_name }}</td>
                            <td class="py-2">{{ $c->email }}</td>
                            <td class="py-2">{{ $c->phone ?? '—' }}</td>
                            <td class="py-2">{{ Str::limit($c->address, 40) }}</td>
                            <td class="py-2">
                                <a href="{{ route('customers.show', $c) }}">
                                    <x-primary-button>
                                        View
                                    </x-primary-button>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="py-4" colspan="5">No customers yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $customers->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>