@extends('layouts.app')

@section('title', 'Tüm İlanlar — İlkerMax')
@section('meta_description', 'Satılık ve kiralık daire, villa, arsa ve işyeri ilanlarını filtreleyerek arayın.')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6">
        <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">İlanlar</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $properties->total() }} ilan bulundu</p>

        <div x-data="{ open: false }" class="mt-4">
            <button
                type="button"
                @click="open = !open"
                class="flex w-full items-center justify-between rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 sm:hidden"
            >
                <span class="flex items-center gap-2">
                    <x-heroicon-o-adjustments-horizontal class="h-4 w-4" />
                    Filtrele ve Sırala
                </span>
                <x-heroicon-o-chevron-down class="h-4 w-4" x-bind:class="open && 'rotate-180'" />
            </button>

            <form
                action="{{ route('properties.index') }}"
                method="GET"
                :class="open ? 'grid' : 'hidden'"
                class="mt-3 grid-cols-2 gap-3 rounded-lg border border-gray-200 bg-white p-4 sm:mt-0 sm:!grid sm:grid-cols-3 sm:gap-3 lg:grid-cols-6"
            >
                <select name="listing_type" class="col-span-2 w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy sm:col-span-1">
                    <option value="">Satılık / Kiralık</option>
                    <option value="sale" @selected(request('listing_type') === 'sale')>Satılık</option>
                    <option value="rent" @selected(request('listing_type') === 'rent')>Kiralık</option>
                </select>

                <select name="category_id" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy">
                    <option value="">Tüm Kategoriler</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>

                <x-location-cascade :locations="$locations" />

                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min. Fiyat" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy">
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max. Fiyat" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy">

                <select name="sort" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy">
                    <option value="">En Yeni</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Fiyat: Düşükten Yükseğe</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Fiyat: Yüksekten Düşüğe</option>
                </select>

                <button type="submit" class="col-span-2 rounded-md bg-brand-navy px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90 sm:col-span-3 lg:col-span-1">
                    Filtrele
                </button>
            </form>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($properties as $property)
                <x-property-card :property="$property" />
            @empty
                <p class="col-span-full py-12 text-center text-gray-500">Seçtiğiniz kriterlere uygun ilan bulunamadı.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $properties->links() }}
        </div>
    </div>
@endsection
