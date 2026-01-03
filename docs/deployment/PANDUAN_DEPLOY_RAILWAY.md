# 🚂 Panduan Lengkap Deploy ke Railway

**Tanggal:** 2025-01-02  
**Aplikasi:** Managemen Data Santri - PP HS AL-FAKKAR  
**Platform:** Railway.app

---

## ✅ **PERSIAPAN SEBELUM DEPLOY**

### 1. Pastikan Repository Sudah di GitHub

```bash
# Cek apakah sudah ada remote GitHub
git remote -v

# Jika belum, tambahkan remote GitHub
git remote add origin https://github.com/username/repository-name.git
git push -u origin main
```

### 2. Pastikan File Penting Sudah Ada

- ✅ `composer.json` - Dependencies PHP
- ✅ `package.json` - Dependencies Node.js
- ✅ `.env.example` atau `ENV_TEMPLATE.txt` - Template environment
- ✅ `public/index.php` - Entry point Laravel
- ✅ Semua file sudah di-commit

---

## 🚀 **LANGKAH DEPLOY KE RAILWAY**

### Langkah 1: Buat Akun Railway (Gratis!)

1. **Kunjungi Railway**
   - Buka https://railway.app
   - Klik "Start a New Project"

2. **Login dengan GitHub**
   - Pilih "Login with GitHub"
   - Authorize Railway (gratis, tidak perlu kartu kredit)
   - Selesai! Anda dapat **$5 credit gratis/bulan**

---

### Langkah 2: Create New Project

1. **Klik "New Project"**
   - Di dashboard Railway
   - Pilih "Deploy from GitHub repo"

2. **Pilih Repository**
   - Pilih repository GitHub Anda
   - Railway akan otomatis detect Laravel

3. **Railway Auto-Setup**
   - Railway akan otomatis:
     - Detect PHP/Laravel
     - Setup build command
     - Setup start command

---

### Langkah 3: Add PostgreSQL Database

1. **Klik "New"** di project
2. **Pilih "Database"**
3. **Pilih "PostgreSQL"**
4. **Railway akan otomatis setup:**
   - Database instance
   - Connection string
   - Environment variables

**Catatan:** Railway menggunakan PostgreSQL, bukan MySQL. Laravel kompatibel dengan PostgreSQL.

---

### Langkah 4: Setup Environment Variables

1. **Klik pada Web Service** (bukan database)
2. **Klik tab "Variables"**
3. **Tambahkan environment variables:**

```env
APP_NAME="Managemen Data Santri"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://your-app.railway.app

# Database (akan auto-setup dari PostgreSQL service)
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database

# Queue
QUEUE_CONNECTION=database

# Filesystem
FILESYSTEM_DISK=local

# Mail (gunakan log untuk testing)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Catatan:** 
- `${{Postgres.PGHOST}}` adalah syntax Railway untuk reference database
- Ganti `your-app.railway.app` dengan URL yang diberikan Railway

---

### Langkah 5: Generate APP_KEY

1. **Buka Terminal di Railway**
   - Klik pada Web Service
   - Klik tab "Deployments"
   - Klik pada deployment terbaru
   - Klik "View Logs" → "Open Shell"

2. **Generate APP_KEY**
   ```bash
   php artisan key:generate
   ```

3. **Copy APP_KEY yang di-generate**
   - Tambahkan ke Environment Variables:
     ```
     APP_KEY=base64:xxxxx...
     ```

---

### Langkah 6: Run Migration

Di terminal Railway yang sama:

```bash
# Run migration
php artisan migrate --force

# Setup storage link
php artisan storage:link

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### Langkah 7: Deploy!

1. **Railway akan otomatis deploy** setiap kali Anda push ke GitHub
2. **Atau klik "Redeploy"** di dashboard Railway
3. **Tunggu deployment selesai** (~2-5 menit)
4. **Aplikasi akan live** di URL yang diberikan Railway

---

## 🔧 **KONFIGURASI TAMBAHAN**

### Setup Build & Start Commands

Railway biasanya auto-detect, tapi jika perlu manual:

**Build Command:**
```bash
composer install --no-dev --optimize-autoloader && npm install && npm run build
```

**Start Command:**
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

**Catatan:** Railway akan otomatis set `$PORT` environment variable.

---

### Setup Custom Domain (Optional)

1. **Klik pada Web Service**
2. **Settings → Domains**
3. **Add Custom Domain**
4. **Setup DNS:**
   - Tambahkan CNAME record di domain provider:
     ```
     Type: CNAME
     Name: @ atau www
     Value: your-app.railway.app
     ```
5. **SSL otomatis** (gratis dari Railway)

---

## 📝 **PERUBAHAN UNTUK POSTGRESQL**

Railway menggunakan PostgreSQL, bukan MySQL. Perlu perubahan kecil:

### 1. Update composer.json (jika belum ada)

```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0",
        "doctrine/dbal": "^3.0"
    }
}
```

Lalu jalankan:
```bash
composer require doctrine/dbal
```

### 2. Cek Migration untuk Syntax MySQL Spesifik

Laravel biasanya kompatibel dengan PostgreSQL, tapi cek migration yang menggunakan:

- ✅ `VARCHAR` → OK (kompatibel)
- ✅ `TEXT` → OK (kompatibel)
- ✅ `INTEGER` → OK (kompatibel)
- ⚠️ `AUTO_INCREMENT` → PostgreSQL pakai `SERIAL` atau `BIGSERIAL`
- ⚠️ `TIMESTAMP` → PostgreSQL pakai `TIMESTAMP` (OK)

**Jika ada migration dengan `AUTO_INCREMENT`, update:**

```php
// Sebelum (MySQL)
$table->id(); // atau $table->bigIncrements('id');

// Sesudah (PostgreSQL compatible - sudah OK di Laravel)
$table->id(); // Laravel sudah handle ini dengan benar
```

**Laravel sudah handle perbedaan MySQL/PostgreSQL dengan baik, jadi biasanya tidak perlu perubahan!**

---

## 🔍 **TROUBLESHOOTING**

### Problem 1: Build Failed

**Error:** `composer install` failed

**Solusi:**
- Pastikan `composer.json` valid
- Cek log di Railway untuk detail error
- Pastikan PHP version sesuai (^8.2)

---

### Problem 2: Database Connection Failed

**Error:** `SQLSTATE[HY000] [2002] Connection refused`

**Solusi:**
- Pastikan PostgreSQL service sudah running
- Pastikan environment variables database sudah benar
- Pastikan menggunakan `${{Postgres.PGHOST}}` syntax

---

### Problem 3: APP_KEY Not Set

**Error:** `No application encryption key has been specified`

**Solusi:**
- Generate APP_KEY: `php artisan key:generate`
- Copy ke environment variables
- Redeploy aplikasi

---

### Problem 4: Migration Failed

**Error:** `SQL syntax error`

**Solusi:**
- Cek apakah ada syntax MySQL spesifik di migration
- Update migration untuk PostgreSQL compatible
- Atau gunakan Laravel migration yang sudah compatible

---

### Problem 5: Storage Permission Error

**Error:** `Permission denied` untuk storage

**Solusi:**
- Railway biasanya handle permission otomatis
- Pastikan `storage:link` sudah dijalankan
- Cek log untuk detail error

---

## 💰 **MENGHEMAT CREDIT DI RAILWAY**

### Tips:

1. **Monitor Credit Usage**
   - Cek dashboard Railway secara berkala
   - Lihat berapa credit yang terpakai

2. **Optimize Resource**
   - Gunakan resource minimal (512MB RAM cukup untuk Laravel kecil)
   - Disable fitur yang tidak perlu

3. **Wake Up Aplikasi yang Sleep**
   - Jika aplikasi sleep (credit habis), cukup klik "Wake Up"
   - Tidak perlu top-up credit (tetap gratis)

4. **Gunakan untuk Development/Testing**
   - Railway gratis cocok untuk development
   - Untuk production, pertimbangkan upgrade atau pindah ke hosting berbayar

---

## 📊 **MONITORING & LOGS**

### View Logs:

1. **Klik pada Web Service**
2. **Tab "Deployments"**
3. **Klik pada deployment**
4. **View Logs** → Lihat real-time logs

### Monitor Usage:

1. **Dashboard Railway**
2. **Lihat credit usage**
3. **Lihat resource usage**

---

## ✅ **CHECKLIST SETELAH DEPLOY**

- [ ] Aplikasi sudah live di Railway
- [ ] Database sudah terhubung
- [ ] Migration sudah dijalankan
- [ ] APP_KEY sudah di-generate
- [ ] Storage link sudah dibuat
- [ ] Environment variables sudah di-set
- [ ] Custom domain sudah di-setup (jika perlu)
- [ ] SSL sudah aktif (otomatis dari Railway)
- [ ] Aplikasi bisa diakses dari browser
- [ ] Login berfungsi
- [ ] CRUD berfungsi
- [ ] File upload berfungsi

---

## 🎯 **NEXT STEPS**

Setelah deploy berhasil:

1. **Test semua fitur:**
   - Login sebagai admin
   - Login sebagai santri
   - CRUD data santri
   - Upload foto album
   - Dll

2. **Setup Monitoring:**
   - Monitor error logs
   - Monitor credit usage

3. **Setup Backup:**
   - Railway tidak otomatis backup database
   - Setup manual backup atau gunakan service backup

4. **Optimize:**
   - Setup CDN untuk assets (optional)
   - Optimize images
   - Setup caching

---

## 📚 **DOKUMENTASI TAMBAHAN**

- [Hosting Benar-Benar Gratis](HOSTING_BENAR_BENAR_GRATIS.md)
- [Mengapa Vercel Tidak Cocok](MENGAPA_VERCEL_TIDAK_COCOK.md)
- [Checklist Hosting](CHECKLIST_HOSTING_DAN_DOMAIN.md)

---

## 🆘 **BUTUH BANTUAN?**

- **Railway Docs:** https://docs.railway.app
- **Railway Discord:** https://discord.gg/railway
- **Railway Support:** support@railway.app

---

**Selamat! Aplikasi Anda sudah siap untuk deploy ke Railway! 🚀**

