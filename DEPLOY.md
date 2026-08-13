# İlkerMax — Hosting'e Kurulum (tek seferlik)

SSH erişiminiz olduğu için ana yöntem GitHub'dan `git clone` + `composer install`. (SSH'sız bir hosting'e geçerseniz alt kısımdaki "SSH yoksa" bölümünü kullanın — zip paketiyle çalışır.)

## 1. Hazırlık — hosting panelinde

1. cPanel (veya kullandığınız panel) üzerinden **yeni bir MySQL veritabanı** ve bir **veritabanı kullanıcısı** oluşturun, kullanıcıyı veritabanına tam yetkiyle bağlayın. Şu bilgileri not edin: veritabanı adı, kullanıcı adı, şifre.
2. Domain'inizin **belge kökünü (document root)** `public/` klasörüne yönlendirebiliyor musunuz kontrol edin. Yönlendiremiyorsanız, aşağıdaki B seçeneğini kullanın.

## 2. GitHub'da salt-okunur bir erişim anahtarı (token) oluşturun

Repo **private** olduğu için sunucudan `git clone` yapabilmek için bir token gerekiyor:

1. GitHub'da sağ üstteki profil fotoğrafınıza tıklayın → **Settings** → sol menüde en altta **Developer settings** → **Personal access tokens** → **Fine-grained tokens** → **Generate new token**.
2. İsim verin (örn. "ilkermax-hosting"), **Repository access**'te "Only select repositories" seçip **ilkermax** reposunu seçin.
3. **Permissions** altında **Contents: Read-only** yeterli.
4. Oluşan token'ı (bir kere gösterilir, kopyalayın) not edin.

## 3. Sunucuya bağlanıp projeyi çekin

SSH ile hosting'inize bağlanın, sitenin duracağı klasöre gidin (örn. `public_html`'in üstündeki bir klasör), sonra:

```bash
git clone https://<TOKEN>@github.com/yapixeltasarim-byte/ilkermax.git ilkermax
cd ilkermax
composer install --no-dev --optimize-autoloader
```

`<TOKEN>` yerine adım 2'de aldığınız token'ı yazın. Klonlama bittikten sonra, token'ın `.git/config`'te düz yazılı kalmaması için:

```bash
git remote set-url origin https://github.com/yapixeltasarim-byte/ilkermax.git
```

## 4. Belge kökünü ayarlayın

**A) Belge kökünü `public/`e yönlendirebiliyorsanız**: panelden `ilkermax/public` klasörünü domain'in belge kökü yapın.

**B) Yönlendiremiyorsanız**: `ilkermax` klasörünün TAMAMINI `public_html` içine taşıyın (veya oraya klonlayın), `public_html/ilkermax/public/` içindeki her şeyi `public_html`'in köküne taşıyın, kalan `public_html/index.php`'yi açıp `__DIR__.'/../` kısımlarındaki `/../`'ı silin (artık `vendor`/`bootstrap` aynı klasörde).

## 5. `.env` dosyasını düzenleyin

Projede gelen `.env.production` dosyasını `.env` olarak yeniden adlandırın, şu satırları **kendi bilgilerinizle** doldurun:

- `APP_URL` — gerçek domain'iniz (`https://...`)
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` — adım 1'de oluşturduğunuz bilgiler

`APP_KEY` ve `BOT_API_KEY` zaten hazır değerlerle geliyor, dokunmanıza gerek yok. `DEPLOY_SECRET`/`FILAMENT_ADMIN_*` satırlarına SSH'la kurulumda gerek yok, silebilirsiniz.

## 6. Veritabanını kurun ve demo verilerle doldurun

```bash
php artisan migrate --force
php artisan storage:link
php artisan db:seed --force
```

Bu, gerçek Kocaeli il/ilçe/mahalle + kategori referans verisini VE **demo amaçlı 15 örnek ilanı** (fiyat, foto, konum örnekleriyle — müşteriye "site böyle görünecek" demek için) yükler.

## 7. Gerçek admin girişinizi oluşturun

⚠️ **Önemli güvenlik notu:** Demo veri seed'i bir `test@example.com` kullanıcısı da oluşturur, şifresi Laravel'in **herkese açık varsayılan test şifresi** ("password") olur. Bu hesapla admin paneline kimse girmesin diye, kendi gerçek admin hesabınızı ayrıca oluşturun:

```bash
php artisan make:filament-user
```

(İsminizi, kendi e-postanızı ve güçlü bir şifre girin.) İsterseniz `test@example.com` kullanıcısını admin panelden ("Users" varsa) veya `php artisan tinker` ile silin — sadece giriş amaçlı, ilan verilerini etkilemez.

## 8. Giriş yapın

- **Site**: `https://SIZIN-DOMAININIZ.com`
- **Admin panel**: `https://SIZIN-DOMAININIZ.com/admin` — adım 7'de oluşturduğunuz hesapla girin.

## Güncelleme (bundan sonraki değişiklikler için)

```bash
cd ilkermax
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
```

## SSH yoksa (zip ile alternatif kurulum)

SSH'sız bir hosting kullanırsanız, size ayrıca gönderilen `ilkermax-deploy.zip` dosyasını (vendor/ dahil) cPanel Dosya Yöneticisi'yle yükleyip çıkarabilir, `.env.production`'ı doldurup `.env` yapabilir, sonra tarayıcıda bir kerelik `/_deploy/<DEPLOY_SECRET>` adresini ziyaret ederek (route otomatik migrate + storage:link + **temiz** referans verisi — demo ilan İÇERMEZ — + admin kullanıcı oluşturur) kurulumu tamamlayabilirsiniz. Bu route sadece `.env`'de `DEPLOY_SECRET` tanımlıyken çalışır; kullandıktan sonra o satırı silin.

## Sorun giderme

- **500 hatası**: `.env`'deki `APP_DEBUG=true` yapıp sayfayı tekrar açın, hatayı görün, sonra tekrar `false` yapın.
- **"could not find driver" / veritabanı hatası**: hosting'inizde PHP'nin `pdo_mysql` eklentisinin açık olduğunu kontrol edin (genelde varsayılan açıktır).
- **Fotoğraflar görünmüyor**: `APP_URL`'in `.env`'de gerçek domain'inizle birebir aynı olduğundan emin olun.
- **`git clone` "Authentication failed" hatası**: token'ı doğru kopyaladığınızdan ve "Contents: Read-only" iznine sahip olduğundan emin olun; token süresi dolmuş olabilir, yenisini oluşturun.
