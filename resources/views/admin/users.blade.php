<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>
            <button onclick="openUserModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-indigo-700 transition shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Admin
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filters & Search -->
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.users.list') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search by name or email..."
                                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- User Type Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User Type</label>
                            <select name="type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">All Types</option>
                                <option value="admin" {{ request('type')=='admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ request('type')=='staff' ? 'selected' : '' }}>Staff</option>
                                <option value="customer" {{ request('type')=='customer' ? 'selected' : '' }}>Customer
                                </option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <!-- Results Count -->
                        <div class="text-sm text-gray-600">
                            Showing <span class="font-semibold">{{ $users->firstItem() ?? 0 }}</span>
                            to <span class="font-semibold">{{ $users->lastItem() ?? 0 }}</span>
                            of <span class="font-semibold">{{ $users->total() }}</span> results
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.list') }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                Clear Filters
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Username
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User Type
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($user->profile_photo_path)
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                                alt="{{ $user->name }}">
                                            @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-indigo-400 flex items-center justify-center text-white font-semibold">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user->username }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->user_type === 'admin')
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Admin
                                    </span>
                                    @elseif($user->user_type === 'staff')
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Staff
                                    </span>
                                    @else
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Customer
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->status === 'active')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                    @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        @if($user->status === 'active')
                                        <!-- Block Button -->
                                        <form action="{{ route('admin.users.block', $user) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition font-medium">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                                Block
                                            </button>
                                        </form>
                                        @else
                                        <!-- Unblock Button -->
                                        <form action="{{ route('admin.users.unblock', $user) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition font-medium">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Unblock
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">No users found</h3>
                                        <p class="text-gray-500 mb-4">
                                            @if(request()->hasAny(['search', 'type', 'status']))
                                            Try adjusting your filters or search query.
                                            @else
                                            Get started by creating your first admin.
                                            @endif
                                        </p>
                                        @if(request()->hasAny(['search', 'type', 'status']))
                                        <a href="{{ route('admin.users.list') }}"
                                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                            Clear Filters
                                        </a>
                                        @else
                                        <button onclick="openUserModal()"
                                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                            Add Admin
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Include Create User Modal -->
    @include('admin.modals.create')

    <!-- Modal Scripts -->
    <script>
        function openUserModal() {
        document.getElementById('userModal').classList.remove('hidden');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
            document.querySelector('#userModal form').reset();
        }

        // Auto-open modal if there are validation errors
        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->any())
                openUserModal();
            @endif
        });
    </script>

    <style>
        .modal-content {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app-layout>