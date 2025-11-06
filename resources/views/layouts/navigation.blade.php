@php($user = Auth::user())
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mh-logo class="block h-10" />
                    </a>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Customer-only --}}
                    @if($user && $user->user_type === 'customer')
                    <x-nav-link :href="route('customer.events.index')"
                        :active="request()->routeIs('customer.events.*')">
                        {{ __('My Events') }}
                    </x-nav-link>
                    <x-nav-link :href="route('customer.payments.index')"
                        :active="request()->routeIs('customer.payments.index')">
                        {{ __('Payment History') }}
                    </x-nav-link>
                    <x-nav-link :href="route('customer.billings')" :active="request()->routeIs('customer.billings')">
                        {{ __('Billings') }}
                    </x-nav-link>
                    @endif

                    @auth
                    {{-- ADMIN --}}
                    @if ($user->user_type === 'admin')
                    <x-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                        {{ __('Events') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('customers.*')">
                        {{ __('Customers') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.staff.index')" :active="request()->routeIs('staff.*')">
                        {{ __('Staff') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.payments.index')" :active="request()->routeIs('admin.payments.*')">
                        {{ __('Payments') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.payroll.index')" :active="request()->routeIs('admin.payroll.*')">
                        {{ __('Payroll') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                        {{ __('Reports') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.users.list')" :active="request()->routeIs('admin.users.list')">
                        {{ __('Users') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.management.index')"
                        :active="request()->routeIs('admin.management.*')">
                        {{ __('Management') }}
                    </x-nav-link>
                    @endif

                    {{-- STAFF (no Staff list, just Events + Schedule) --}}
                    @if(Auth::user()->user_type === 'staff')
                    <x-nav-link :href="route('staff.schedules.index')"
                        :active="request()->routeIs('staff.schedules.*')">
                        {{ __('My Schedule') }}
                    </x-nav-link>
                    <x-nav-link :href="route('staff.earnings')" :active="request()->routeIs('staff.earnings')">
                        {{ __('Earnings') }}
                    </x-nav-link>
                    @endif
                    @endauth
                </div>
            </div>

            <!-- Right side: Notifications + User menu -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <!-- Notification Bell -->
                <div x-data="notificationDropdown()" class="relative">
                    <button @click="toggleDropdown()"
                        class="relative p-2 text-gray-600 hover:text-gray-800 focus:outline-none transition rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span x-show="unreadCount > 0" x-text="unreadCount"
                            class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full"></span>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                        style="display: none;">

                        <!-- Header -->
                        <div
                            class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-blue-50 to-indigo-50">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                Notifications
                                <span x-show="unreadCount > 0" x-text="'(' + unreadCount + ')'"
                                    class="text-blue-600"></span>
                            </h3>
                            <button @click="markAllAsRead()" x-show="unreadCount > 0"
                                class="text-xs text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                Mark all read
                            </button>
                        </div>
                        <div class="flex flex-col max-h-[32rem]">

                            {{-- list (scrolls) --}}
                            <div class="overflow-y-auto flex-1">
                                <template x-if="notifications.length === 0">
                                    <div class="px-4 py-12 text-center">
                                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-gray-500 text-sm">No notifications yet</p>
                                    </div>
                                </template>

                                <template x-for="notification in notifications" :key="notification.id">
                                    <a :href="`/notifications/${notification.id}/read`"
                                        class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100"
                                        :class="{ 'bg-blue-50': !notification.is_read }">
                                        <div class="flex items-start gap-3">
                                            <div :class="{
                        'bg-orange-100 text-orange-600': notification.type === 'event_request',
                        'bg-emerald-100 text-emerald-600': notification.type === 'payment_submitted',
                        'bg-blue-100 text-blue-600': notification.type === 'event_status',
                        'bg-green-100 text-green-600': notification.type === 'payment_approved',
                        'bg-red-100 text-red-600': notification.type === 'payment_rejected',
                        'bg-purple-100 text-purple-600': notification.type === 'schedule_assigned',
                        'bg-gray-100 text-gray-600': notification.type === 'schedule_removed',
                        'bg-teal-100 text-teal-600': notification.type === 'payroll_paid',
                        'bg-yellow-100 text-yellow-600': notification.type === 'customer_feedback',
                    }" class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 mb-1"
                                                    x-text="notification.title"></p>
                                                <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed"
                                                    x-text="notification.message"></p>
                                                <p class="text-xs text-gray-400 mt-2"
                                                    x-text="formatDate(notification.created_at)"></p>
                                            </div>

                                            <template x-if="!notification.is_read">
                                                <span
                                                    class="w-2.5 h-2.5 bg-blue-600 rounded-full flex-shrink-0 mt-1"></span>
                                            </template>
                                        </div>
                                    </a>
                                </template>
                            </div>

                            {{-- sticky footer (always visible at bottom) --}}
                            <div class="px-4 py-3 border-t border-gray-100 bg-white flex items-center justify-center">
                                <a href="{{ route('notifications.index') }}"
                                    class="w-full inline-flex items-center justify-center gap-2 text-sm text-gray-700 hover:text-gray-900 hover:bg-gray-50 px-3 py-2 rounded-md transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    View all notifications
                                </a>
                            </div>
                        </div>
                    </div>


                </div>


                <!-- User Profile -->
                <img src="{{ Auth::user()->profile_photo_url }}" class="h-8 w-8 rounded-full object-cover"
                    alt="{{ Auth::user()->name }}">

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- Customer-only --}}
            @if($user && $user->user_type === 'customer')
            <x-responsive-nav-link :href="route('customer.events.index')"
                :active="request()->routeIs('customer.events.*')">
                {{ __('My Events') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customer.payments.index')"
                :active="request()->routeIs('customer.payments.index')">
                {{ __(key: 'Payment History') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customer.billings')" :active="request()->routeIs('customer.billings')">
                {{ __(key: 'Billings') }}
            </x-responsive-nav-link>
            @endif

            @auth
            {{-- ADMIN --}}
            @if ($user->user_type === 'admin')
            <x-responsive-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                {{ __('Events') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('customers.*')">
                {{ __('Customers') }}
            </x-responsive-nav-link>
            <x-nav-link :href="route('admin.staff.index')" :active="request()->routeIs('staff.*')">
                {{ __('Staff') }}
            </x-nav-link>
            <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('reports.*')">
                {{ __('Reports') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.users.list')" :active="request()->routeIs('admin.users.list')">
                {{ __('Users') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.management.index')"
                :active="request()->routeIs('admin.management.*')">
                {{ __('Management') }}
            </x-responsive-nav-link>
            @endif

            {{-- STAFF --}}
            @if ($user->user_type === 'staff')
            <x-responsive-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                {{ __('Events') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('staff.schedules.index')"
                :active="request()->routeIs('staff.schedule.*')">
                {{ __('Schedule') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('staff.earnings')" :active="request()->routeIs('staff.earnings.*')">
                {{ __('Earnings') }}
            </x-responsive-nav-link>
            @endif
            @endauth
        </div>

        <!-- Responsive Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- Toasts --}}
@if (session('success'))
<x-toast type="success" :message="session('success')" />
@endif
@if (session('info'))
<x-toast type="info" :message="session('info')" />
@endif
@if (session('warning'))
<x-toast type="warning" :message="session('warning')" />
@endif
@if (session('error'))
<x-toast type="error" :message="session('error')" />
@endif

{{-- Alpine.js Notification Component --}}
<script>
    function notificationDropdown() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,

        init() {
            this.fetchNotifications();
            // Poll every 30 seconds for new notifications
            setInterval(() => {
                this.fetchNotifications();
            }, 30000);
        },

        async fetchNotifications() {
            try {
                const response = await fetch('/notifications');
                const data = await response.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (error) {
                console.error('Failed to fetch notifications:', error);
            }
        },

        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                this.fetchNotifications();
            }
        },

        async markAllAsRead() {
            try {
                await fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                this.fetchNotifications();
            } catch (error) {
                console.error('Failed to mark as read:', error);
            }
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000); // seconds

            if (diff < 60) return 'Just now';
            if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
            if (diff < 604800) return Math.floor(diff / 86400) + 'd ago';
            
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }
    }
}
</script>