<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Notifications</h2>

            @if($unreadCount > 0)
            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Mark all as read
                </button>
            </form>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($unreadCount > 0)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-blue-800 font-medium">
                            You have {{ $unreadCount }} unread {{ $unreadCount === 1 ? 'notification' : 'notifications'
                            }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">

                @if($notifications->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($notifications as $notification)
                    <div class="p-4 hover:bg-gray-50 transition {{ !$notification->is_read ? 'bg-blue-50/30' : '' }}">
                        <a href="{{ route('notifications.read', $notification) }}" class="block group">
                            <div class="flex items-start gap-4">

                                {{-- Notification Icon --}}
                                <div class="flex-shrink-0">
                                    @php
                                    $iconClass = match($notification->type) {
                                    'event_approved', 'event_scheduled' => 'bg-green-100 text-green-600',
                                    'event_rejected' => 'bg-red-100 text-red-600',
                                    'payment_approved' => 'bg-green-100 text-green-600',
                                    'payment_pending' => 'bg-yellow-100 text-yellow-600',
                                    'payment_rejected' => 'bg-red-100 text-red-600',
                                    'staff_work_finished' => 'bg-blue-100 text-blue-600',
                                    'customer_inclusions_updated' => 'bg-purple-100 text-purple-600',
                                    'customer_phone_updated' => 'bg-indigo-100 text-indigo-600',
                                    default => 'bg-gray-100 text-gray-600'
                                    };
                                    @endphp

                                    <div
                                        class="w-10 h-10 rounded-full {{ $iconClass }} flex items-center justify-center">
                                        @if(str_contains($notification->type, 'payment'))
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        @elseif(str_contains($notification->type, 'event'))
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        @elseif(str_contains($notification->type, 'staff'))
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        @elseif($notification->type === 'customer_phone_updated')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        @elseif(str_contains($notification->type, 'inclusions'))
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        @endif
                                    </div>
                                </div>

                                {{-- Notification Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1">
                                            <p
                                                class="text-sm font-semibold text-gray-900 group-hover:text-violet-600 transition">
                                                {{ $notification->title }}
                                                @if(!$notification->is_read)
                                                <span
                                                    class="inline-flex items-center ml-2 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    New
                                                </span>
                                                @endif
                                            </p>
                                            <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                                                {{ $notification->message }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-2 flex items-center gap-4 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>

                                        @if($notification->is_read)
                                        <span class="flex items-center gap-1 text-green-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Read
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Arrow Icon --}}
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-violet-600 transition"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($notifications->hasPages())
                <div class="px-4 py-4 border-t border-gray-100">
                    {{ $notifications->links() }}
                </div>
                @endif

                @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No notifications yet</h3>
                    <p class="text-sm text-gray-500">
                        You'll see notifications here when you receive updates about your events, payments, and more.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>