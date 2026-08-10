# İlkerMax Bot

İlkerMax emlak sitesine ([D:\ilkermax](../ilkermax)) Telegram üzerinden ilan girmek için kullanılan bot. Bot: **@ilkermax_bot**.

Bu proje Laravel sitesinden tamamen bağımsızdır — sadece sitenin `/api/properties` endpoint'lerine HTTP üzerinden istek atar (bkz. `D:\ilkermax\README.md` içindeki "Bot API" bölümü).

## Stack

Node.js + TypeScript + [grammY](https://grammy.dev) + [`@grammyjs/conversations`](https://grammy.dev/plugins/conversations) (adım adım diyalog akışı için).

## Kurulum

```bash
npm install
cp .env.example .env
```

`.env` dosyasını doldurun:

- `BOT_TOKEN` — [@BotFather](https://t.me/BotFather)'dan `/newbot` ile alınır.
- `ALLOWED_TELEGRAM_IDS` — botu kullanmasına izin verilen Telegram kullanıcı ID'leri, virgülle ayrılmış. Kendi ID'nizi öğrenmek için Telegram'da [@userinfobot](https://t.me/userinfobot)'a `/start` yazın.
- `API_BASE_URL` — Laravel sitesinin adresi (yerelde `http://127.0.0.1:8000`, production'da gerçek domain).
- `API_KEY` — Laravel `.env`'deki `BOT_API_KEY` ile **birebir aynı** olmalı.
- `SITE_URL` — ilan yayınlandıktan sonra kullanıcıya gösterilecek link için (genelde `API_BASE_URL` ile aynı).

## Çalıştırma

```bash
npm run dev      # geliştirme (dosya değişince otomatik yeniden başlar)
npm run typecheck
npm run build && npm start   # production
```

## Kullanım

1. Telegram'da bota `/yeniilan` yazın.
2. Bot sırayla sorar: ilan tipi, fiyat, konum (il/ilçe/mahalle), oda sayısı, net m², açıklama.
3. İlan Laravel API'sinde oluşturulur (durum otomatik `published` — onay beklemez).
4. Fotoğrafları teker teker gönderin, bitince `/bitir` yazın — her foto anında yüklenir, ilk foto kapak fotoğrafı olur.
5. Bot ilanın public link'ini paylaşır.

`ALLOWED_TELEGRAM_IDS` listesinde olmayan kullanıcılar botu kullanamaz.

## Deploy

Bu bot `long polling` ile çalışır (webhook değil) — sürekli açık bir process gerektirir, bu yüzden paylaşımlı PHP hosting'de barındırılamaz. Ücretsiz/ucuz bir Node.js platformuna (ör. [Railway](https://railway.app), [Render](https://render.com)) deploy edilmesi önerilir: `npm run build` sonrası `npm start` komutunu çalıştıracak şekilde ayarlanır, ortam değişkenleri (`.env` içeriği) platformun kendi ortam değişkeni ayarlarına girilir.
