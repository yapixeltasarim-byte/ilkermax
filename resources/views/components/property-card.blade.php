@props(['property'])

<a href="{{ route('properties.show', $property) }}" class="group block">
    <div class="relative aspect-[4/3] w-full overflow-hidden rounded-lg bg-gray-100">
        @if ($property->coverImage)
            <img
                src="{{ $property->coverImage->url }}"
                alt="{{ $property->title }}"
                loading="lazy"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
            >
        @else
            <div class="flex h-full w-full items-center justify-center text-gray-400">
                <x-heroicon-o-photo class="h-8 w-8" />
            </div>
        @endif

        @if ($property->is_featured)
            <span class="absolute left-2 top-2 rounded-full bg-brand-red px-2 py-0.5 text-[11px] font-semibold text-white">
                Öne Çıkan
            </span>
        @endif
    </div>

    <div class="mt-2">
        <p class="text-base font-bold text-gray-900">
            {{ number_format((float) $property->price, 0, ',', '.') }} {{ $property->currency }}
        </p>

        <p class="mt-0.5 truncate text-xs text-gray-600">
            {{ $property->listing_type === 'sale' ? 'Satılık' : 'Kiralık' }}@if ($property->category) {{ $property->category->name }}@endif
        </p>

        @if ($property->location)
            <p class="truncate text-xs text-gray-500">
                {{ $property->location->province }} / {{ $property->location->district }}
            </p>
        @endif
    </div>
</a>
