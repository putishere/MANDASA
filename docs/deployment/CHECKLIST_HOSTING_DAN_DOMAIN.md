# 🌐 CHECKLIST KESIAPAN HOSTING DAN DOMAIN

**Aplikasi:** Managemen Data Santri - PP HS AL-FAKKAR  
**Tanggal:** $(date)  
**Framework:** Laravel 12.0 | PHP ^8.2

---

## ❌ STATUS: **BELUM SIAP UNTUK HOSTING**

### ⚠️ ALASAN UTAMA: Masalah Keamanan Kritis Masih Ada

---

## 🔴 MASALAH KRITIS YANG HARUS DIPERBAIKI SEBELUM HOSTING:

### 1. Route Debug/Test Masih Aktif (SANGAT BERBAHAYA!)

**Ditemukan 7+ route yang masih aktif dan bisa diakses publik:**

```
❌ /fix-admin-password       - Bisa reset password admin
❌ /create-admin-now          - Bisa buat admin baru  
❌ /fix-login-admin           - Script perbaikan login
❌ /buat-admin                - Buat admin dengan password default
❌ /fix-all                   - Script perbaikan umum
❌ /fix-login-saput           - Script perbaikan user spesifik
❌ /admin/test-data-santri    - Script test data
```

**Dampak jika di-deploy:**
- 🔴 Siapapun bisa membuat/reset password admin
- 🔴 Siapapun bisa membuat admin baru
- 🔴 Data bisa dimanipulasi dari luar
- 🔴 Informasi sensitif ter-expose

**Status:** ❌ **BELUM DIPERBAIKI**

---

### 2. Hardcoded Credentials Masih Ada

**Lokasi:**
- `routes/web.php` - Password `admin123` ter-hardcode di 12+ lokasi
- `database/seeders/DatabaseSeeder.php` - Password default di seeder

**Dampak:**
- 🔴 Credentials ter-expose jika kode di-push ke repository publik
- 🔴 Risiko unauthorized access

**Status:** ❌ **BELUM DIPERBAIKI**

---

### 3. File .env.example Tidak Ada

**Dampak:**
- ⚠️ Tidak ada template untuk konfigurasi hosting
- ⚠️ Risiko konfigurasi salah

**Status:** ❌ **BELUM DIBUAT**

---

## 📋 CHECKLIST LENGKAP UNTUK HOSTING:

### ✅ A. PERSYARATAN SERVER

| Persyaratan | Minimum | Recommended | Status |
|-------------|---------|--------------|--------|
| **PHP Version** | 8.2 | 8.2+ | ✅ OK |
| **PHP Extensions** | PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath | Semua + Fileinfo, GD | ⚠️ Perlu dicek |
| **Database** | MySQL 5.7+ / MariaDB 10.3+ / PostgreSQL 10+ | MySQL 8.0+ | ⚠️ Perlu konfigurasi |
| **Web Server** | Apache / Nginx | Nginx | ⚠️ Perlu setup |
| **Composer** | 2.x | Latest | ⚠️ Perlu dicek |
| **Node.js** | 18.x | 20.x | ⚠️ Perlu dicek |
| **NPM** | 9.x | Latest | ⚠️ Perlu dicek |

**Action Required:**
- [ ] Verifikasi semua PHP extensions terinstall
- [ ] Setup database (MySQL/PostgreSQL)
- [ ] Konfigurasi web server (Apache/Nginx)
- [ ] Install Composer di server
- [ ] Install Node.js dan NPM di server

---

### ✅ B. KONFIGURASI APLIKASI

#### B.1. File Environment (.env)

**Yang harus dibuat:**
- [ ] Buat file `.env.example` sebagai template
- [ ] Copy `.env.example` ke `.env` di server
- [ ] Isi semua variabel environment dengan nilai production

**Variabel penting yang harus di-set:**

```env
APP_NAME="Managemen Data Santri"
APP_ENV=production
APP_KEY=                    # Generate dengan: php artisan key:generate
APP_DEBUG=false             # WAJIB false untuk production!
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://yourdomain.com  # Ganti dengan domain Anda

DB_CONNECTION=mysql         # Atau pgsql untuk PostgreSQL
DB_HOST=127.0.0.1           # Atau IP database server
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_db
DB_PASSWORD=password_db    # Password kuat!

SESSION_DRIVER=database     # Atau redis untuk performa lebih baik
SESSION_LIFETIME=120

CACHE_STORE=database        # Atau redis untuk performa lebih baik

FILESYSTEM_DISK=local       # Atau s3 untuk cloud storage
```

**Status:** ❌ **BELUM SIAP** - File .env.example belum ada

---

#### B.2. Keamanan Route

**Yang harus dilakukan:**
- [ ] Hapus semua route debug/test dari `routes/web.php`
- [ ] Hapus hardcoded credentials
- [ ] Proteksi route admin dengan middleware yang tepat
- [ ] Setup rate limiting untuk login

**Status:** ❌ **BELUM SIAP** - Route debug masih aktif

---

#### B.3. Database

**Yang harus dilakukan:**
- [ ] Buat database baru di server hosting
- [ ] Buat user database dengan password kuat
- [ ] Jalankan migration: `php artisan migrate --force`
- [ ] Jalankan seeder (jika diperlukan, tapi hati-hati dengan password default)
- [ ] Setup backup database otomatis

**Status:** ⚠️ **PERLU KONFIGURASI**

---

### ✅ C. BUILD & OPTIMIZE

#### C.1. Frontend Assets

**Yang harus dilakukan:**
```bash
npm install
npm run build
```

**Hasil yang diharapkan:**
- [ ] Folder `public/build` terbuat
- [ ] File CSS dan JS sudah di-minify
- [ ] Vite manifest ter-generate

**Status:** ⚠️ **PERLU BUILD**

---

#### C.2. Laravel Optimization

**Yang harus dilakukan:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
composer install --optimize-autoloader --no-dev
```

**Status:** ⚠️ **PERLU OPTIMIZE**

---

#### C.3. Storage Link

**Yang harus dilakukan:**
```bash
php artisan storage:link
```

**Verifikasi:**
- [ ] Link `public/storage` → `storage/app/public` sudah ada
- [ ] Permission folder storage sudah benar (755 untuk folder, 644 untuk file)

**Status:** ⚠️ **PERLU SETUP**

---

### ✅ D. KONFIGURASI WEB SERVER

#### D.1. Apache (.htaccess)

**File:** `public/.htaccess` (sudah ada di Laravel)

**Yang perlu dicek:**
- [ ] File `.htaccess` ada di folder `public`
- [ ] Mod_rewrite sudah enabled
- [ ] Document root mengarah ke folder `public`

**Contoh Virtual Host Apache:**
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    
    DocumentRoot /path/to/your/app/public
    
    <Directory /path/to/your/app/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

**Status:** ⚠️ **PERLU KONFIGURASI**

---

#### D.2. Nginx

**Contoh konfigurasi Nginx:**
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /path/to/your/app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Status:** ⚠️ **PERLU KONFIGURASI**

---

### ✅ E. SSL/HTTPS

**Yang harus dilakukan:**
- [ ] Install SSL certificate (Let's Encrypt gratis)
- [ ] Redirect HTTP ke HTTPS
- [ ] Update `APP_URL` di `.env` ke `https://`
- [ ] Setup auto-renewal SSL certificate

**Status:** ⚠️ **PERLU SETUP**

---

### ✅ F. PERMISSIONS & SECURITY

#### F.1. File Permissions

**Yang harus di-set:**
```bash
# Folder storage dan bootstrap/cache harus writable
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# File lainnya
chmod -R 755 .
chmod -R 644 .env
```

**Status:** ⚠️ **PERLU SETUP**

---

#### F.2. Security Headers

**Tambahkan di web server config:**
- [ ] X-Frame-Options: SAMEORIGIN
- [ ] X-Content-Type-Options: nosniff
- [ ] X-XSS-Protection: 1; mode=block
- [ ] Strict-Transport-Security (HSTS)

**Status:** ⚠️ **PERLU SETUP**

---

### ✅ G. MONITORING & BACKUP

#### G.1. Error Logging

**Yang harus dilakukan:**
- [ ] Setup error logging (Laravel sudah ada, tapi perlu konfigurasi)
- [ ] Setup monitoring (Sentry, Bugsnag, atau custom)
- [ ] Setup log rotation

**Status:** ⚠️ **PERLU SETUP**

---

#### G.2. Database Backup

**Yang harus dilakukan:**
- [ ] Setup backup database otomatis (daily/weekly)
- [ ] Test restore backup
- [ ] Simpan backup di lokasi aman (cloud storage)

**Status:** ⚠️ **PERLU SETUP**

---

### ✅ H. DOMAIN & DNS

#### H.1. Domain Configuration

**Yang harus dilakukan:**
- [ ] Point domain ke IP server hosting
- [ ] Setup A record: `@` → IP server
- [ ] Setup A record: `www` → IP server (atau CNAME ke @)
- [ ] Setup MX record (jika menggunakan email)
- [ ] Verifikasi DNS propagation

**Status:** ⚠️ **PERLU KONFIGURASI**

---

#### H.2. Domain Settings

**Yang harus dicek:**
- [ ] Domain sudah terdaftar dan aktif
- [ ] Nameserver sudah benar
- [ ] DNS sudah propagate (cek dengan `nslookup` atau `dig`)

**Status:** ⚠️ **PERLU KONFIGURASI**

---

## 📊 RINGKASAN STATUS KESIAPAN:

| Aspek | Status | Keterangan |
|-------|--------|------------|
| **Keamanan Route** | ❌ TIDAK SIAP | Route debug masih aktif |
| **Hardcoded Credentials** | ❌ TIDAK SIAP | Masih ada di kode |
| **Environment Config** | ❌ TIDAK SIAP | .env.example belum ada |
| **Server Requirements** | ⚠️ PERLU CEK | Perlu verifikasi di server |
| **Database Setup** | ⚠️ PERLU SETUP | Perlu konfigurasi |
| **Web Server Config** | ⚠️ PERLU SETUP | Perlu konfigurasi |
| **SSL/HTTPS** | ⚠️ PERLU SETUP | Perlu install certificate |
| **Build Assets** | ⚠️ PERLU BUILD | Perlu npm run build |
| **Optimization** | ⚠️ PERLU OPTIMIZE | Perlu artisan optimize |
| **Domain & DNS** | ⚠️ PERLU SETUP | Perlu konfigurasi |

---

## 🎯 KESIMPULAN:

### ❌ **APLIKASI BELUM SIAP UNTUK HOSTING**

**Alasan utama:**
1. 🔴 **Masalah keamanan kritis** - Route debug masih aktif
2. 🔴 **Hardcoded credentials** - Password masih ter-hardcode
3. ❌ **Tidak ada .env.example** - Tidak ada template konfigurasi

**Yang harus dilakukan SEBELUM hosting:**
1. ✅ Hapus semua route debug/test
2. ✅ Hapus hardcoded credentials
3. ✅ Buat file .env.example
4. ✅ Test aplikasi di environment staging (jika ada)

**Yang harus dilakukan SETELAH hosting:**
1. ✅ Setup server requirements
2. ✅ Konfigurasi database
3. ✅ Setup web server (Apache/Nginx)
4. ✅ Install SSL certificate
5. ✅ Build assets dan optimize
6. ✅ Setup backup dan monitoring

---

## 📝 LANGKAH-LANGKAH DEPLOYMENT:

### Pre-Deployment (Lokal):

```bash
# 1. Hapus route debug (WAJIB!)
# Edit routes/web.php dan hapus semua route debug

# 2. Buat .env.example
# Copy template dan buat file .env.example

# 3. Build assets
npm install
npm run build

# 4. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 5. Test aplikasi
php artisan serve
# Test semua fitur

# 6. Commit ke git (jangan commit .env!)
git add .
git commit -m "Prepare for production deployment"
git push
```

### Deployment (Server):

```bash
# 1. Clone repository
git clone your-repo-url
cd your-app-folder

# 2. Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 3. Setup environment
cp .env.example .env
# Edit .env dengan nilai production
php artisan key:generate

# 4. Setup database
php artisan migrate --force
# Jalankan seeder jika diperlukan (hati-hati dengan password default!)

# 5. Setup storage
php artisan storage:link
chmod -R 775 storage bootstrap/cache

# 6. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 7. Setup permissions
chown -R www-data:www-data storage bootstrap/cache
```

---

## ⚠️ PERINGATAN PENTING:

1. **JANGAN deploy dengan route debug masih aktif** - Ini sangat berbahaya!
2. **JANGAN commit file .env** - Pastikan sudah di .gitignore
3. **JANGAN gunakan password default** di production - Ganti segera!
4. **PASTIKAN APP_DEBUG=false** di production
5. **SETUP backup database** secara berkala
6. **SETUP SSL/HTTPS** untuk keamanan data
7. **TEST semua fitur** setelah deployment

---

**Status Akhir:** ❌ **BELUM SIAP UNTUK HOSTING**

**Rekomendasi:** Perbaiki masalah keamanan kritis terlebih dahulu sebelum melakukan hosting.

