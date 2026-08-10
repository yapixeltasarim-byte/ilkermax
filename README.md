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

## Bot API

Telegram bot'un (Faz 5, ayrı repo) ilan girmesi için iki endpoint var. Her ikisi de `X-API-Key` header'ı ister (`.env`'deki `BOT_API_KEY` ile eşleşmeli). Bot'tan gelen ilanlar doğrudan `published` olur, onay beklemez.

**1. İlan oluştur**

```
POST /api/properties
X-API-Key: <BOT_API_KEY>
Content-Type: application/json

{
  "listing_type": "sale",
  "price": 4500000,
  "currency": "TRY",
  "rooms": "3+1",
  "area_net": 120,
  "province": "Kocaeli",
  "district": "İzmit",
  "neighborhood": "Yenişehir",
  "category": "Daire",
  "description": "Deniz manzaralı..."
}
```

→ `201 { "id": 42, "upload_url": "/api/properties/42/images" }`

`province`/`district`/`neighborhood` gönderilen konum mevcut değilse otomatik oluşturulur; `category` da aynı şekilde. `title` gönderilmezse otomatik üretilir.

**2. Fotoğraf yükle** (her fotoğraf için ayrı çağrı, `multipart/form-data`)

```
POST /api/properties/42/images
X-API-Key: <BOT_API_KEY>
Content-Type: multipart/form-data

image=<dosya>
```

→ `201 { "id": 7, "url": "http://.../storage/properties/xxx.jpg" }`

İlk yüklenen fotoğraf otomatik kapak fotoğrafı olur.

## Yerel geliştirme

Bu makinede [Laravel Herd](https://herd.laravel.com) kullanılıyor. Herd'in `php.ini` dosyasındaki `extension_dir`, Windows kullanıcı adındaki Türkçe `İ` karakteri yüzünden yanlış çözümleniyor — bu nedenle komutlar düzeltilmiş bir `php.ini` kopyasıyla (`PHPRC` ortam değişkeni veya `-c` bayrağı ile) çalıştırılmalı.
