<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">{{ $title }}</h2>
            <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @isset($filters)
            <form method="GET" class="bg-white p-4 rounded-lg shadow-sm flex items-center gap-2">
                @if(isset($filters['days']))
                <label class="text-sm text-gray-600">Days</label>
                <input type="number" name="days" value="{{ request('days', $filters['days']) }}"
                    class="border rounded px-3 py-2 w-28" min="1">
                @endif
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Apply</button>

                {{-- Export CSV --}}
                <a class="px-4 py-2 border rounded ml-auto" href="{{ route('admin.reports.export', [
                            'title'   => $title,
                            'headers' => $headers,
                            'rows'    => json_encode($rows),
                       ]) }}">
                    Export CSV
                </a>
            </form>
            @else
            {{-- Export button even when no filters --}}
            <div class="flex justify-end">
                <a class="px-4 py-2 border rounded" href="{{ route('admin.reports.export', [
                            'title'   => $title,
                            'headers' => $headers,
                            'rows'    => json_encode($rows),
                       ]) }}">
                    Export CSV
                </a>
            </div>
            @endisset

            <div class="bg-white p-6 rounded-lg shadow-sm overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-gray-600">
                        <tr>
                            @foreach($headers as $h)
                            <th class="text-left py-2 pr-4">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                        <tr class="border-t">
                            @foreach($r as $cell)
                            <td class="py-2 pr-4">{{ $cell }}</td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td class="py-4 text-center text-gray-500" colspan="{{ count($headers) }}">No data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>