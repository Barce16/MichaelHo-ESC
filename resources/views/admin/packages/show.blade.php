<x-admin.layouts.management>
    @php
    // Event styling: accept array or newline string
    $styRaw = $package->event_styling;
    $sty = is_array($styRaw)
    ? array_values(array_filter($styRaw, fn($s) => trim((string)$s) !== ''))
    : collect(preg_split('/\r\n|\r|\n/', (string) $styRaw, -1, PREG_SPLIT_NO_EMPTY))
    ->map(fn($s) => trim($s))->filter()->values()->all();

    // Package images (ordered by sort if relation is defined that way)
    $imgs = $package->images ?? collect();
    $fallbacks = [
    "https://picsum.photos/seed/pkg-{$package->id}-1/960/540",
    "https://picsum.photos/seed/pkg-{$package->id}-2/480/360",
    "https://picsum.photos/seed/pkg-{$package->id}-3/480/360",
    "https://picsum.photos/seed/pkg-{$package->id}-4/960/360",
    ];

    $img1 = $imgs[0]->url ?? $fallbacks[0];
    $alt1 = $imgs[0]->alt ?? 'Package image';
    $img2 = $imgs[1]->url ?? $fallbacks[1];
    $alt2 = $imgs[1]->alt ?? 'Package image';
    $img3 = $imgs[2]->url ?? $fallbacks[2];
    $alt3 = $imgs[2]->alt ?? 'Package image';
    $img4 = $imgs[3]->url ?? $fallbacks[3];
    $alt4 = $imgs[3]->alt ?? 'Package image';
    @endphp

    <div class="space-y-6">
        {{-- Header --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $package->name }}</h3>
                        @if($package->is_active)
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Active
                        </span>
                        @else
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            Inactive
                        </span>
                        @endif
                    </div>

                    @if($package->description)
                    <p class="text-gray-600">{{ $package->description }}</p>
                    @endif

                    {{-- Type --}}
                    @if($package->type)
                    <div class="mt-3">
                        @if($package->type instanceof \App\Enums\PackageType)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold {{ $package->type->color() }}">
                            {{ $package->type->label() }}
                        </span>
                        @else
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-800 capitalize">
                            {{ $package->type }}
                        </span>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="text-right">
                    <div class="text-3xl font-bold text-gray-900 mb-1">₱{{ number_format($package->price, 2) }}</div>
                    <div class="text-sm text-gray-500">Total Package Price</div>
                    <a href="{{ route('admin.management.packages.edit', $package) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 mt-3 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Package
                    </a>
                </div>
            </div>
        </div>

        {{-- Gallery --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Package Gallery
            </h4>

            <div class="grid grid-cols-2 gap-3">
                <figure class="col-span-2 rounded-xl overflow-hidden shadow-sm relative group">
                    <div class="relative w-full aspect-[16/9]">
                        <img src="{{ $img1 }}" alt="{{ $alt1 }}"
                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy" decoding="async">
                    </div>
                    <figcaption class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent">
                    </figcaption>
                </figure>

                <figure class="rounded-xl overflow-hidden shadow-sm relative group">
                    <div class="relative w-full aspect-[4/3]">
                        <img src="{{ $img2 }}" alt="{{ $alt2 }}"
                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy" decoding="async">
                    </div>
                </figure>

                <figure class="rounded-xl overflow-hidden shadow-sm relative group">
                    <div class="relative w-full aspect-[4/3]">
                        <img src="{{ $img3 }}" alt="{{ $alt3 }}"
                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy" decoding="async">
                    </div>
                </figure>

                <figure class="col-span-2 rounded-xl overflow-hidden shadow-sm relative group">
                    <div class="relative w-full aspect-[16/7]">
                        <img src="{{ $img4 }}" alt="{{ $alt4 }}"
                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy" decoding="async">
                    </div>
                </figure>
            </div>
        </div>

        {{-- Pricing Breakdown --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pricing Breakdown
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-sky-50 border border-sky-200 rounded-lg p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-sky-700 uppercase tracking-wide">Coordination</div>
                            <div class="text-2xl font-bold text-sky-900">₱{{ number_format($package->coordination_price
                                ?? 25000, 2) }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-rose-50 border border-rose-200 rounded-lg p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-rose-700 uppercase tracking-wide">Event Styling</div>
                            <div class="text-2xl font-bold text-rose-900">₱{{
                                number_format($package->event_styling_price ?? 55000, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Coordination Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Coordination Services
            </h4>
            <div class="prose prose-sm max-w-none text-gray-700 bg-slate-50 rounded-lg p-4">
                {{ $package->coordination ?: 'No coordination details provided.' }}
            </div>
        </div>

        {{-- Event Styling --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                </svg>
                Event Styling Details
            </h4>

            @if(empty($sty))
            <div class="text-gray-500 bg-slate-50 rounded-lg p-4">No styling details provided.</div>
            @else
            <ul class="space-y-2">
                @foreach($sty as $item)
                <li class="flex items-start gap-3 bg-slate-50 rounded-lg p-3">
                    <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-gray-700">{{ $item }}</span>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        {{-- Inclusions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Package Inclusions
                </h4>
                <span class="px-3 py-1 bg-violet-100 text-violet-700 text-sm font-semibold rounded-full">
                    {{ $package->inclusions->count() }} {{ Str::plural('item', $package->inclusions->count()) }}
                </span>
            </div>

            @if($package->inclusions->isEmpty())
            <div class="text-center py-8 bg-slate-50 rounded-lg">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <p class="text-gray-500 font-medium">No inclusions added yet</p>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($package->inclusions as $inc)
                @php
                $incNotes = trim((string)($inc->notes ?? ''));
                $noteLines = $incNotes !== '' ? preg_split('/\r\n|\r|\n/', $incNotes) : [];
                $noteLines = array_values(array_filter($noteLines, fn($l) => trim($l) !== ''));
                @endphp
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-slate-50 transition">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-1">{{ $inc->name }}</h5>
                            @if($inc->category)
                            <span
                                class="inline-block px-2 py-0.5 bg-violet-50 text-violet-700 border border-violet-200 rounded text-xs font-medium">
                                {{ $inc->category }}
                            </span>
                            @endif
                        </div>
                        @if(!is_null($inc->price))
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900">₱{{ number_format($inc->price, 2) }}</div>
                        </div>
                        @endif
                    </div>

                    @if(!empty($noteLines))
                    <ul class="text-sm text-gray-600 leading-relaxed space-y-1 mt-3 pt-3 border-t border-gray-200">
                        @foreach($noteLines as $line)
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-violet-400 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>{{ $line }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Events Using This Package --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Events Using This Package
                    </h4>
                    <span class="px-3 py-1 bg-slate-200 text-slate-700 text-xs font-semibold rounded-full">
                        {{ $eventsUsingPackage->total() }} {{ Str::plural('event', $eventsUsingPackage->total()) }}
                    </span>
                </div>
            </div>

            @if($eventsUsingPackage->count() === 0)
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 font-medium">No events using this package yet</p>
            </div>
            @else
            <div class="divide-y divide-gray-200">
                @foreach ($eventsUsingPackage as $e)
                @php
                $eventDate = \Carbon\Carbon::parse($e->event_date);
                $statusConfig = match($e->status) {
                'requested' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'dot'
                => 'bg-amber-500'],
                'approved' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'border' => 'border-sky-200', 'dot' =>
                'bg-sky-500'],
                'scheduled' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' => 'border-violet-200',
                'dot' => 'bg-violet-500'],
                'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200',
                'dot' => 'bg-emerald-500'],
                'cancelled' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'dot' =>
                'bg-rose-500'],
                default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'dot' =>
                'bg-slate-500'],
                };
                @endphp
                <div class="p-6 hover:bg-slate-50 transition">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-2">{{ $e->name }}</h5>
                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-2">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $eventDate->format('M d, Y') }}
                                </div>
                                @if($e->event_location)
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ Str::limit($e->event_location, 30) }}
                                </div>
                                @endif
                            </div>
                            @if($e->customer)
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $e->customer->customer_name }} • {{ $e->customer->email }}
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                {{ ucwords(str_replace('_', ' ', $e->status)) }}
                            </span>

                            <a href="{{ route('admin.events.show', $e) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($eventsUsingPackage->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-slate-50">
                {{ $eventsUsingPackage->withQueryString()->links() }}
            </div>
            @endif
            @endif
        </div>

        {{-- Back Button --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.management.packages.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Packages
            </a>
        </div>
    </div>
</x-admin.layouts.management>