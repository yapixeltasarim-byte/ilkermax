# İlkerMax — Hosting'e Tek Komutla Kurulum

## 1. Hazırlık (2 dakika)

1. **GitHub token**: [github.com/settings/tokens?type=beta](https://github.com/settings/tokens?type=beta) → **Generate new token** → isim verin, **Repository access**'te sadece **ilkermax** reposunu seçin, **Permissions** → **Contents: Read-only**. Oluşan token'ı kopyalayın (bir kere gösterilir).
2. Hosting panelinizde (cPanel vb.) **yeni bir MySQL veritabanı + kullanıcı** oluşturun, adını/kullanıcı adını/şifresini not edin.

## 2. Script'i çalıştırın

Size ayrıca gönderilen **`deploy.sh`** dosyasını SCP/SFTP ile sunucunuza kopyalayın, SSH ile bağlanıp çalıştırın:

```bash
bash deploy.sh
```

Sırayla 5 şey soracak: GitHub token, domain, MySQL veritabanı adı/kullanıcı/şifre. Sonrasında hepsini otomatik yapar: depoyu indirir, `composer install` çalıştırır, `.env`'i doldurur, veritabanını kurup demo verilerle (örnek ilanlar) doldurur, ve size özel bir admin girişi oluşturur. Sonunda ekrana **site adresi + admin giriş bilgilerinizi** yazdırır.

## 3. Tek elle yapmanız gereken şey

Script bittikten sonra hosting panelinizden domain'in **belge kökünü (document root)** script'in oluşturduğu klasördeki `public` alt klasörüne yönlendirin (genelde "Domains" bölümünde). Yönlendiremiyorsanız:

`ilkermax` klasörünün içindekileri `public_html`'e taşıyın, `public/` içindeki her şeyi `public_html`'in köküne taşıyın, kalan `public_html/index.php`'de `__DIR__.'/../` kısımlarındaki `/../`'ı silin.

Bu kadar. Site demo ilanlarla (örnek fiyat/foto/konum) açılır — gerçek ilanları admin panelden veya bottan ekleyebilirsiniz. `test@example.com` demo kullanıcısının şifresi herkese açık bir varsayılan değerdir, sadece script'in size verdiği gerçek admin hesabıyla giriş yapın.

## Güncelleme (sonraki değişiklikler için)

```bash
cd ilkermax   # veya script'te verdiğiniz klasör adı
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
```

## SSH'ınız yoksa

`deploy.sh` SSH gerektirir. SSH'sız bir hosting kullanıyorsanız, ayrıca gönderilen `ilkermax-deploy.zip`'i (vendor dahil) cPanel Dosya Yöneticisi'yle yükleyip çıkarın, `.env.production`'ı `.env` yapıp doldurun, tarayıcıda bir kez `/_deploy/<DEPLOY_SECRET>` adresini açın (kılavuzun eski sürümünde detaylandırılmıştı — isterseniz tekrar yazayım).

## Sorun giderme

- **500 hatası**: `.env`'de `APP_DEBUG=true` yapıp tekrar açın, hatayı görün, sonra `false`'a döndürün.
- **Veritabanı hatası**: hosting'inizde `pdo_mysql` PHP eklentisinin açık olduğunu kontrol edin.
- **Fotoğraflar görünmüyor**: `.env`'deki `APP_URL` gerçek domain'inizle birebir aynı olmalı.
- **`git clone` "Authentication failed"**: token'ı doğru kopyaladığınızdan ve "Contents: Read-only" izniyle oluşturduğunuzdan emin olun.
