@extends('layouts.app')

@section('title', $property->title.' — İlkerMax')
@section('meta_description', Str::limit(strip_tags($property->description), 155))

@push('meta')
    <meta property="og:title" content="{{ $property->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($property->description), 155) }}">
    <meta property="og:type" content="website">
    @if ($property->coverImage)
        <meta property="og:image" content="{{ $property->coverImage->url }}">
    @endif
@endpush

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6">
        <nav class="flex items-center gap-1 text-xs text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-brand-navy">Ana Sayfa</a>
            <span>/</span>
            <a href="{{ route('properties.index') }}" class="hover:text-brand-navy">İlanlar</a>
            <span>/</span>
            <span class="truncate text-gray-700">{{ $property->title }}</span>
        </nav>

        @if ($property->images->isNotEmpty())
            <div x-data="{ active: 0, images: {{ Illuminate\Support\Js::from($property->images->pluck('url')) }} }" class="mt-4">
                <div class="aspect-video w-full overflow-hidden rounded-lg bg-gray-100">
                    <img :src="images[active]" alt="{{ $property->title }}" class="h-full w-full object-cover">
                </div>

                @if ($property->images->count() > 1)
                    <div class="mt-2 flex gap-2 overflow-x-auto pb-1">
                        <template x-for="(image, index) in images" :key="index">
                            <button
                                type="button"
                                @click="active = index"
                                class="h-16 w-20 shrink-0 overflow-hidden rounded-md border-2"
                                :class="active === index ? 'border-brand-red' : 'border-transparent'"
                            >
                                <img :src="image" class="h-full w-full object-cover">
                            </button>
                        </template>
                    </div>
                @endif
            </div>
        @endif

        <div class="mt-6 grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <span class="inline-block rounded-full bg-brand-navy px-2.5 py-1 text-xs font-semibold text-white">
                    {{ $property->listing_type === 'sale' ? 'Satılık' : 'Kiralık' }}
                </span>

                <h1 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">{{ $property->title }}</h1>

                @if ($property->location)
                    <p class="mt-1 flex items-center gap-1 text-sm text-gray-500">
                        <x-heroicon-o-map-pin class="h-4 w-4 shrink-0" />
                        {{ $property->location->province }} / {{ $property->location->district }}@if ($property->location->neighborhood) / {{ $property->location->neighborhood }}@endif
                    </p>
                @endif

                <p class="mt-4 text-2xl font-bold text-brand-red sm:text-3xl">
                    {{ number_format((float) $property->price, 0, ',', '.') }} {{ $property->currency }}
                </p>

                <div class="mt-6 grid grid-cols-2 gap-4 rounded-lg border border-gray-200 bg-white p-4 sm:grid-cols-4">
                    @if ($property->rooms)
                        <div>
                            <p class="text-xs text-gray-500">Oda Sayısı</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $property->rooms }}</p>
                        </div>
                    @endif
                    @if ($property->area_net)
                        <div>
                            <p class="text-xs text-gray-500">Net m²</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $property->area_net }} m²</p>
                        </div>
                    @endif
                    @if ($property->area_gross)
                        <div>
                            <p class="text-xs text-gray-500">Brüt m²</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $property->area_gross }} m²</p>
                        </div>
                    @endif
                    @if ($property->floor !== null)
                        <div>
                            <p class="text-xs text-gray-500">Bulunduğu Kat</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $property->floor }} / {{ $property->total_floors }}</p>
                        </div>
                    @endif
                    @if ($property->building_age !== null)
                        <div>
                            <p class="text-xs text-gray-500">Bina Yaşı</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $property->building_age }}</p>
                        </div>
                    @endif
                    @if ($property->heating_type)
                        <div>
                            <p class="text-xs text-gray-500">Isıtma</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $property->heating_type }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500">Eşyalı</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $property->furnished ? 'Evet' : 'Hayır' }}</p>
                    </div>
                    @if ($property->bathrooms)
                        <div>
                            <p class="text-xs text-gray-500">Banyo Sayısı</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $property->bathrooms }}</p>
                        </div>
                    @endif
                </div>

                @if ($property->description)
                    <div class="prose prose-sm mt-6 max-w-none text-gray-700">
                        {!! $property->description !!}
                    </div>
                @endif

                @if ($property->features->isNotEmpty())
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-gray-900">Özellikler</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ($property->features as $feature)
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">{{ $feature->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($property->latitude && $property->longitude)
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-gray-900">Konum</p>
                        <div
                            id="property-map"
                            data-lat="{{ $property->latitude }}"
                            data-lng="{{ $property->longitude }}"
                            class="mt-2 h-72 w-full rounded-lg border border-gray-200"
                        ></div>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-20 rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm font-semibold text-gray-900">Danışman</p>

                    @if ($property->agent)
                        <p class="mt-2 text-base font-medium text-gray-900">{{ $property->agent->name }}</p>

                        <div class="mt-4 flex flex-col gap-2">
                            @if ($property->agent->whatsapp)
                                @php
                                    $whatsappNumber = preg_replace('/\D/', '', $property->agent->whatsapp);
                                    $whatsappMessage = rawurlencode("Merhaba, \"{$property->title}\" ilanı hakkında bilgi almak istiyorum.");
                                @endphp
                                <a
                                    href="https://wa.me/90{{ ltrim($whatsappNumber, '0') }}?text={{ $whatsappMessage }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="flex items-center justify-center gap-2 rounded-md bg-green-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700"
                                >
                                    <x-heroicon-o-chat-bubble-left-right class="h-4 w-4" />
                                    WhatsApp ile Yaz
                                </a>
                            @endif

                            <a
                                href="tel:{{ $property->agent->phone }}"
                                class="flex items-center justify-center gap-2 rounded-md border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-brand-navy hover:text-brand-navy"
                            >
                                <x-heroicon-o-phone class="h-4 w-4" />
                                {{ $property->agent->phone }}
                            </a>

                            @if ($property->agent->email)
                                <a
                                    href="mailto:{{ $property->agent->email }}"
                                    class="flex items-center justify-center gap-2 rounded-md border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-brand-navy hover:text-brand-navy"
                                >
                                    <x-heroicon-o-envelope class="h-4 w-4" />
                                    E-posta Gönder
                                </a>
                            @endif
                        </div>
                    @else
                        <p class="mt-2 text-sm text-gray-500">Bu ilan için henüz bir danışman atanmadı.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
