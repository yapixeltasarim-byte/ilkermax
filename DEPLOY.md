# İlkerMax — Hosting'e Kurulum (tek seferlik)

Bu kılavuz, terminal/SSH erişiminiz olmasa bile (sadece cPanel/FTP ile) siteyi tek seferde canlıya almanız için hazırlandı.

## 1. Hazırlık — hosting panelinde

1. cPanel (veya kullandığınız panel) üzerinden **yeni bir MySQL veritabanı** ve bir **veritabanı kullanıcısı** oluşturun, kullanıcıyı veritabanına tam yetkiyle bağlayın. Şu bilgileri not edin: veritabanı adı, kullanıcı adı, şifre.
2. Domain'inizin (veya alt domain'inizin) **belge kökünü (document root)** `public/` klasörüne yönlendirebiliyor musunuz kontrol edin (genelde "Domains" veya "Alan Adları" bölümünde ayarlanır). Yönlendiremiyorsanız sorun değil — aşağıda B seçeneği bunu çözüyor.

## 2. Dosyaları yükleyin

1. Size verilen `ilkermax-deploy.zip` dosyasını hosting'inize (cPanel Dosya Yöneticisi veya FTP ile) yükleyin.
2. **A) Belge kökünü `public/`e yönlendirebiliyorsanız**: zip'i domain'in bağlı olduğu klasöre (örn. `public_html/ilkermax/`) çıkarın, sonra panelden o klasörün içindeki `public` alt klasörünü belge kökü olarak ayarlayın.
3. **B) Yönlendiremiyorsanız** (çoğu paylaşımlı hosting'te `public_html` zaten kök'tür): zip'i `public_html` içine çıkarın, sonra `public_html/public/` klasörünün İÇİNDEKİ her şeyi `public_html`'in köküne taşıyın, son olarak kalan `public_html/index.php` dosyasını açıp içindeki iki `__DIR__` satırını şöyle güncelleyin:
   ```php
   require __DIR__.'/vendor/autoload.php';
   $app = require_once __DIR__.'/bootstrap/app.php';
   ```
   (Yani `/../` kısımlarını silin — çünkü artık `vendor` ve `bootstrap` klasörleri `public_html` ile aynı seviyede değil, `index.php` ile aynı seviyede.)

## 3. `.env` dosyasını düzenleyin

Yüklediğiniz paketteki `.env.production` dosyasını `.env` olarak yeniden adlandırın (veya içeriğini mevcut `.env`'e kopyalayın) ve şu satırları **kendi bilgilerinizle** doldurun:

- `APP_URL` — gerçek domain'iniz (`https://...`)
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` — adım 1'de oluşturduğunuz bilgiler
- `FILAMENT_ADMIN_EMAIL` — admin panele gireceğiniz e-posta (varsayılan zaten sizin e-postanız, değiştirmek isterseniz değiştirin)

`APP_KEY`, `DEPLOY_SECRET`, `FILAMENT_ADMIN_PASSWORD` ve `BOT_API_KEY` zaten hazır değerlerle geliyor, dokunmanıza gerek yok.

## 4. Kurulumu çalıştırın (tek tıkla)

`.env`'i kaydettikten sonra, tarayıcıda şu adresi açın (kendi domain'iniz ve `.env`'deki `DEPLOY_SECRET` değeriyle):

```
https://SIZIN-DOMAININIZ.com/_deploy/abfb9448006cb2e4bc1c20a12713f5eaa2167ce47ce833d0
```

Bu adres veritabanı tablolarını oluşturur, gerçek Kocaeli il/ilçe/mahalle + kategori referans verisini yükler, fotoğraf yükleme klasörünü bağlar ve admin kullanıcınızı oluşturur. Ekranda "Kurulum tamamlandı" yazısını görmelisiniz.

**Önemli — kurulumdan hemen sonra:** `.env` dosyasından `DEPLOY_SECRET` satırını silin (bu adresi tekrar kimse çalıştıramasın diye).

## 5. Giriş yapın

- **Site**: `https://SIZIN-DOMAININIZ.com`
- **Admin panel**: `https://SIZIN-DOMAININIZ.com/admin` — `.env`'deki `FILAMENT_ADMIN_EMAIL` / `FILAMENT_ADMIN_PASSWORD` ile giriş yapın, ardından profil ayarlarından şifrenizi değiştirin.

Site sıfır ilanla açılır (sahte demo veri yok) — ilanları admin panelden veya Telegram bot üzerinden (ayrı kurulacak, bkz. `ilkermax-bot` projesi) ekleyebilirsiniz.

## SSH/Composer erişiminiz varsa (alternatif, daha temiz yöntem)

Yukarıdaki tek-tık kurulumu atlayıp klasik yöntemi tercih edebilirsiniz:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan db:seed --class="Database\Seeders\ProductionSeeder" --force
php artisan make:filament-user
```

Bu durumda zip'in içindeki `vendor/` klasörünü yüklemenize gerek yok, `composer install` yeterli.

## Sorun giderme

- **500 hatası**: `.env`'deki `APP_DEBUG=true` yapıp sayfayı tekrar açın, hatayı görün, sonra tekrar `false` yapın.
- **"could not find driver" / veritabanı hatası**: hosting'inizde PHP'nin `pdo_mysql` eklentisinin açık olduğunu kontrol edin (genelde varsayılan açıktır).
- **Fotoğraflar görünmüyor**: `APP_URL`'in `.env`'de gerçek domain'inizle birebir aynı olduğundan emin olun.
