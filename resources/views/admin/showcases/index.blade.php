<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Event Showcase</h2>
                <p class="text-sm text-gray-500 mt-1">Manage featured events on homepage</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white px-4 py-2 rounded-lg border border-gray-200">
                    <span class="text-sm text-gray-600">Published:</span>
                    <span class="font-bold text-gray-900 ml-2">{{ $publishedCount }}</span>
                </div>
                <a href="{{ route('admin.management.showcases.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-rose-500 to-pink-600 text-white font-semibold rounded-lg hover:from-rose-600 hover:to-pink-700 transition shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Event
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if($showcases->isEmpty())
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 font-medium text-lg">No event showcases yet</p>
                    <a href="{{ route('admin.management.showcases.create') }}"
                        class="text-sm text-rose-600 hover:text-rose-800 mt-2 inline-block font-medium">
                        Add your first showcase
                    </a>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @foreach($showcases as $showcase)
                    <div
                        class="group relative overflow-hidden rounded-xl shadow-md border border-gray-200 hover:shadow-xl transition-all">
                        <div class="aspect-[4/5] overflow-hidden bg-gray-100">
                            <img src="{{ $showcase->image_url }}" alt="{{ $showcase->event_name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                        <div class="absolute top-3 right-3 flex gap-2">
                            @if($showcase->is_published)
                            <span
                                class="px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">Published</span>
                            @else
                            <span
                                class="px-2 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">Draft</span>
                            @endif
                        </div>

                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                            <span
                                class="inline-block px-2 py-1 bg-white/20 backdrop-blur-sm rounded text-xs font-medium uppercase mb-2">
                                {{ $showcase->type }}
                            </span>
                            <h3 class="text-lg font-bold mb-1">{{ $showcase->event_name }}</h3>
                            <p class="text-xs text-gray-200 mb-2 line-clamp-2">{{ $showcase->description }}</p>
                            <p class="text-xs text-gray-300 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $showcase->location }}
                            </p>
                        </div>

                        <div
                            class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <a href="{{ route('admin.management.showcases.edit', $showcase) }}"
                                class="p-2 bg-white text-gray-900 rounded-lg hover:bg-gray-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            @if($showcase->is_published)
                            <form action="{{ route('admin.management.showcases.unpublish', $showcase) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="p-2 bg-white text-gray-900 rounded-lg hover:bg-gray-100 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.management.showcases.publish', $showcase) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="p-2 bg-white text-gray-900 rounded-lg hover:bg-gray-100 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('admin.management.showcases.destroy', $showcase) }}" method="POST"
                                onsubmit="return confirm('Delete this showcase?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-gray-200">
                    {{ $showcases->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>