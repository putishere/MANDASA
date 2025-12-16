#!/bin/bash

# Script Helper untuk Deploy Laravel ke Hosting
# Gunakan script ini jika hosting Anda mendukung SSH

echo "=========================================="
echo "  Laravel Deploy Helper Script"
echo "  Managemen Data Santri"
echo "=========================================="
echo ""

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fungsi untuk menampilkan pesan
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Cek apakah di folder aplikasi Laravel
if [ ! -f "artisan" ]; then
    print_error "File artisan tidak ditemukan. Pastikan Anda berada di root folder aplikasi Laravel."
    exit 1
fi

print_info "Memulai proses deploy..."

# 1. Install/Update Composer Dependencies
print_info "1. Menginstall dependencies Composer..."
composer install --no-dev --optimize-autoloader
if [ $? -eq 0 ]; then
    print_success "Composer dependencies berhasil diinstall"
else
    print_error "Gagal menginstall Composer dependencies"
    exit 1
fi

# 2. Generate Application Key (jika belum ada)
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    print_info "2. Generate application key..."
    php artisan key:generate
    if [ $? -eq 0 ]; then
        print_success "Application key berhasil di-generate"
    else
        print_error "Gagal generate application key"
        exit 1
    fi
else
    print_info "2. Application key sudah ada, skip..."
fi

# 3. Run Migration
print_info "3. Menjalankan migration database..."
php artisan migrate --force
if [ $? -eq 0 ]; then
    print_success "Migration berhasil dijalankan"
else
    print_error "Gagal menjalankan migration"
    exit 1
fi

# 4. Create Storage Link
print_info "4. Membuat storage link..."
php artisan storage:link
if [ $? -eq 0 ]; then
    print_success "Storage link berhasil dibuat"
else
    print_error "Gagal membuat storage link"
fi

# 5. Set Permission
print_info "5. Mengatur permission folder..."
chmod -R 775 storage bootstrap/cache
if [ $? -eq 0 ]; then
    print_success "Permission berhasil diatur"
else
    print_error "Gagal mengatur permission"
fi

# 6. Clear Cache
print_info "6. Membersihkan cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
print_success "Cache berhasil dibersihkan"

# 7. Cache untuk Production
print_info "7. Membuat cache untuk production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
if [ $? -eq 0 ]; then
    print_success "Cache production berhasil dibuat"
else
    print_error "Gagal membuat cache production"
fi

# 8. Optimize Autoloader
print_info "8. Optimize autoloader..."
composer dump-autoload --optimize
if [ $? -eq 0 ]; then
    print_success "Autoloader berhasil dioptimize"
else
    print_error "Gagal optimize autoloader"
fi

echo ""
echo "=========================================="
print_success "Deploy selesai!"
echo "=========================================="
echo ""
print_info "Pastikan:"
echo "  - File .env sudah dikonfigurasi dengan benar"
echo "  - Database sudah dibuat dan dikonfigurasi"
echo "  - Document root mengarah ke folder public/"
echo "  - SSL certificate sudah diinstall"
echo "  - Permission folder storage dan bootstrap/cache sudah benar (775)"
echo ""

