<x-admin.layouts.management>
    <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">Packages</h3>
            <a href="{{ route('admin.management.packages.create') }}" class="px-3 py-2 bg-gray-800 text-white rounded">
                New Package
            </a>
        </div>

        <form method="GET" class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search packages"
                class="border rounded px-3 py-2 w-full">
            <button class="border px-3 py-2 rounded">Search</button>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-gray-600">
                    <tr>
                        <th class="text-left py-2">Name</th>
                        <th class="text-left py-2">Price</th>
                        <th class="text-left py-2">Status</th>
                        <th class="text-center py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $p)
                    <tr class="border-t">
                        <td class="py-2">
                            <div class="font-medium">{{ $p->name }}</div>
                            <div class="text-gray-500">{{ \Illuminate\Support\Str::limit($p->description, 60) }}</div>
                        </td>
                        <td class="py-2">₱{{ number_format($p->price, 2) }}</td>
                        <td class="py-2">
                            @php $badge = $p->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                                {{ $p->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-2 space-x-2 flex justify-center items-center">
                            <a href="{{ route('admin.management.packages.show', $p) }}" class="underline">View</a>
                            <a href="{{ route('admin.management.packages.edit', $p) }}" class="underline">Edit</a>
                            <form action="{{ route('admin.management.packages.toggle', $p) }}" method="POST"
                                class="inline">
                                @csrf @method('PATCH')
                                <button class="underline">{{ $p->is_active ? 'Deactivate' : 'Activate' }}</button>
                            </form>
                            <form action="{{ route('admin.management.packages.destroy', $p) }}" method="POST"
                                class="inline" onsubmit="return confirm('Delete this package?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-500">No packages yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $packages->links() }}</div>
    </div>
</x-admin.layouts.management>