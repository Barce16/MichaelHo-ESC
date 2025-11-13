<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">All Notifications</h2>

            @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Mark All as Read
                </button>
            </form>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($notifications->isEmpty())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No Notifications</h3>
                <p class="mt-1 text-sm text-gray-500">You don't have any notifications yet.</p>
            </div>
            @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y divide-gray-200">
                @foreach($notifications as $notification)
                @php
                $data = $notification->data;
                $isUnread = is_null($notification->read_at);
                @endphp

                <div class="p-6 hover:bg-gray-50 transition {{ $isUnread ? 'bg-violet-50' : '' }}">
                    <div class="flex items-start gap-4">
                        {{-- Icon --}}
                        <div
                            class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center {{ $isUnread ? 'bg-violet-100' : 'bg-gray-100' }}">
                            @if($data['type'] === 'inclusion_change_request')
                            <svg class="w-6 h-6 {{ $isUnread ? 'text-violet-600' : 'text-gray-600' }}" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            @else
                            <svg class="w-6 h-6 {{ $isUnread ? 'text-violet-600' : 'text-gray-600' }}" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-base font-semibold text-gray-900">
                                        {{ $data['message'] ?? 'New notification' }}
                                    </p>

                                    @if(isset($data['event_name']))
                                    <p class="text-sm text-gray-600 mt-1">
                                        Event: <span class="font-medium">{{ $data['event_name'] }}</span>
                                    </p>
                                    @endif

                                    @if(isset($data['customer_name']))
                                    <p class="text-sm text-gray-600">
                                        Customer: <span class="font-medium">{{ $data['customer_name'] }}</span>
                                    </p>
                                    @endif

                                    @if(isset($data['added_count']) || isset($data['removed_count']))
                                    <div class="flex items-center gap-3 mt-2">
                                        @if(isset($data['added_count']) && $data['added_count'] > 0)
                                        <span class="text-sm text-green-600 font-medium">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            {{ $data['added_count'] }} added
                                        </span>
                                        @endif
                                        @if(isset($data['removed_count']) && $data['removed_count'] > 0)
                                        <span class="text-sm text-red-600 font-medium">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                            {{ $data['removed_count'] }} removed
                                        </span>
                                        @endif
                                        @if(isset($data['difference']))
                                        <span
                                            class="text-sm font-semibold {{ $data['difference'] > 0 ? 'text-red-600' : ($data['difference'] < 0 ? 'text-green-600' : 'text-gray-600') }}">
                                            {{ $data['difference'] > 0 ? '+' : '' }}₱{{
                                            number_format(abs($data['difference']), 2) }}
                                        </span>
                                        @endif
                                    </div>
                                    @endif

                                    <p class="text-xs text-gray-500 mt-2">
                                        {{ $notification->created_at->format('M d, Y \a\t g:i A') }}
                                        ({{ $notification->created_at->diffForHumans() }})
                                    </p>
                                </div>

                                {{-- Unread Badge --}}
                                @if($isUnread)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800">
                                    New
                                </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-3 mt-4">
                                @if(isset($data['url']))
                                <a href="{{ $data['url'] }}"
                                    onclick="event.preventDefault(); markAsReadAndRedirect('{{ $notification->id }}', '{{ $data['url'] }}');"
                                    class="inline-flex items-center gap-1 text-sm text-violet-600 hover:text-violet-700 font-medium">
                                    View Details
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                                @endif

                                @if($isUnread)
                                <form method="POST"
                                    action="{{ route('admin.notifications.mark-read', $notification->id) }}"
                                    class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                        Mark as read
                                    </button>
                                </form>
                                @endif

                                <form method="POST"
                                    action="{{ route('admin.notifications.destroy', $notification->id) }}"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700"
                                        onclick="return confirm('Delete this notification?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
            @endif
        </div>
    </div>

    <script>
        function markAsReadAndRedirect(notificationId, url) {
        fetch(`/admin/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(() => {
            window.location.href = url;
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.href = url;
        });
    }
    </script>
</x-app-layout>