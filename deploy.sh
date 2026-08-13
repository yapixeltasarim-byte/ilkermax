#!/usr/bin/env bash
# İlkerMax — tek komutla hosting kurulumu.
# Bu sürüm, domain'in belge kökü DEĞİŞTİRİLEMEYEN hosting'ler için (ör. Hostinger'da
# public_html sabit köktür). Uygulama kodu ~/ilkermax'ta durur, sadece public/
# içeriği public_html'e kopyalanır.
#
# Kullanım: bu dosyayı sunucuya kopyalayın (SCP/SFTP), SSH ile bağlanıp çalıştırın:
#   bash deploy.sh

set -e

echo "=== İlkerMax kurulum (public_html = sabit kök) ==="
echo

read -rp "Domain (ör. https://ornek.com, sonunda / OLMADAN): " SITE_URL
read -rp "MySQL veritabanı adı: " DB_NAME
read -rp "MySQL kullanıcı adı: " DB_USER
read -rsp "MySQL şifresi: " DB_PASS
echo

APP_DIR="$HOME/ilkermax"
WEB_ROOT="$HOME/public_html"

echo
echo "→ Depo klonlanıyor..."
rm -rf "$APP_DIR"
git clone "https://github.com/yapixeltasarim-byte/ilkermax.git" "$APP_DIR"
cd "$APP_DIR"

echo "→ Bağımlılıklar kuruluyor (composer install)..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "→ .env oluşturuluyor..."
APP_KEY_VALUE="base64:$(openssl rand -base64 32)"
ADMIN_PASSWORD_VALUE=$(openssl rand -hex 8)
BOT_KEY_VALUE=$(openssl rand -hex 20)

cat > .env <<ENVEOF
APP_NAME=İlkerMax
APP_ENV=production
APP_KEY=${APP_KEY_VALUE}
APP_DEBUG=false
APP_URL=${SITE_URL}

APP_LOCALE=tr
APP_FALLBACK_LOCALE=tr
APP_FAKER_LOCALE=tr_TR

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=${DB_NAME}
DB_USERNAME=${DB_USER}
DB_PASSWORD=${DB_PASS}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

VITE_APP_NAME="\${APP_NAME}"

BOT_API_KEY=${BOT_KEY_VALUE}

FILAMENT_ADMIN_EMAIL=yapixeltasarim@gmail.com
FILAMENT_ADMIN_PASSWORD=${ADMIN_PASSWORD_VALUE}
ENVEOF

echo "→ Veritabanı kuruluyor ve demo verilerle dolduruluyor..."
php artisan migrate --force
php artisan db:seed --force
php artisan ilkermax:setup-admin

echo "→ public_html hazırlanıyor (mevcut içerik temizlenip public/ kopyalanıyor)..."
mkdir -p "$WEB_ROOT"
find "$WEB_ROOT" -mindepth 1 -delete
cp -a "$APP_DIR/public/." "$WEB_ROOT/"

echo "→ index.php'deki yollar public_html'in dışındaki ilkermax klasörüne göre düzeltiliyor..."
sed -i "s#__DIR__\.'/\.\./storage/framework/maintenance\.php'#__DIR__.'/../ilkermax/storage/framework/maintenance.php'#" "$WEB_ROOT/index.php"
sed -i "s#__DIR__\.'/\.\./vendor/autoload\.php'#__DIR__.'/../ilkermax/vendor/autoload.php'#" "$WEB_ROOT/index.php"
sed -i "s#__DIR__\.'/\.\./bootstrap/app\.php'#__DIR__.'/../ilkermax/bootstrap/app.php'#" "$WEB_ROOT/index.php"

echo "→ Fotoğraf yükleme klasörü bağlanıyor..."
rm -rf "$WEB_ROOT/storage"
ln -sfn "$APP_DIR/storage/app/public" "$WEB_ROOT/storage"

ADMIN_EMAIL=$(grep '^FILAMENT_ADMIN_EMAIL=' .env | cut -d= -f2-)
ADMIN_PASSWORD=$(grep '^FILAMENT_ADMIN_PASSWORD=' .env | cut -d= -f2-)

echo
echo "=== KURULUM TAMAMLANDI ==="
echo "Site:        ${SITE_URL}"
echo "Admin panel: ${SITE_URL}/admin"
echo "Giriş:       ${ADMIN_EMAIL} / ${ADMIN_PASSWORD}"
echo
echo "Not: 'test@example.com' kullanıcısı demo veriyle birlikte gelir, şifresi herkese"
echo "açık bir varsayılan değerdir — admin panele SADECE yukarıdaki gerçek hesapla girin."
echo
echo "Güncelleme yapmak isterseniz: cd ~/ilkermax && git pull && composer install --no-dev"
echo "ve sonra bu script'in public_html kopyalama kısmını tekrar çalıştırın."
