# 📤 Cara Push Proyek ke GitHub

**Panduan lengkap untuk upload proyek ke GitHub sebelum deploy ke Railway**

---

## 📍 **DI MANA MENJALANKAN PERINTAH?**

Perintah Git dijalankan di **folder proyek Anda**, yaitu:

```
C:\laragon\www\Managemen Data Santri
```

---

## 🛠️ **CARA MENJALANKAN PERINTAH**

### Opsi 1: Menggunakan Terminal/Command Prompt (Recommended)

#### A. Buka Terminal di Folder Proyek

**Cara 1: Dari File Explorer**
1. Buka File Explorer
2. Navigasi ke: `C:\laragon\www\Managemen Data Santri`
3. Klik kanan di folder → **"Open in Terminal"** atau **"Open PowerShell here"**

**Cara 2: Dari Cursor/VS Code**
1. Buka folder proyek di Cursor/VS Code
2. Tekan `Ctrl + `` (backtick) untuk buka terminal
3. Terminal akan otomatis di folder proyek

**Cara 3: Manual**
1. Buka PowerShell atau Command Prompt
2. Ketik:
   ```powershell
   cd "C:\laragon\www\Managemen Data Santri"
   ```

#### B. Jalankan Perintah Git

Setelah terminal terbuka di folder proyek, jalankan:

```bash
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

---

### Opsi 2: Menggunakan GitHub Desktop (Lebih Mudah!)

Jika Git belum terinstall atau lebih suka GUI:

#### 1. Install GitHub Desktop
- Download: https://desktop.github.com
- Install aplikasi

#### 2. Login dengan GitHub
- Buka GitHub Desktop
- Login dengan akun GitHub Anda

#### 3. Add Repository
- Klik **"File"** → **"Add Local Repository"**
- Pilih folder: `C:\laragon\www\Managemen Data Santri`
- Jika belum ada repository Git, GitHub Desktop akan tanya untuk initialize

#### 4. Commit & Push
- Di GitHub Desktop, Anda akan lihat semua file yang berubah
- Tulis commit message: `"Prepare for Railway deployment"`
- Klik **"Commit to main"**
- Klik **"Push origin"** untuk upload ke GitHub

---

## ⚠️ **JIKA GIT BELUM TERINSTALL**

### Install Git untuk Windows:

1. **Download Git**
   - Kunjungi: https://git-scm.com/download/win
   - Download installer

2. **Install Git**
   - Jalankan installer
   - Pilih opsi default (Next, Next, Install)
   - Setelah selesai, restart terminal

3. **Verifikasi Install**
   ```bash
   git --version
   ```
   Harus muncul versi Git (contoh: `git version 2.42.0`)

---

## 📝 **LANGKAH LENGKAP PUSH KE GITHUB**

### Jika Repository Belum Ada di GitHub:

#### 1. Buat Repository di GitHub
1. Login ke https://github.com
2. Klik **"+"** → **"New repository"**
3. Isi nama repository (contoh: `managemen-data-santri`)
4. Pilih **Public** atau **Private**
5. **JANGAN** centang "Initialize with README"
6. Klik **"Create repository"**

#### 2. Hubungkan dengan Proyek Lokal

Di terminal (di folder proyek):

```bash
# 1. Initialize Git (jika belum)
git init

# 2. Add semua file
git add .

# 3. Commit pertama
git commit -m "Initial commit"

# 4. Tambahkan remote GitHub
git remote add origin https://github.com/username/repository-name.git
# Ganti username dan repository-name dengan milik Anda!

# 5. Push ke GitHub
git branch -M main
git push -u origin main
```

---

### Jika Repository Sudah Ada di GitHub:

```bash
# 1. Cek apakah sudah ada remote
git remote -v

# 2. Jika belum ada remote, tambahkan:
git remote add origin https://github.com/username/repository-name.git

# 3. Add file baru
git add .

# 4. Commit
git commit -m "Prepare for Railway deployment"

# 5. Push
git push origin main
```

---

## 🔍 **CARA CEK STATUS GIT**

### Cek apakah Git sudah terinstall:
```bash
git --version
```

### Cek status repository:
```bash
git status
```

### Cek remote GitHub:
```bash
git remote -v
```

### Lihat commit history:
```bash
git log --oneline
```

---

## ⚠️ **MASALAH UMUM**

### Problem 1: "git is not recognized"

**Solusi:**
- Install Git (lihat di atas)
- Restart terminal setelah install

---

### Problem 2: "fatal: not a git repository"

**Solusi:**
```bash
git init
```

---

### Problem 3: "fatal: remote origin already exists"

**Solusi:**
```bash
# Hapus remote lama
git remote remove origin

# Tambahkan remote baru
git remote add origin https://github.com/username/repository-name.git
```

---

### Problem 4: "Permission denied" saat push

**Solusi:**
- Pastikan sudah login ke GitHub
- Gunakan Personal Access Token (bukan password)
- Atau gunakan GitHub Desktop

---

## 🎯 **QUICK START (Copy-Paste)**

Jika Git sudah terinstall dan repository sudah ada:

```bash
# Buka terminal di folder proyek
cd "C:\laragon\www\Managemen Data Santri"

# Add semua file
git add .

# Commit
git commit -m "Prepare for Railway deployment"

# Push ke GitHub
git push origin main
```

---

## 📚 **DOKUMENTASI TAMBAHAN**

- [Panduan Deploy Railway](../deployment/PANDUAN_DEPLOY_RAILWAY.md)
- [Railway Quick Start](../../RAILWAY_QUICK_START.md)

---

**Setelah push ke GitHub, Anda bisa langsung deploy ke Railway! 🚀**

