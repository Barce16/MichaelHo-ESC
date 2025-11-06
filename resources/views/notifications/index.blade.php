<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold">Notifications</h1>

            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">{{ $unreadCount }} unread</span>

                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-md">
                        Mark all read
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($notifications as $note)
            @php
            // unread flag
            $isUnread = (isset($note->is_read) && !$note->is_read) || empty($note->read_at);

            // visual mapping by type
            switch($note->type) {
            case 'payment_submitted':
            $bg = 'bg-emerald-50';
            $border = 'border-emerald-200';
            $accent = 'text-emerald-600';
            $icon = 'receipt';
            break;

            case 'payment_approved':
            $bg = 'bg-green-50';
            $border = 'border-green-200';
            $accent = 'text-green-600';
            $icon = 'check';
            break;

            case 'payment_rejected':
            $bg = 'bg-red-50';
            $border = 'border-red-200';
            $accent = 'text-red-600';
            $icon = 'x';
            break;

            case 'event_request':
            $bg = 'bg-orange-50';
            $border = 'border-orange-200';
            $accent = 'text-orange-600';
            $icon = 'request';
            break;

            case 'event_status':
            $bg = 'bg-sky-50';
            $border = 'border-sky-200';
            $accent = 'text-sky-600';
            $icon = 'calendar';
            break;

            case 'inclusions_updated':
            $bg = 'bg-amber-50';
            $border = 'border-amber-200';
            $accent = 'text-amber-600';
            $icon = 'adjustments';
            break;

            case 'schedule_assigned':
            $bg = 'bg-purple-50';
            $border = 'border-purple-200';
            $accent = 'text-purple-600';
            $icon = 'calendar-check';
            break;

            case 'schedule_removed':
            $bg = 'bg-gray-50';
            $border = 'border-gray-200';
            $accent = 'text-gray-600';
            $icon = 'calendar-x';
            break;

            case 'payroll_paid':
            $bg = 'bg-teal-50';
            $border = 'border-teal-200';
            $accent = 'text-teal-600';
            $icon = 'money';
            break;

            case 'customer_feedback':
            $bg = 'bg-yellow-50';
            $border = 'border-yellow-200';
            $accent = 'text-yellow-600';
            $icon = 'chat';
            break;

            default:
            $bg = 'bg-gray-50';
            $border = 'border-gray-200';
            $accent = 'text-gray-600';
            $icon = 'bell';
            }

            // left border color when unread
            if ($accent === 'text-emerald-600') $borderColor = 'border-emerald-500';
            elseif ($accent === 'text-green-600') $borderColor = 'border-green-500';
            elseif ($accent === 'text-red-600') $borderColor = 'border-red-500';
            elseif ($accent === 'text-orange-600') $borderColor = 'border-orange-500';
            elseif ($accent === 'text-sky-600') $borderColor = 'border-sky-500';
            elseif ($accent === 'text-amber-600') $borderColor = 'border-amber-500';
            elseif ($accent === 'text-purple-600') $borderColor = 'border-purple-500';
            elseif ($accent === 'text-teal-600') $borderColor = 'border-teal-500';
            elseif ($accent === 'text-yellow-600') $borderColor = 'border-yellow-500';
            else $borderColor = 'border-gray-400';

            $leftBorder = $isUnread ? 'border-l-4 ' . $borderColor : '';
            @endphp

            <article
                class="bg-white border rounded-lg shadow-sm p-4 flex items-start gap-4 hover:shadow-md transform hover:-translate-y-0.5 transition"
                role="article">
                {{-- unread dot --}}
                <div class="mt-1">
                    @if($isUnread)
                    <span class="inline-block w-2 h-2 rounded-full {{ $accent }}" title="Unread"></span>
                    @else
                    <span class="inline-block w-2 h-2 rounded-full bg-transparent"></span>
                    @endif
                </div>

                {{-- card --}}
                <div class="flex-1 {{ $leftBorder }} {{ $border }} rounded-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            {{-- icon --}}
                            <div class="flex-shrink-0 p-2 rounded-md {{ $bg }}">
                                @if($icon === 'receipt')
                                <svg class="w-5 h-5 {{ $accent }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6M7 21h10M12 3v6" />
                                </svg>
                                @elseif($icon === 'check')
                                <svg class="w-5 h-5 {{ $accent }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                @elseif($icon === 'x')
                                <svg class="w-5 h-5 {{ $accent }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                @elseif($icon === 'request')
                                <svg class="w-5 h-5 {{ $accent }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 11V7a3 3 0 013-3h0a3 3 0 013 3v4M7 21h10a2 2 0 002-2v-7a2 2 0 00-2-2H7a2 2 0 00-2 2v7a2 2 0 002 2z" />
                                </svg>
                                @elseif($icon === 'calendar')
                                <svg class="w-5 h-5 {{ $accent }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                @elseif($icon === 'adjustments')
                                <svg class="w-5 h-5 {{ $accent }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 6h16M4 12h8M4 18h12" />
                                </svg>
                                @elseif($icon === 'calendar-check')
                                <svg class="w-5 h-5 {{ $accent }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M3 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                @elseif($icon === 'calendar-x')
                                <svg class="w-5 h-5 {{ $accent }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                @elseif($icon === 'money')
                                <svg class="w-5 h-5 {{ $accent }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-3 0-5 1-6 3 1 2 3 3 6 3s5-1 6-3c-1-2-3-3-6-3zM12 8v8" />
                                </svg>
                                @elseif($icon === 'chat')
                                <svg class="w-5 h-5 {{ $accent }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M21 12v4a2 2 0 01-2 2H7l-4 4V6a2 2 0 012-2h14a2 2 0 012 2v6z" />
                                </svg>
                                @else
                                <svg class="w-5 h-5 {{ $accent }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                                </svg>
                                @endif
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                    {{ $note->title }}
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $bg }} {{ $accent }} font-medium">
                                        {{ str_replace('_', ' ', ucfirst($note->type)) }}
                                    </span>
                                </h3>

                                <p class="text-xs text-gray-500 mt-1">{!! ($note->message) !!}</p>
                            </div>
                        </div>

                        <div class="text-right ml-3">
                            <div class="text-xs text-gray-400">{{ $note->created_at ? $note->created_at->diffForHumans()
                                : '' }}</div>

                            <div class="mt-2">
                                {{-- open / mark-read --}}
                                <a href="{{ route('notifications.read', $note->id) }}"
                                    class="text-sm px-3 py-1 rounded-md border border-gray-200 bg-white hover:bg-gray-50 inline-block">
                                    {{ $isUnread ? 'Open' : 'View' }}
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </article>
            @empty
            <div class="bg-white border rounded-lg shadow-sm p-6 text-center text-gray-600">
                No notifications yet.
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>