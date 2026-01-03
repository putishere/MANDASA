# 💻 Cara Buka Terminal untuk Upload ke GitHub

**Panduan lengkap cara membuka terminal di berbagai aplikasi**

---

## 🎯 **TERMINAL YANG MANA?**

Ada beberapa cara buka terminal. Pilih yang paling mudah untuk Anda:

---

## ✅ **CARA 1: Terminal di GitHub Desktop (Paling Mudah!)**

### Langkah:

1. **Di GitHub Desktop yang sudah terbuka:**
   - Klik menu **"Repository"** (di bagian atas)
   - Pilih **"Open in Terminal"** atau **"Open in Command Prompt"**
   - Terminal akan terbuka otomatis di folder proyek!

2. **Atau cara lain:**
   - Klik kanan pada repository di GitHub Desktop
   - Pilih **"Open in Terminal"**

**Terminal akan langsung di folder:** `C:\laragon\www\Managemen Data Santri`

---

## ✅ **CARA 2: Terminal di Cursor/VS Code**

### Langkah:

1. **Buka folder proyek di Cursor**
   - File → Open Folder
   - Pilih: `C:\laragon\www\Managemen Data Santri`

2. **Buka Terminal:**
   - Tekan **`Ctrl + ``** (Ctrl + backtick)
   - Atau klik menu **"Terminal"** → **"New Terminal"**

**Terminal akan langsung di folder proyek!**

---

## ✅ **CARA 3: Terminal di File Explorer**

### Langkah:

1. **Buka File Explorer**
2. **Navigasi ke folder:**
   - `C:\laragon\www\Managemen Data Santri`

3. **Buka Terminal:**
   - Klik kanan di folder (di area kosong)
   - Pilih **"Open in Terminal"** atau **"Open PowerShell here"**
   - Atau ketik `powershell` di address bar, tekan Enter

**Terminal akan langsung di folder tersebut!**

---

## ✅ **CARA 4: PowerShell/Command Prompt Biasa**

### Langkah:

1. **Buka PowerShell atau Command Prompt**
   - Tekan `Windows + R`
   - Ketik `powershell` atau `cmd`
   - Tekan Enter

2. **Pindah ke folder proyek:**
   ```powershell
   cd "C:\laragon\www\Managemen Data Santri"
   ```

**Sekarang terminal sudah di folder proyek!**

---

## 🎯 **REKOMENDASI**

### Untuk Upload ke GitHub:

**Paling Mudah:** **Cara 1 - Terminal di GitHub Desktop**
- ✅ Langsung di folder proyek
- ✅ Tidak perlu navigasi manual
- ✅ Cukup klik menu

**Alternatif:** **Cara 2 - Terminal di Cursor**
- ✅ Jika sudah buka Cursor
- ✅ Tekan `Ctrl + `` saja

---

## 📝 **SETELAH TERMINAL TERBUKA**

Setelah terminal terbuka, jalankan perintah:

```bash
git add .
git commit -m "Initial commit - Managemen Data Santri"
```

**Catatan:** 
- Jika Git belum terinstall, akan muncul error
- Solusinya: Install Git dulu atau gunakan GitHub Desktop untuk commit

---

## 🔍 **CARA CEK TERMINAL SUDAH DI FOLDER BENAR**

Di terminal, ketik:
```powershell
pwd
```

Harus muncul:
```
C:\laragon\www\Managemen Data Santri
```

Atau ketik:
```powershell
dir
```

Harus muncul file seperti:
- `composer.json`
- `package.json`
- `README.md`
- Folder `app/`
- Folder `config/`
- dll

---

## 🆘 **JIKA GIT BELUM TERINSTALL**

Jika muncul error "git is not recognized":

**Solusi 1: Install Git**
- Download: https://git-scm.com/download/win
- Install aplikasi
- Restart terminal

**Solusi 2: Gunakan GitHub Desktop**
- Tidak perlu Git
- Langsung commit & push via GUI
- Lebih mudah!

---

## 📋 **RINGKASAN**

**Terminal yang dimaksud:**
- ✅ Terminal di GitHub Desktop (paling mudah!)
- ✅ Terminal di Cursor (Ctrl + `)
- ✅ Terminal di File Explorer (klik kanan → Open in Terminal)
- ✅ PowerShell biasa (cd ke folder proyek)

**Setelah terminal terbuka:**
- Jalankan: `git add .`
- Jalankan: `git commit -m "Initial commit"`
- Kembali ke GitHub Desktop → Push

---

**Paling mudah: Gunakan Terminal di GitHub Desktop! 💻**

