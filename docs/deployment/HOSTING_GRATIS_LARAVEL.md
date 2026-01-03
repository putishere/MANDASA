# 🆓 Hosting Gratis untuk Aplikasi Laravel

**Tanggal:** 2025-01-02  
**Aplikasi:** Managemen Data Santri - PP HS AL-FAKKAR

---

## ⚠️ **CATATAN PENTING**

**"Gratis" biasanya memiliki batasan:**
- ⚠️ Resource terbatas (CPU, RAM, Storage)
- ⚠️ Traffic terbatas
- ⚠️ Tidak ada support premium
- ⚠️ Bisa ada iklan atau branding
- ⚠️ Bisa dihentikan kapan saja

**Untuk aplikasi production, pertimbangkan hosting berbayar mulai dari Rp 20.000/bulan.**

---

## 🆓 **PLATFORM HOSTING GRATIS YANG SUPPORT LARAVEL**

### 1. 🚂 **Railway** (Recommended untuk Gratis)

**Website:** https://railway.app

**Fitur Gratis:**
- ✅ **$5 credit gratis** setiap bulan (cukup untuk aplikasi kecil)
- ✅ Support PHP/Laravel
- ✅ PostgreSQL database gratis
- ✅ Auto-deployment dari Git
- ✅ SSL otomatis
- ✅ Custom domain support

**Batasan:**
- ⚠️ Credit habis = aplikasi sleep (bisa di-wake up)
- ⚠️ Database terbatas (500MB untuk gratis)
- ⚠️ Storage terbatas

**Cocok untuk:**
- ✅ Development/Testing
- ✅ Aplikasi kecil dengan traffic rendah
- ✅ Portfolio/Showcase

**Setup:**
```bash
# 1. Buat akun Railway
# 2. Connect GitHub repository
# 3. Railway akan auto-detect Laravel
# 4. Setup environment variables
# 5. Deploy!
```

**Harga:** Gratis (dengan $5 credit/bulan)

---

### 2. 🎨 **Render** (Bagus untuk Gratis)

**Website:** https://render.com

**Fitur Gratis:**
- ✅ **Web Service gratis** (dengan batasan)
- ✅ Support PHP/Laravel
- ✅ PostgreSQL database gratis
- ✅ Auto-deployment dari Git
- ✅ SSL otomatis
- ✅ Custom domain support

**Batasan:**
- ⚠️ **Aplikasi sleep setelah 15 menit tidak aktif**
- ⚠️ Spin-up time ~30 detik setelah sleep
- ⚠️ Database terbatas (1GB untuk gratis)
- ⚠️ 750 jam/bulan (cukup untuk 24/7 jika tidak sleep)

**Cocok untuk:**
- ✅ Development/Testing
- ✅ Aplikasi dengan traffic rendah
- ✅ Aplikasi yang tidak perlu 24/7 aktif

**Setup:**
```bash
# 1. Buat akun Render
# 2. New Web Service → Connect GitHub
# 3. Pilih PHP environment
# 4. Setup build command: composer install
# 5. Setup start command: php artisan serve
# 6. Setup environment variables
# 7. Deploy!
```

**Harga:** Gratis (dengan batasan sleep)

---

### 3. 🪁 **Fly.io** (Bagus untuk Gratis)

**Website:** https://fly.io

**Fitur Gratis:**
- ✅ **3 shared-cpu VMs gratis**
- ✅ Support PHP/Laravel
- ✅ PostgreSQL database (dengan credit)
- ✅ Global edge network
- ✅ Auto-deployment dari Git
- ✅ SSL otomatis

**Batasan:**
- ⚠️ **$5 credit gratis** (habis = perlu top-up)
- ⚠️ Resource terbatas
- ⚠️ Setup lebih kompleks (perlu Dockerfile)

**Cocok untuk:**
- ✅ Developer yang familiar dengan Docker
- ✅ Aplikasi yang perlu global distribution
- ✅ Development/Testing

**Setup:**
```bash
# 1. Install flyctl CLI
# 2. fly launch
# 3. Setup Dockerfile untuk Laravel
# 4. Deploy!
```

**Harga:** Gratis (dengan $5 credit)

---

### 4. 🐳 **Koyeb** (Alternatif)

**Website:** https://www.koyeb.com

**Fitur Gratis:**
- ✅ **2 services gratis**
- ✅ Support PHP/Laravel
- ✅ PostgreSQL database gratis
- ✅ Auto-deployment dari Git
- ✅ SSL otomatis
- ✅ Global edge network

**Batasan:**
- ⚠️ Resource terbatas
- ⚠️ Traffic terbatas
- ⚠️ Database terbatas

**Cocok untuk:**
- ✅ Development/Testing
- ✅ Aplikasi kecil

**Harga:** Gratis (dengan batasan)

---

### 5. 🇮🇩 **Hosting Gratis Indonesia** (Tidak Recommended)

**Platform:**
- InfinityFree
- 000webhost
- Freehostia

**Masalah:**
- ❌ **Sering tidak reliable** (downtime tinggi)
- ❌ **Banyak iklan** atau branding
- ❌ **Resource sangat terbatas**
- ❌ **Tidak ada support**
- ❌ **Bisa dihapus kapan saja**
- ❌ **Tidak cocok untuk production**

**TIDAK DISARANKAN untuk aplikasi production!**

---

## 📊 **PERBANDINGAN PLATFORM GRATIS**

| Platform | Credit/Resource | Database | Sleep Mode | Setup | Rating |
|----------|----------------|----------|------------|-------|--------|
| **Railway** | $5/bulan | PostgreSQL | Tidak | Mudah | ⭐⭐⭐⭐⭐ |
| **Render** | 750 jam/bulan | PostgreSQL | Ya (15 menit) | Mudah | ⭐⭐⭐⭐ |
| **Fly.io** | $5 credit | PostgreSQL | Tidak | Sulit | ⭐⭐⭐ |
| **Koyeb** | 2 services | PostgreSQL | Tidak | Mudah | ⭐⭐⭐ |

---

## 🎯 **REKOMENDASI UNTUK APLIKASI INI**

### Untuk Development/Testing:

**Pilihan 1: Railway** ⭐ (Paling Recommended)
- ✅ Paling mudah setup
- ✅ Tidak ada sleep mode
- ✅ $5 credit cukup untuk testing
- ✅ Auto-deployment mudah

**Pilihan 2: Render**
- ✅ Mudah setup
- ⚠️ Ada sleep mode (tapi tidak masalah untuk testing)
- ✅ Gratis 24/7 jika ada traffic

### Untuk Production:

**JANGAN gunakan hosting gratis!**

**Gunakan hosting berbayar:**
- **Shared Hosting:** Rp 20.000 - Rp 50.000/bulan (sangat murah!)
- **VPS:** Mulai dari $5/bulan (~Rp 75.000)

**Mengapa?**
- ✅ Reliable (uptime tinggi)
- ✅ Tidak ada sleep mode
- ✅ Support lebih baik
- ✅ Resource lebih besar
- ✅ Cocok untuk production

---

## 📝 **CARA SETUP DI RAILWAY (Recommended)**

### 1. Persiapan Repository

```bash
# Pastikan ada file .env.example
# Pastikan ada composer.json
# Commit semua perubahan
git add .
git commit -m "Prepare for Railway deployment"
git push
```

### 2. Setup di Railway

1. **Buat Akun Railway**
   - Kunjungi https://railway.app
   - Sign up dengan GitHub

2. **Create New Project**
   - Klik "New Project"
   - Pilih "Deploy from GitHub repo"
   - Pilih repository Anda

3. **Setup Environment**
   - Railway akan auto-detect Laravel
   - Tambahkan environment variables:
     ```
     APP_NAME="Managemen Data Santri"
     APP_ENV=production
     APP_KEY=                    # Generate nanti
     APP_DEBUG=false
     APP_URL=https://your-app.railway.app
     
     DB_CONNECTION=pgsql        # Railway pakai PostgreSQL
     DB_HOST=                   # Auto dari Railway
     DB_PORT=                   # Auto dari Railway
     DB_DATABASE=               # Auto dari Railway
     DB_USERNAME=               # Auto dari Railway
     DB_PASSWORD=               # Auto dari Railway
     ```

4. **Add PostgreSQL Database**
   - Klik "New" → "Database" → "PostgreSQL"
   - Railway akan auto-setup connection string

5. **Generate APP_KEY**
   - Buka terminal di Railway
   - Run: `php artisan key:generate`

6. **Run Migration**
   - Run: `php artisan migrate --force`

7. **Setup Storage Link**
   - Run: `php artisan storage:link`

8. **Deploy!**
   - Railway akan auto-deploy setiap push ke GitHub

### 3. Setup Custom Domain (Optional)

1. Klik pada service
2. Settings → Domains
3. Add custom domain
4. Setup DNS sesuai instruksi Railway

---

## 📝 **CARA SETUP DI RENDER**

### 1. Persiapan Repository

Sama seperti Railway

### 2. Setup di Render

1. **Buat Akun Render**
   - Kunjungi https://render.com
   - Sign up dengan GitHub

2. **New Web Service**
   - Klik "New" → "Web Service"
   - Connect GitHub repository

3. **Configure Service**
   ```
   Name: managemen-data-santri
   Environment: PHP
   Build Command: composer install --no-dev --optimize-autoloader
   Start Command: php -S 0.0.0.0:$PORT -t public
   ```

4. **Add PostgreSQL Database**
   - Klik "New" → "PostgreSQL"
   - Render akan auto-setup

5. **Setup Environment Variables**
   - Sama seperti Railway
   - Pastikan set `PORT` variable (auto dari Render)

6. **Deploy!**
   - Render akan auto-deploy

**Catatan:** Aplikasi akan sleep setelah 15 menit tidak aktif. Spin-up time ~30 detik.

---

## ⚠️ **PERUBAHAN YANG DIPERLUKAN UNTUK POSTGRESQL**

Railway dan Render menggunakan **PostgreSQL**, bukan MySQL. Perlu perubahan kecil:

### 1. Update composer.json (jika belum ada)

```json
"require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "doctrine/dbal": "^3.0"  // Untuk PostgreSQL
}
```

### 2. Update .env

```env
DB_CONNECTION=pgsql
DB_HOST=your-host
DB_PORT=5432
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

### 3. Update Migration (jika ada syntax MySQL spesifik)

Laravel biasanya kompatibel dengan PostgreSQL, tapi cek migration yang menggunakan:
- `AUTO_INCREMENT` → `SERIAL` atau `BIGSERIAL`
- `TEXT` → tetap `TEXT` (OK)
- `VARCHAR` → tetap `VARCHAR` (OK)

---

## 💰 **BIAYA SETELAH GRATIS**

### Railway:
- Setelah $5 credit habis: **$5/bulan** untuk continue
- Atau upgrade ke plan berbayar

### Render:
- Setelah 750 jam habis: **$7/bulan** untuk continue
- Atau upgrade ke plan berbayar

### Fly.io:
- Setelah $5 credit habis: **Top-up minimum $5**
- Atau upgrade ke plan berbayar

---

## 🎯 **KESIMPULAN**

### Untuk Development/Testing:
✅ **Railway** - Paling mudah dan reliable  
✅ **Render** - Bagus, tapi ada sleep mode

### Untuk Production:
❌ **JANGAN gunakan hosting gratis!**

**Gunakan hosting berbayar:**
- Shared Hosting: **Rp 20.000 - Rp 50.000/bulan** (sangat murah!)
- VPS: Mulai dari **$5/bulan** (~Rp 75.000)

**Mengapa?**
- ✅ Reliable untuk production
- ✅ Tidak ada sleep mode
- ✅ Support lebih baik
- ✅ Resource lebih besar

---

## 📚 **DOKUMENTASI TAMBAHAN**

- [Analisis Vercel](VERCEL_DEPLOYMENT_ANALYSIS.md)
- [Mengapa Vercel Tidak Cocok](MENGAPA_VERCEL_TIDAK_COCOK.md)
- [Checklist Hosting](CHECKLIST_HOSTING_DAN_DOMAIN.md)

---

**Catatan:** Hosting gratis bagus untuk testing, tapi untuk production gunakan hosting berbayar yang reliable!

