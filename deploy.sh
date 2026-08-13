#!/usr/bin/env bash
# İlkerMax — tek komutla hosting kurulumu.
# Kullanım: bu dosyayı sunucuya kopyalayın (SCP/SFTP), SSH ile bağlanın, çalıştırın:
#   bash deploy.sh
# Sırayla birkaç bilgi soracak (GitHub token, domain, MySQL bilgileri), gerisini otomatik yapar.

set -e

echo "=== İlkerMax kurulum ==="
echo

read -rp "GitHub token (Contents: Read-only, ilkermax reposu için): " GH_TOKEN
read -rp "Domain (ör. https://ornek.com, sonunda / OLMADAN): " SITE_URL
read -rp "MySQL veritabanı adı: " DB_NAME
read -rp "MySQL kullanıcı adı: " DB_USER
read -rsp "MySQL şifresi: " DB_PASS
echo
read -rp "Kurulum klasör adı (varsayılan: ilkermax): " APP_DIR
APP_DIR=${APP_DIR:-ilkermax}

echo
echo "→ Depo klonlanıyor..."
git clone "https://${GH_TOKEN}@github.com/yapixeltasarim-byte/ilkermax.git" "$APP_DIR"
cd "$APP_DIR"
git remote set-url origin "https://github.com/yapixeltasarim-byte/ilkermax.git"

echo "→ Bağımlılıklar kuruluyor (composer install)..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "→ .env hazırlanıyor..."
cp .env.production .env
sed -i "s#^APP_URL=.*#APP_URL=${SITE_URL}#" .env
sed -i "s#^DB_DATABASE=.*#DB_DATABASE=${DB_NAME}#" .env
sed -i "s#^DB_USERNAME=.*#DB_USERNAME=${DB_USER}#" .env
sed -i "s#^DB_PASSWORD=.*#DB_PASSWORD=${DB_PASS}#" .env

echo "→ Veritabanı kuruluyor ve demo verilerle dolduruluyor..."
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan ilkermax:setup-admin

ADMIN_EMAIL=$(grep '^FILAMENT_ADMIN_EMAIL=' .env | cut -d= -f2-)
ADMIN_PASSWORD=$(grep '^FILAMENT_ADMIN_PASSWORD=' .env | cut -d= -f2-)

echo
echo "=== KURULUM TAMAMLANDI ==="
echo "Site:        ${SITE_URL}"
echo "Admin panel: ${SITE_URL}/admin"
echo "Giriş:       ${ADMIN_EMAIL} / ${ADMIN_PASSWORD}"
echo
echo "TEK EKSİK ADIM: Hosting panelinizden domain'in belge kökünü (document root)"
echo "'${APP_DIR}/public' klasörüne yönlendirin (veya DEPLOY.md'deki B seçeneğini uygulayın)."
echo
echo "Not: 'test@example.com' kullanıcısı demo veriyle birlikte gelir, şifresi herkese"
echo "açık bir varsayılan değerdir — admin panele SADECE yukarıdaki gerçek hesapla girin,"
echo "isterseniz test@example.com'u sonradan silin."
