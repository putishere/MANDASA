# 💾 Cara Backup dan Hapus File Sampah

## 📋 Langkah-Langkah

### Langkah 1: Backup File Sampah

Jalankan script backup untuk memindahkan file-file sampah ke folder backup:

```bash
php backup_file_sampah.php
```

**Script ini akan:**
- ✅ Membuat folder `backup/scripts/` dan `backup/docs/`
- ✅ Menyalin file-file temporary/perbaikan ke folder backup
- ✅ Menyalin dokumentasi perbaikan ke folder backup
- ✅ Membuat log file backup

**File yang akan di-backup:**
- Script perbaikan (`fix_*.php`, `clear_*.php`, dll)
- Dokumentasi perbaikan (`FIX_*.md`, `PERBAIKAN_*.md`, dll)

### Langkah 2: Verifikasi Backup

Setelah backup selesai, verifikasi:

1. **Cek folder backup:**
   ```
   backup/
   ├── scripts/     # File script yang di-backup
   ├── docs/        # Dokumentasi yang di-backup
   └── backup_log_*.txt  # Log backup
   ```

2. **Cek log backup:**
   - Buka file `backup/backup_log_*.txt`
   - Pastikan semua file tercatat di log

3. **Test aplikasi:**
   - Buka aplikasi di browser
   - Test fitur-fitur utama
   - Pastikan tidak ada error

### Langkah 3: Hapus File Sampah (Opsional)

**⚠️ PERINGATAN:** Langkah ini akan menghapus file secara permanen!

Setelah verifikasi aplikasi masih berjalan dengan baik, jalankan:

```bash
php hapus_file_sampah.php
```

**Script ini akan:**
- ⚠️ Meminta konfirmasi (ketik 'YA' untuk melanjutkan)
- ✅ Menghapus file-file yang sudah di-backup
- ✅ Membuat log penghapusan

**Catatan:**
- File backup masih tersimpan di folder `backup/`
- File original akan dihapus dari root folder
- Log penghapusan tersimpan di `backup/delete_log_*.txt`

## 📁 Struktur Folder Backup

Setelah backup, struktur folder akan seperti ini:

```
project/
├── backup/
│   ├── scripts/
│   │   ├── fix_all_issues.php
│   │   ├── fix_role_user.php
│   │   ├── clear_session.php
│   │   └── ...
│   ├── docs/
│   │   ├── FIX_419_ERROR.md
│   │   ├── PERBAIKAN_REDIRECT_LOOP.md
│   │   └── ...
│   ├── backup_log_2025-01-XX_XXXXXX.txt
│   └── delete_log_2025-01-XX_XXXXXX.txt (jika sudah dihapus)
├── fix_*.php (akan dihapus setelah backup)
├── FIX_*.md (akan dihapus setelah backup)
└── ...
```

## 🔄 Alur Lengkap

### 1. Backup File Sampah
```bash
php backup_file_sampah.php
```

**Output:**
- ✅ File-file di-copy ke folder backup
- ✅ Log backup dibuat
- ✅ File original masih ada di root folder

### 2. Verifikasi
- ✅ Cek folder backup
- ✅ Test aplikasi
- ✅ Pastikan tidak ada error

### 3. Hapus File (Opsional)
```bash
php hapus_file_sampah.php
```

**Output:**
- ⚠️ Meminta konfirmasi
- ✅ File original dihapus
- ✅ Log penghapusan dibuat
- ✅ File backup masih tersimpan

## 📝 File yang Dibackup

### Script Temporary/Perbaikan:
- `fix_*.php` - Script perbaikan
- `clear_*.php` - Script clear cache/session
- `create_*.php` - Script create data
- `test_*.php` - Script test
- `view_*.php` - Script view database
- `setup_*.php` - Script setup
- `add_*.php` - Script add data
- `jalankan_*.php` - Script jalankan migration
- `force_logout_all.php`
- `cek_file_sampah.php`

### Dokumentasi Perbaikan:
- `FIX_*.md` - Dokumentasi perbaikan
- `PERBAIKAN_*.md` - Dokumentasi perbaikan
- `SOLUSI_*.md` - Dokumentasi solusi
- `TROUBLESHOOTING_*.md` - Dokumentasi troubleshooting
- `VERIFIKASI_*.md` - Dokumentasi verifikasi
- `LAPORAN_*.md` - Laporan pemeriksaan
- `CARA_PERBAIKI_*.md` - Panduan perbaikan
- `CARA_MEMPERBAIKI_*.md` - Panduan perbaikan
- `RINGKASAN_*.md` - Ringkasan perbaikan
- `STATUS_*.md` - Status aplikasi
- `TAMBAHAN_*.md` - Tambahan
- `PERBEDAAN_*.md` - Perbedaan
- `STUDI_KASUS_*.md` - Studi kasus

**Catatan:** File dokumentasi penting seperti `README.md`, `ALUR_APLIKASI.md`, dll tidak akan di-backup.

## ⚠️ Peringatan

### Sebelum Menghapus:

1. ✅ **Pastikan backup sudah dilakukan**
2. ✅ **Verifikasi aplikasi masih berjalan dengan baik**
3. ✅ **Cek log backup untuk memastikan semua file tercatat**
4. ✅ **Pastikan folder backup tersimpan dengan baik**

### Jangan Hapus:

- ❌ File penting aplikasi (app/, routes/, config/, dll)
- ❌ File dokumentasi utama (README.md, dll)
- ❌ File konfigurasi (.env, composer.json, dll)
- ❌ File migration dan seeder
- ❌ File yang masih digunakan di routes

## 🔍 Cek File Sampah

Sebelum backup, cek file sampah terlebih dahulu:

```bash
php cek_file_sampah.php
```

Script ini akan menampilkan:
- File temporary/perbaikan
- Dokumentasi perbaikan
- Script yang tidak digunakan
- Route perbaikan yang masih ada

## 📊 Log File

### Backup Log
File: `backup/backup_log_YYYY-MM-DD_HHMMSS.txt`

Berisi:
- Daftar file yang di-backup
- Lokasi backup
- Size file
- Total file dan size

### Delete Log
File: `backup/delete_log_YYYY-MM-DD_HHMMSS.txt`

Berisi:
- Daftar file yang dihapus
- Lokasi backup
- Error (jika ada)

## ✅ Checklist

- [ ] Jalankan `php cek_file_sampah.php` untuk melihat file sampah
- [ ] Jalankan `php backup_file_sampah.php` untuk backup
- [ ] Verifikasi folder backup sudah dibuat
- [ ] Cek log backup
- [ ] Test aplikasi untuk memastikan masih berjalan
- [ ] Jika aplikasi masih berjalan dengan baik, jalankan `php hapus_file_sampah.php`
- [ ] Verifikasi file sudah dihapus dari root folder
- [ ] Pastikan file backup masih tersimpan di folder backup/

## 🆘 Troubleshooting

### Jika Backup Gagal:
1. Pastikan folder `backup/` bisa dibuat
2. Cek permission folder
3. Pastikan ada space disk yang cukup

### Jika Hapus Gagal:
1. Pastikan file sudah di-backup
2. Cek permission file
3. Pastikan file tidak sedang digunakan

### Jika Aplikasi Error Setelah Hapus:
1. File backup masih tersimpan di folder `backup/`
2. Copy file kembali dari backup jika diperlukan
3. Test aplikasi lagi

---

**Dibuat oleh:** AI Assistant  
**Tanggal:** $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")

