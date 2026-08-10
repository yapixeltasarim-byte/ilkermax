@extends('layouts.app')

@section('title', 'İlkerMax — Kocaeli\'de Satılık ve Kiralık Emlak İlanları')
@section('meta_description', 'Kocaeli\'de satılık ve kiralık daire, villa, arsa ve işyeri ilanlarını İlkerMax\'ta keşfedin.')

@section('content')
    <section class="bg-brand-navy px-4 py-12 text-white sm:px-6 sm:py-16">
        <div class="mx-auto max-w-6xl">
            <h1 class="text-2xl font-bold sm:text-4xl">Hayalinizdeki eve İlkerMax ile ulaşın</h1>
            <p class="mt-2 text-sm text-gray-300 sm:text-base">Kocaeli'de binlerce satılık ve kiralık ilan arasından size uygun olanı bulun.</p>

            <form action="{{ route('properties.index') }}" method="GET" class="mt-6 grid grid-cols-1 gap-3 rounded-xl bg-white p-4 text-gray-900 shadow-lg sm:grid-cols-2 lg:grid-cols-6">
                <select name="listing_type" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy">
                    <option value="">Satılık / Kiralık</option>
                    <option value="sale">Satılık</option>
                    <option value="rent">Kiralık</option>
                </select>

                <select name="category_id" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy">
                    <option value="">Tüm Kategoriler</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <x-location-cascade :locations="$locations" />

                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-md bg-brand-red px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 sm:col-span-2 lg:col-span-1">
                    <x-heroicon-o-magnifying-glass class="h-4 w-4" />
                    İlan Ara
                </button>
            </form>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
        <h2 class="text-lg font-semibold text-gray-900 sm:text-xl">Kategoriye Göre Göz At</h2>
        <div class="mt-4 flex gap-3 overflow-x-auto pb-2 sm:grid sm:grid-cols-4 sm:gap-4 sm:overflow-visible lg:grid-cols-7">
            @foreach ($categories as $category)
                <a
                    href="{{ route('properties.index', ['category_id' => $category->id]) }}"
                    class="flex shrink-0 flex-col items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-3 text-center text-sm font-medium text-gray-700 transition hover:border-brand-navy hover:text-brand-navy sm:shrink"
                >
                    <x-dynamic-component :component="$category->icon" class="h-6 w-6" />
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </section>

    @if ($featured->isNotEmpty())
        <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 sm:text-xl">Öne Çıkan İlanlar</h2>
                <a href="{{ route('properties.index') }}" class="text-sm font-medium text-brand-navy hover:underline">Tümünü Gör</a>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featured as $property)
                    <x-property-card :property="$property" />
                @endforeach
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 sm:text-xl">Yeni Eklenen İlanlar</h2>
            <a href="{{ route('properties.index') }}" class="text-sm font-medium text-brand-navy hover:underline">Tümünü Gör</a>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($latest as $property)
                <x-property-card :property="$property" />
            @endforeach
        </div>
    </section>
@endsection
