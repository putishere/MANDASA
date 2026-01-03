# 📤 Cara Upload via Web GitHub (Tidak Disarankan untuk Proyek Besar)

**Catatan:** Upload via web GitHub **TIDAK PRAKTIS** untuk proyek besar seperti ini karena harus upload file satu per satu.

**Lebih baik gunakan:**
- ✅ **GitHub Desktop** (paling mudah - 5 menit)
- ✅ **Command Line** (jika Git sudah terinstall)

---

## ⚠️ **MENGAPA TIDAK DISARANKAN?**

Upload via web GitHub:
- ❌ Harus upload file satu per satu atau dalam batch kecil
- ❌ Tidak bisa upload folder sekaligus
- ❌ Sangat lama untuk proyek dengan banyak file
- ❌ Tidak praktis untuk proyek Laravel

**Proyek ini punya ratusan file!** Akan sangat lama jika upload satu per satu.

---

## 🚀 **CARA LEBIH CEPAT: GitHub Desktop**

### Langkah Super Cepat:

1. **Download GitHub Desktop**
   - https://desktop.github.com
   - Install aplikasi

2. **Login dengan GitHub**
   - Buka GitHub Desktop
   - Login dengan akun GitHub Anda

3. **Add Repository**
   - Klik **"File"** → **"Add Local Repository"**
   - Pilih folder: `C:\laragon\www\Managemen Data Santri`
   - Klik **"Add Repository"**

4. **Publish ke GitHub**
   - Klik **"Publish repository"** (di bagian bawah)
   - Pilih repository: `putishere/MANDASA` (yang sudah Anda buat)
   - Atau buat repository baru
   - Klik **"Publish Repository"**

5. **Commit & Push**
   - Tulis commit message: `Initial commit - Prepare for Railway deployment`
   - Klik **"Commit to main"**
   - Klik **"Push origin"**

**Selesai dalam 5 menit!** ✅

---

## 🔧 **ALTERNATIF: Command Line**

Jika Git sudah terinstall:

```bash
# Buka terminal di folder proyek (Ctrl + ` di Cursor)
cd "C:\laragon\www\Managemen Data Santri"

# Add semua file
git add .

# Commit
git commit -m "Initial commit - Prepare for Railway deployment"

# Connect ke repository GitHub yang sudah Anda buat
git remote add origin https://github.com/putishere/MANDASA.git

# Push ke GitHub
git branch -M main
git push -u origin main
```

**Selesai dalam 2 menit!** ✅

---

## 📤 **JIKA TETAP MAU UPLOAD VIA WEB**

Jika Anda tetap ingin menggunakan web interface (tidak disarankan):

### Cara Upload File via Web:

1. **Di halaman upload yang Anda buka:**
   - Klik **"Atau pilih file Anda"** (Or choose your files)
   - Pilih file yang ingin di-upload
   - Bisa pilih beberapa file sekaligus (Ctrl + Click)

2. **Tunggu file ter-upload**
   - File akan muncul di daftar

3. **Isi commit message:**
   - Di bagian bawah, isi: `Initial commit - Prepare for Railway deployment`

4. **Klik "Commit changes"**

**Masalah:** Harus upload file satu per satu atau dalam batch kecil. Sangat lama!

---

## 🎯 **REKOMENDASI**

**JANGAN gunakan upload via web untuk proyek ini!**

**Gunakan GitHub Desktop:**
- ✅ Upload semua file sekaligus
- ✅ Hanya 5 menit
- ✅ Mudah dan cepat

**Atau Command Line:**
- ✅ Upload semua file sekaligus
- ✅ Hanya 2 menit
- ✅ Lebih cepat

---

## 📝 **SETELAH UPLOAD**

Setelah semua file sudah di GitHub:
- ✅ Langsung bisa deploy ke Railway!
- ✅ Lihat: `LANGKAH_DEPLOY_RAILWAY.md`

---

**Gunakan GitHub Desktop untuk hasil terbaik! 🚀**

