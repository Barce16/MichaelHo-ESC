<x-admin.layouts.management>
    @php
    // Event styling: accept array or newline string
    $styRaw = $package->event_styling;
    $sty = is_array($styRaw)
    ? array_values(array_filter($styRaw, fn($s) => trim((string)$s) !== ''))
    : collect(preg_split('/\r\n|\r|\n/', (string) $styRaw, -1, PREG_SPLIT_NO_EMPTY))
    ->map(fn($s) => trim($s))->filter()->values()->all();

    // Package images (ordered by sort if relation is defined that way)
    $imgs = $package->images ?? collect(); // expects hasMany->orderBy('sort') in model
    // 4 fallbacks if fewer than 4 images exist
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

    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">{{ $package->name }}</h3>
                <div class="text-gray-500">₱{{ number_format($package->price, 2) }}</div>
            </div>
            <a href="{{ route('admin.management.packages.edit', $package) }}"
                class="px-3 py-2 bg-gray-800 text-white rounded">
                Edit
            </a>
        </div>

        {{-- Gallery (hero + 3 tiles) --}}
        <div class="grid grid-cols-2 gap-3">
            <figure class="col-span-2 rounded-xl overflow-hidden shadow-sm relative">
                <div class="relative w-full aspect-[16/9]">
                    <img src="{{ $img1 }}" alt="{{ $alt1 }}" class="absolute inset-0 w-full h-full object-cover"
                        loading="lazy" decoding="async">
                </div>
                <figcaption class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent">
                </figcaption>
            </figure>

            <figure class="rounded-xl overflow-hidden shadow-sm relative">
                <div class="relative w-full aspect-[4/3]">
                    <img src="{{ $img2 }}" alt="{{ $alt2 }}" class="absolute inset-0 w-full h-full object-cover"
                        loading="lazy" decoding="async">
                </div>
            </figure>
            <figure class="rounded-xl overflow-hidden shadow-sm relative">
                <div class="relative w-full aspect-[4/3]">
                    <img src="{{ $img3 }}" alt="{{ $alt3 }}" class="absolute inset-0 w-full h-full object-cover"
                        loading="lazy" decoding="async">
                </div>
            </figure>

            <figure class="col-span-2 rounded-xl overflow-hidden shadow-sm relative">
                <div class="relative w-full aspect-[16/7]">
                    <img src="{{ $img4 }}" alt="{{ $alt4 }}" class="absolute inset-0 w-full h-full object-cover"
                        loading="lazy" decoding="async">
                </div>
            </figure>
        </div>

        {{-- Type --}}
        <div>
            <span class="text-gray-600">Type:</span>
            @if($package->type instanceof \App\Enums\PackageType)
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $package->type->color() }}">
                {{ $package->type->label() }}
            </span>
            @else
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 capitalize">
                {{ $package->type ?? 'N/A' }}
            </span>
            @endif
        </div>

        {{-- Inclusions (notes now come from Inclusion model, not pivot) --}}
        <div>
            <div class="text-gray-600 text-sm mb-1">Inclusions</div>

            @if($package->inclusions->isEmpty())
            <div class="text-gray-500">No inclusions added.</div>
            @else
            <ul class="space-y-2">
                @foreach($package->inclusions as $inc)
                @php
                $incNotes = trim((string)($inc->notes ?? ''));
                $noteLines = $incNotes !== '' ? preg_split('/\r\n|\r|\n/', $incNotes) : [];
                $noteLines = array_values(array_filter($noteLines, fn($l) => trim($l) !== ''));
                @endphp
                <li class="border rounded p-3">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-semibold">
                            {{ $inc->name }}
                            @if($inc->category)
                            <span class="text-xs text-gray-500">• {{ $inc->category }}</span>
                            @endif
                        </div>

                        @if(!is_null($inc->price))
                        <div class="text-base font-medium text-gray-800">
                            ₱{{ number_format($inc->price, 2) }}
                        </div>
                        @endif
                    </div>

                    @if(!empty($noteLines))
                    <ul class="text-sm text-gray-700 leading-tight list-disc pl-5 space-y-0.5">
                        @foreach($noteLines as $line)
                        <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-sm text-gray-500">No notes.</div>
                    @endif
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        {{-- Coordination --}}
        <div>
            <div class="text-gray-600 text-sm mb-1">Coordination</div>
            <div class="whitespace-pre-line">{{ $package->coordination ?: '—' }}</div>
        </div>

        {{-- Event Styling (bullet list) --}}
        <div>
            <div class="text-gray-600 text-sm mb-1">Event Styling</div>
            @if(empty($sty))
            <div class="text-gray-500">No styling details.</div>
            @else
            <ul class="space-y-1">
                @foreach($sty as $item)
                <li>{{ $item }}</li>
                @endforeach
            </ul>
            @endif
        </div>

        {{-- Prices --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-gray-600 text-sm">Coordination Price</div>
                <div class="font-medium">₱{{ number_format($package->coordination_price ?? 25000, 2) }}</div>
            </div>
            <div>
                <div class="text-gray-600 text-sm">Event Styling Price</div>
                <div class="font-medium">₱{{ number_format($package->event_styling_price ?? 55000, 2) }}</div>
            </div>
        </div>

        {{-- Status --}}
        <div>
            <div class="text-gray-600 text-sm mb-1">Status</div>
            @php $badge = $package->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; @endphp
            <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                {{ $package->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        {{-- Events using this package --}}
        <div class="pt-6 border-t">
            <h4 class="text-md font-semibold mb-3">Events</h4>

            @if($eventsUsingPackage->count() === 0)
            <div class="text-sm text-gray-600">No Events</div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 border-b">
                            <th class="py-2 pr-4">Date</th>
                            <th class="py-2 pr-4">Event</th>
                            <th class="py-2 pr-4">Customer</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($eventsUsingPackage as $e)
                        <tr class="border-b">
                            <td class="py-2 pr-4">
                                {{ \Illuminate\Support\Carbon::parse($e->event_date)->format('Y-m-d') }}
                            </td>
                            <td class="py-2 pr-4">
                                <div class="font-medium">{{ $e->name }}</div>
                                <div class="text-gray-500">{{ $e->venue ?: '—' }}</div>
                            </td>
                            <td class="py-2 pr-4">
                                {{ $e->customer?->customer_name ?? '—' }}
                                <div class="text-gray-500 text-xs">{{ $e->customer?->email ?? '' }}</div>
                            </td>
                            <td class="py-2 pr-4">
                                @php
                                $color = match($e->status){
                                'requested' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-blue-100 text-blue-800',
                                'scheduled' => 'bg-indigo-100 text-indigo-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                                };
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $color }}">
                                    {{ ucfirst($e->status) }}
                                </span>
                            </td>
                            <td class="py-2 pr-4">
                                <a href="{{ route('admin.events.show', $e) }}"
                                    class="text-indigo-600 hover:underline">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $eventsUsingPackage->withQueryString()->links() }}
            </div>
            @endif
        </div>

        <div class="pt-4 border-t">
            <a href="{{ route('admin.management.packages.index') }}" class="underline">Back to packages</a>
        </div>
    </div>
</x-admin.layouts.management>