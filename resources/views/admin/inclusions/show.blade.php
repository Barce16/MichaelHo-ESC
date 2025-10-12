<x-admin.layouts.management>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $inclusion->name }}</h3>
                        @if($inclusion->is_active)
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

                    @if($inclusion->category)
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                        {{ $inclusion->category }}
                    </span>
                    @endif
                </div>

                <div class="text-right">
                    <div class="text-3xl font-bold text-gray-900 mb-1">₱{{ number_format($inclusion->price, 2) }}</div>
                    <div class="text-sm text-gray-500">Service Price</div>
                    <a href="{{ route('admin.management.inclusions.edit', $inclusion) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 mt-3 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Inclusion
                    </a>
                </div>
            </div>
        </div>

        {{-- Image Display --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Image
            </h4>

            @if($inclusion->image)
            <figure class="rounded-xl overflow-hidden shadow-sm relative group">
                <div class="relative w-full aspect-[16/9]">
                    <img src="{{ $inclusion->image_url }}" alt="{{ $inclusion->name }}"
                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        loading="lazy">
                </div>
                <figcaption class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent">
                </figcaption>
            </figure>
            @else
            <div class="text-center py-16 bg-slate-50 rounded-xl border-2 border-dashed border-gray-300">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 font-medium">No image uploaded</p>
                <p class="text-gray-400 text-sm mt-1">Add an image to showcase this service</p>
            </div>
            @endif
        </div>

        {{-- Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Inclusion Information
                </h4>
            </div>

            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-slate-50 rounded-lg p-4">
                        <dt class="flex items-center gap-2 text-sm font-medium text-gray-600 mb-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Service Name
                        </dt>
                        <dd class="text-base font-semibold text-gray-900">{{ $inclusion->name }}</dd>
                    </div>

                    <div class="bg-sky-50 rounded-lg p-4">
                        <dt class="flex items-center gap-2 text-sm font-medium text-sky-700 mb-2">
                            <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Category
                        </dt>
                        <dd class="text-base font-semibold text-sky-900">
                            {{ $inclusion->category ?? 'No category assigned' }}
                        </dd>
                    </div>

                    <div class="bg-violet-50 rounded-lg p-4">
                        <dt class="flex items-center gap-2 text-sm font-medium text-violet-700 mb-2">
                            <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Price
                        </dt>
                        <dd class="text-base font-semibold text-violet-900">₱{{ number_format($inclusion->price, 2) }}
                        </dd>
                    </div>

                    <div class="bg-slate-50 rounded-lg p-4">
                        <dt class="flex items-center gap-2 text-sm font-medium text-gray-600 mb-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status
                        </dt>
                        <dd>
                            @if($inclusion->is_active)
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-sm font-semibold bg-emerald-100 text-emerald-800">
                                Active & Available
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-sm font-semibold bg-slate-200 text-slate-800">
                                Inactive
                            </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    Contact Information
                </h4>
            </div>

            <div class="p-6">
                @if($inclusion->contact_person || $inclusion->contact_email || $inclusion->contact_phone)
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-slate-50 rounded-lg p-4">
                        <dt class="flex items-center gap-2 text-sm font-medium text-gray-600 mb-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Contact Person
                        </dt>
                        <dd class="text-base font-semibold text-gray-900">
                            {{ $inclusion->contact_person ?? '—' }}
                        </dd>
                    </div>

                    <div class="bg-slate-50 rounded-lg p-4">
                        <dt class="flex items-center gap-2 text-sm font-medium text-gray-600 mb-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Email Address
                        </dt>
                        <dd class="text-base font-semibold text-gray-900 truncate">
                            @if($inclusion->contact_email)
                            <a href="mailto:{{ $inclusion->contact_email }}" class="text-sky-600 hover:text-sky-700">
                                {{ $inclusion->contact_email }}
                            </a>
                            @else
                            —
                            @endif
                        </dd>
                    </div>

                    <div class="bg-slate-50 rounded-lg p-4">
                        <dt class="flex items-center gap-2 text-sm font-medium text-gray-600 mb-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Phone Number
                        </dt>
                        <dd class="text-base font-semibold text-gray-900">
                            @if($inclusion->contact_phone)
                            <a href="tel:{{ $inclusion->contact_phone }}" class="text-sky-600 hover:text-sky-700">
                                {{ $inclusion->contact_phone }}
                            </a>
                            @else
                            —
                            @endif
                        </dd>
                    </div>
                </dl>
                @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    <p class="font-medium">No contact information provided</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Notes & Description
                </h4>
            </div>

            <div class="p-6">
                @if($inclusion->notes)
                <div class="prose prose-sm max-w-none text-gray-700 bg-slate-50 rounded-lg p-4 whitespace-pre-line">{{
                    $inclusion->notes }}</div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="font-medium">No notes or description provided</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Back Button --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.management.inclusions.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Inclusions
            </a>
        </div>
    </div>
</x-admin.layouts.management>