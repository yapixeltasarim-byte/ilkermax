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

# Hostinger'da hesabın BİRDEN FAZLA domaini varsa (addon domain), her domainin
# gerçek doküman kökü ~/public_html DEĞİL, ~/domains/<domain>/public_html'dir.
# ~/public_html sadece hesabın birincil/ana domainine aittir — onu KARIŞTIRMAMAK
# için önce doğru yolu tespit ediyoruz.
DOMAIN_HOST=$(echo "$SITE_URL" | sed -E 's#^https?://##; s#/.*$##')
if [ -d "$HOME/domains/$DOMAIN_HOST/public_html" ]; then
    WEB_ROOT="$HOME/domains/$DOMAIN_HOST/public_html"
else
    WEB_ROOT="$HOME/public_html"
fi
echo "→ Doküman kökü: $WEB_ROOT"

# Script kendi APP_DIR'ini silip yeniden klonluyor; script bu klasörün
# içinden çalıştırılırsa "rm -rf" çalışma dizinini ayağının altından
# kaldırıp git clone'u bozar. Önce güvenli bir dizine (HOME) geçiyoruz.
cd "$HOME"

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
DB_HOST=localhost
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

echo "→ index.php'deki yollar ilkermax klasörünün mutlak yoluna göre düzeltiliyor..."
# Mutlak yol kullanıyoruz (göreli "../ilkermax" değil) çünkü WEB_ROOT'un HOME'a
# göre derinliği domain'e göre değişebilir (~/public_html mi, ~/domains/x/public_html mi).
sed -i "s#__DIR__\.'/\.\./storage/framework/maintenance\.php'#'$APP_DIR/storage/framework/maintenance.php'#" "$WEB_ROOT/index.php"
sed -i "s#__DIR__\.'/\.\./vendor/autoload\.php'#'$APP_DIR/vendor/autoload.php'#" "$WEB_ROOT/index.php"
sed -i "s#__DIR__\.'/\.\./bootstrap/app\.php'#'$APP_DIR/bootstrap/app.php'#" "$WEB_ROOT/index.php"

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
