<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Users</h2>
            <a href="{{ route('admin.create-user') }}" class="bg-gray-800 text-white px-4 py-2 rounded">New User</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-gray-600">
                        <tr>
                            <th class="text-left py-2">Name</th>
                            <th class="text-left py-2">Email</th>
                            <th class="text-left py-2">Type</th>
                            <th class="text-left py-2">Status</th>
                            <th class="text-left py-2">Joined</th>
                            <th class="text-left py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $u)
                        <tr class="border-t">
                            <td class="py-2">{{ $u->name }}</td>
                            <td class="py-2">{{ $u->email }}</td>
                            <td class="py-2">
                                <span class="px-2 py-1 rounded bg-gray-100">
                                    {{ ucfirst($u->user_type) }}
                                </span>
                            </td>
                            <td class="py-2">
                                <span
                                    class="px-2 py-1 rounded text-xs
                                        {{ $u->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($u->status) }}
                                </span>
                            </td>
                            <td class="py-2">{{ $u->created_at->format('M d, Y') }}</td>
                            <td class="py-2">
                                @if($u->user_type !== 'admin')
                                @if($u->status === 'active')
                                <form action="{{ route('admin.users.block', $u) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-xs">
                                        Block
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.users.unblock', $u) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-xs">
                                        Unblock
                                    </button>
                                </form>
                                @endif
                                @else
                                <span class="text-gray-400 text-xs">Admin</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="py-4" colspan="6">No users yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>