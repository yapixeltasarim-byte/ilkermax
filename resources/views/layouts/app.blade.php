<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name'))</title>
        <meta name="description" content="@yield('meta_description', 'Kocaeli\'de satılık ve kiralık daire, villa, arsa ve işyeri ilanları.')">

        @stack('meta')

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-gray-50 text-gray-900 antialiased">
        <header class="sticky top-0 z-40 border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 sm:px-6">
                <a href="{{ route('home') }}" class="text-xl font-bold text-brand-navy">
                    İlker<span class="text-brand-red">Max</span>
                </a>

                <nav class="hidden items-center gap-6 text-sm font-medium text-gray-700 sm:flex">
                    <a href="{{ route('home') }}" class="hover:text-brand-navy">Ana Sayfa</a>
                    <a href="{{ route('properties.index', ['listing_type' => 'sale']) }}" class="hover:text-brand-navy">Satılık</a>
                    <a href="{{ route('properties.index', ['listing_type' => 'rent']) }}" class="hover:text-brand-navy">Kiralık</a>
                    <a href="{{ route('properties.index') }}" class="hover:text-brand-navy">Tüm İlanlar</a>
                </nav>

                <button
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-md text-gray-700 sm:hidden"
                    x-data
                    @click="$dispatch('toggle-mobile-nav')"
                    aria-label="Menüyü aç"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <nav
                x-data="{ open: false }"
                @toggle-mobile-nav.window="open = !open"
                x-show="open"
                x-cloak
                class="border-t border-gray-200 bg-white px-4 py-3 sm:hidden"
            >
                <ul class="flex flex-col gap-3 text-sm font-medium text-gray-700">
                    <li><a href="{{ route('home') }}" class="block py-1">Ana Sayfa</a></li>
                    <li><a href="{{ route('properties.index', ['listing_type' => 'sale']) }}" class="block py-1">Satılık</a></li>
                    <li><a href="{{ route('properties.index', ['listing_type' => 'rent']) }}" class="block py-1">Kiralık</a></li>
                    <li><a href="{{ route('properties.index') }}" class="block py-1">Tüm İlanlar</a></li>
                </ul>
            </nav>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="border-t border-gray-200 bg-brand-navy text-gray-200">
            <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
                <div class="grid gap-8 sm:grid-cols-3">
                    <div>
                        <p class="text-lg font-bold text-white">
                            İlker<span class="text-brand-red">Max</span>
                        </p>
                        <p class="mt-2 text-sm text-gray-300">Kocaeli'de satılık ve kiralık emlak ilanları.</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-gray-400">İlanlar</p>
                        <ul class="mt-3 space-y-2 text-sm">
                            <li><a href="{{ route('properties.index', ['listing_type' => 'sale']) }}" class="hover:text-white">Satılık İlanlar</a></li>
                            <li><a href="{{ route('properties.index', ['listing_type' => 'rent']) }}" class="hover:text-white">Kiralık İlanlar</a></li>
                            <li><a href="{{ route('properties.index') }}" class="hover:text-white">Tüm İlanlar</a></li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-gray-400">İletişim</p>
                        <p class="mt-3 text-sm text-gray-300">Bir ilanın danışmanıyla doğrudan iletişime geçmek için ilan detay sayfasındaki WhatsApp/telefon butonlarını kullanabilirsiniz.</p>
                    </div>
                </div>

                <p class="mt-8 border-t border-white/10 pt-6 text-xs text-gray-400">
                    &copy; {{ now()->year }} İlkerMax. Tüm hakları saklıdır.
                </p>
            </div>
        </footer>
    </body>
</html>
