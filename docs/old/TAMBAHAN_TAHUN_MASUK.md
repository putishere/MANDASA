# ✅ TAMBAHAN FIELD TAHUN MASUK - DATA SANTRI

## 📋 Ringkasan Perubahan

Field **"Tahun Masuk"** telah ditambahkan ke form input data santri.

---

## 🔧 Perubahan yang Dilakukan

### **1. Database Migration**
- ✅ File: `database/migrations/2025_01_20_000000_add_tahun_masuk_to_santri_detail_table.php`
- ✅ Menambahkan kolom `tahun_masuk` (YEAR) ke tabel `santri_detail`

### **2. Model**
- ✅ File: `app/Models/SantriDetail.php`
- ✅ Menambahkan `tahun_masuk` ke `$fillable`

### **3. Controller**
- ✅ File: `app/Http/Controllers/SantriController.php`
- ✅ Validasi `tahun_masuk` di method `store()` dan `update()`
- ✅ Menyimpan `tahun_masuk` saat create dan update

### **4. Views**
- ✅ File: `resources/views/santri/create.blade.php` - Form tambah santri
- ✅ File: `resources/views/santri/edit.blade.php` - Form edit santri
- ✅ File: `resources/views/santri/index.blade.php` - Tabel daftar santri
- ✅ File: `resources/views/santri/show.blade.php` - Detail santri

---

## 🚀 Cara Menggunakan

### **Langkah 1: Jalankan Migration**

**Via Browser (Paling Mudah):**
```
http://127.0.0.1:8000/migrate-tahun-masuk
```

**Via Command Line:**
```powershell
C:\laragon\bin\php\php-8.2.x\php.exe jalankan_migration_tahun_masuk.php
```

**Via Artisan:**
```bash
php artisan migrate
```

### **Langkah 2: Gunakan Form**

1. **Tambah Santri Baru:**
   - Buka: `/santri/create`
   - Field "Tahun Masuk" sudah tersedia
   - Default: Tahun saat ini
   - Range: 2000 - Tahun saat ini + 1

2. **Edit Santri:**
   - Buka: `/santri/{id}/edit`
   - Field "Tahun Masuk" sudah tersedia
   - Bisa diubah sesuai kebutuhan

---

## 📊 Lokasi Field

### **Form Create/Edit:**
- **Posisi:** Setelah "Tanggal Lahir", sebelum "NIS"
- **Tipe:** Input number
- **Default:** Tahun saat ini ({{ date('Y') }})
- **Validasi:** 
  - Required
  - Integer
  - Min: 2000
  - Max: Tahun saat ini + 1

### **Tabel Daftar Santri:**
- **Kolom:** "Tahun Masuk"
- **Posisi:** Setelah "Tanggal Lahir"
- **Visibility:** Hidden di mobile, tampil di desktop (lg)

### **Halaman Detail:**
- **Posisi:** Setelah "Tanggal Lahir"
- **Format:** Tahun (contoh: 2024)

---

## ✅ Validasi

Field "Tahun Masuk" memiliki validasi:
- ✅ **Required** - Wajib diisi
- ✅ **Integer** - Harus angka
- ✅ **Min: 2000** - Minimal tahun 2000
- ✅ **Max: Tahun saat ini + 1** - Maksimal tahun depan

---

## 🔍 Contoh Penggunaan

### **Tambah Santri Baru:**
```
Nama: Ahmad Fauzi
Username: fauzi123
Tanggal Lahir: 2005-08-15
Tahun Masuk: 2024  ← BARU!
NIS: 2024001
...
```

### **Tampilan di Tabel:**
```
| No | Foto | NIS     | Nama        | Username  | Tanggal Lahir | Tahun Masuk | Status |
|----|------|---------|-------------|-----------|---------------|-------------|--------|
| 1  | ...  | 2024001 | Ahmad Fauzi | fauzi123  | 15-08-2005    | 2024        | Aktif  |
```

---

## 📝 Catatan Penting

1. **Data Lama:**
   - Data santri yang sudah ada akan memiliki `tahun_masuk = NULL`
   - Script migration akan mengisi dengan tahun saat ini secara otomatis
   - Bisa diubah manual melalui form edit

2. **Hapus Route Setelah Digunakan:**
   - Setelah migration selesai, hapus route `/migrate-tahun-masuk` dari `routes/web.php`

3. **Backup Database:**
   - Disarankan backup database sebelum menjalankan migration

---

## ✅ Checklist

- [x] Migration file dibuat
- [x] Model diupdate (fillable)
- [x] Controller diupdate (validasi & save)
- [x] Form create diupdate
- [x] Form edit diupdate
- [x] Tabel index diupdate
- [x] Halaman show diupdate
- [x] Script migration dibuat
- [x] Route migration dibuat

---

**Field "Tahun Masuk" sudah siap digunakan!** 🎉

