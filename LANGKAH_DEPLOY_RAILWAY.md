# 🚀 Langkah Deploy ke Railway (Step-by-Step)

**Urutan yang benar: GitHub dulu, baru Railway!**

---

## 📋 **URUTAN LANGKAH**

```
1. Upload ke GitHub ✅ (Langkah ini dulu!)
   ↓
2. Deploy ke Railway ✅ (Setelah ada di GitHub)
```

---

## ✅ **LANGKAH 1: UPLOAD KE GITHUB DULU**

### A. Buat Repository di GitHub (Jika Belum Ada)

1. **Login ke GitHub**
   - Kunjungi https://github.com
   - Login dengan akun Anda

2. **Buat Repository Baru**
   - Klik **"+"** di pojok kanan atas
   - Pilih **"New repository"**
   - Isi nama: `managemen-data-santri` (atau nama lain)
   - Pilih **Public** atau **Private**
   - **JANGAN** centang "Initialize with README"
   - Klik **"Create repository"**

3. **Copy URL Repository**
   - Setelah repository dibuat, copy URL-nya
   - Contoh: `https://github.com/username/managemen-data-santri.git`

---

### B. Upload Proyek ke GitHub

#### Opsi 1: Menggunakan Terminal (Jika Git Sudah Terinstall)

**Buka terminal di folder proyek:**
- Di Cursor: Tekan `Ctrl + `` (backtick)
- Atau buka PowerShell di folder: `C:\laragon\www\Managemen Data Santri`

**Jalankan perintah:**

```bash
# 1. Initialize Git (jika belum)
git init

# 2. Add semua file
git add .

# 3. Commit pertama
git commit -m "Initial commit - Prepare for Railway deployment"

# 4. Tambahkan remote GitHub (ganti URL dengan milik Anda!)
git remote add origin https://github.com/username/managemen-data-santri.git

# 5. Push ke GitHub
git branch -M main
git push -u origin main
```

**Catatan:** Ganti `username` dan `managemen-data-santri` dengan milik Anda!

---

#### Opsi 2: Menggunakan GitHub Desktop (Lebih Mudah!)

1. **Download GitHub Desktop**
   - https://desktop.github.com
   - Install aplikasi

2. **Login dengan GitHub**
   - Buka GitHub Desktop
   - Login dengan akun GitHub

3. **Add Repository**
   - Klik **"File"** → **"Add Local Repository"**
   - Pilih folder: `C:\laragon\www\Managemen Data Santri`
   - Jika belum ada repository Git, klik **"Create a Repository"**

4. **Publish ke GitHub**
   - Klik **"Publish repository"**
   - Pilih nama repository
   - Pilih **Public** atau **Private**
   - Klik **"Publish Repository"**

5. **Commit & Push**
   - Tulis commit message: `"Initial commit - Prepare for Railway deployment"`
   - Klik **"Commit to main"**
   - Klik **"Push origin"**

---

### C. Verifikasi Upload Berhasil

1. **Refresh halaman GitHub**
   - Kunjungi repository Anda di GitHub
   - Pastikan semua file sudah ada:
     - ✅ `composer.json`
     - ✅ `package.json`
     - ✅ `app/` folder
     - ✅ `config/` folder
     - ✅ `database/` folder
     - ✅ `public/` folder
     - ✅ `routes/` folder
     - ✅ dll

2. **Cek File Penting**
   - Pastikan file `.env` **TIDAK** ada di GitHub (sudah di .gitignore)
   - Pastikan file `vendor/` **TIDAK** ada (sudah di .gitignore)
   - Pastikan file `node_modules/` **TIDAK** ada (sudah di .gitignore)

---

## ✅ **LANGKAH 2: DEPLOY KE RAILWAY**

**Setelah proyek sudah di GitHub, baru deploy ke Railway:**

### 1. Buat Akun Railway (Gratis!)

- Kunjungi https://railway.app
- Klik **"Start a New Project"**
- Login dengan **GitHub** (sama dengan akun GitHub Anda)
- ✅ Dapat **$5 credit gratis/bulan**!

---

### 2. Deploy dari GitHub

1. **Klik "New Project"**
2. **Pilih "Deploy from GitHub repo"**
3. **Pilih repository Anda** (yang baru saja di-upload)
4. **Railway akan otomatis:**
   - Detect Laravel ✅
   - Setup build command ✅
   - Setup start command ✅

---

### 3. Add PostgreSQL Database

1. **Klik "New"** di project
2. **Pilih "Database"**
3. **Pilih "PostgreSQL"**
4. Railway akan otomatis setup database ✅

---

### 4. Setup Environment Variables

Klik pada **Web Service** → **Variables** → Tambahkan:

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

---

### 5. Generate APP_KEY & Run Migration

Buka **Terminal** di Railway → Jalankan:

```bash
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

---

### 6. Selesai! 🎉

- Aplikasi live di URL Railway
- Auto-deploy setiap push ke GitHub
- SSL otomatis (gratis!)

---

## 📝 **RINGKASAN**

### ✅ Yang Harus Dilakukan:

1. **Upload ke GitHub dulu** ← **LANGKAH INI SEKARANG!**
   - Buat repository di GitHub
   - Upload semua file proyek
   - Pastikan semua file sudah ada di GitHub

2. **Deploy ke Railway** ← **Setelah ada di GitHub**
   - Login Railway dengan GitHub
   - Pilih repository dari GitHub
   - Setup database & environment
   - Deploy!

---

## 🆘 **BUTUH BANTUAN?**

### Jika Git Belum Terinstall:
- Lihat: `docs/guides/CARA_PUSH_KE_GITHUB.md`
- Atau gunakan GitHub Desktop (lebih mudah!)

### Jika Ada Masalah:
- Lihat: `docs/deployment/PANDUAN_DEPLOY_RAILWAY.md`
- Atau lihat: `RAILWAY_QUICK_START.md`

---

## 🎯 **QUICK CHECKLIST**

- [ ] Repository sudah dibuat di GitHub
- [ ] Semua file sudah di-upload ke GitHub
- [ ] File `.env` TIDAK ada di GitHub (aman)
- [ ] File `vendor/` TIDAK ada di GitHub (aman)
- [ ] Siap untuk deploy ke Railway!

---

**Setelah upload ke GitHub, langsung bisa deploy ke Railway! 🚀**

