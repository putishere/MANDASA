# 🚂 Railway Quick Start Guide

**Panduan cepat deploy ke Railway dalam 5 menit!**

---

## ⚡ **LANGKAH CEPAT**

### 1. Buat Akun Railway (1 menit)
- Kunjungi https://railway.app
- Klik "Start a New Project"
- Login dengan GitHub (gratis, tidak perlu kartu kredit)
- ✅ Dapat $5 credit gratis/bulan!

### 2. Deploy dari GitHub (2 menit)
- Klik "New Project" → "Deploy from GitHub repo"
- Pilih repository Anda
- Railway auto-detect Laravel ✅

### 3. Add Database (1 menit)
- Klik "New" → "Database" → "PostgreSQL"
- Railway auto-setup ✅

### 4. Setup Environment Variables (1 menit)
Klik pada Web Service → Variables → Tambahkan:

```env
APP_NAME="Managemen Data Santri"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 5. Generate APP_KEY & Run Migration
Buka Terminal di Railway → Jalankan:

```bash
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

### 6. Selesai! 🎉
- Aplikasi live di URL Railway
- Auto-deploy setiap push ke GitHub
- SSL otomatis (gratis!)

---

## 📝 **CATATAN PENTING**

- ✅ Railway menggunakan **PostgreSQL** (bukan MySQL)
- ✅ Migration sudah kompatibel dengan PostgreSQL
- ✅ Tidak perlu perubahan kode
- ✅ Gratis $5 credit/bulan (cukup untuk testing)

---

## 📚 **DOKUMENTASI LENGKAP**

Lihat `docs/deployment/PANDUAN_DEPLOY_RAILWAY.md` untuk panduan lengkap!

---

**Selamat deploy! 🚀**

