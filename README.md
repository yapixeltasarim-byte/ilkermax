# İlkerMax

remax.com.tr benzeri, mobile-first çalışan bir emlak sitesi. İlanlar admin panelden ve bir Telegram bot üzerinden yönetilir.

## Stack

- **Laravel 13** (PHP 8.4) + **SQLite** (local development), **MySQL** (production — paylaşımlı hosting)
- **Filament** — admin panel
- **Leaflet + OpenStreetMap** — ilan detayında harita
- Ayrı bir **Telegram bot** (Node.js + grammY, ayrı repo) ilan girişini otomatikleştirir ve Laravel API'ye yazar

## Marka

- Navy `#1C3144`, kırmızı `#D60033` (varsayılan — ileride değişebilir)
- Dil: şimdilik sadece Türkçe

## Yol haritası

Kapsamlı plan için bkz. `emlak-sitesi-plan.pdf` (proje kökünde referans olarak tutulur). Geliştirme fazlar halinde ilerler: veri modeli → admin panel → public site (mobile-first) → bot API → Telegram bot → teslim.

## Yerel geliştirme

Bu makinede [Laravel Herd](https://herd.laravel.com) kullanılıyor. Herd'in `php.ini` dosyasındaki `extension_dir`, Windows kullanıcı adındaki Türkçe `İ` karakteri yüzünden yanlış çözümleniyor — bu nedenle komutlar düzeltilmiş bir `php.ini` kopyasıyla (`PHPRC` ortam değişkeni veya `-c` bayrağı ile) çalıştırılmalı.
