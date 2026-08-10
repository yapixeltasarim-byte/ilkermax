@props(['property'])

<a href="{{ route('properties.show', $property) }}" class="group block overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
    <div class="relative aspect-[4/3] w-full overflow-hidden bg-gray-100">
        @if ($property->coverImage)
            <img
                src="{{ $property->coverImage->url }}"
                alt="{{ $property->title }}"
                loading="lazy"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
            >
        @else
            <div class="flex h-full w-full items-center justify-center text-gray-400">
                <x-heroicon-o-photo class="h-10 w-10" />
            </div>
        @endif

        <span class="absolute left-2 top-2 rounded-full bg-brand-navy px-2.5 py-1 text-xs font-semibold text-white">
            {{ $property->listing_type === 'sale' ? 'Satılık' : 'Kiralık' }}
        </span>

        @if ($property->is_featured)
            <span class="absolute right-2 top-2 rounded-full bg-brand-red px-2.5 py-1 text-xs font-semibold text-white">
                Öne Çıkan
            </span>
        @endif
    </div>

    <div class="p-4">
        <p class="line-clamp-2 text-sm font-medium text-gray-900">{{ $property->title }}</p>

        <p class="mt-2 text-lg font-bold text-brand-navy">
            {{ number_format((float) $property->price, 0, ',', '.') }} {{ $property->currency }}
        </p>

        @if ($property->location)
            <p class="mt-1 flex items-center gap-1 text-sm text-gray-500">
                <x-heroicon-o-map-pin class="h-4 w-4 shrink-0" />
                {{ $property->location->district }}@if ($property->location->neighborhood) / {{ $property->location->neighborhood }}@endif
            </p>
        @endif

        <div class="mt-3 flex items-center gap-4 border-t border-gray-100 pt-3 text-xs text-gray-600">
            @if ($property->rooms)
                <span class="flex items-center gap-1">
                    <x-heroicon-o-home-modern class="h-4 w-4" />
                    {{ $property->rooms }}
                </span>
            @endif

            @if ($property->area_net)
                <span class="flex items-center gap-1">
                    <x-heroicon-o-arrows-pointing-out class="h-4 w-4" />
                    {{ $property->area_net }} m²
                </span>
            @endif
        </div>
    </div>
</a>
