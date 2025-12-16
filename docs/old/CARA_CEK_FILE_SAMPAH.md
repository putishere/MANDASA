# 🗑️ Cara Cek File Sampah / Tidak Terpakai

## 📋 Cara Menggunakan

### Opsi 1: Via Script PHP (Paling Mudah)

Jalankan script untuk mengecek file sampah:

```bash
php cek_file_sampah.php
```

Script ini akan menampilkan:
- ✅ File-file temporary/perbaikan di root
- ✅ Dokumentasi perbaikan yang banyak
- ✅ Script yang tidak digunakan di routes
- ✅ Route perbaikan yang masih ada
- ✅ File besar yang mungkin tidak diperlukan

### Opsi 2: Via Browser (Jika ada route)

Jika ada route untuk script ini, bisa diakses via browser:
```
http://127.0.0.1:8000/admin/cek-file-sampah
```

### Opsi 3: Manual Check

Buka folder project dan cek file-file berikut:

**File Temporary/Perbaikan:**
- `fix_*.php` - Script perbaikan
- `clear_*.php` - Script clear cache/session
- `create_*.php` - Script create data
- `test_*.php` - Script test
- `view_*.php` - Script view database
- `setup_*.php` - Script setup
- `add_*.php` - Script add data
- `jalankan_*.php` - Script jalankan migration

**Dokumentasi Perbaikan:**
- `FIX_*.md` - Dokumentasi perbaikan
- `PERBAIKAN_*.md` - Dokumentasi perbaikan
- `SOLUSI_*.md` - Dokumentasi solusi
- `TROUBLESHOOTING_*.md` - Dokumentasi troubleshooting
- `VERIFIKASI_*.md` - Dokumentasi verifikasi
- `LAPORAN_*.md` - Laporan pemeriksaan
- `CARA_PERBAIKI_*.md` - Panduan perbaikan
- `RINGKASAN_*.md` - Ringkasan perbaikan

## 🗂️ File yang Biasanya Tidak Diperlukan

### 1. File Temporary/Perbaikan
File-file ini biasanya dibuat untuk troubleshooting dan bisa dihapus setelah aplikasi stabil:

- `fix_all_issues.php`
- `fix_role_user.php`
- `fix_login_admin.php`
- `fix_login_saput.php`
- `fix_semua_masalah.php`
- `fix_419_error.php`
- `fix_tahun_masuk.php`
- `fix_tahun_masuk_simple.php`
- `clear_session.php`
- `clear_config_cache.php`
- `clear_route_cache.php`
- `create_admin_now.php`
- `create_database.php`
- `test_login_419.php`
- `test_login_santri.php`
- `test_santri_dashboard.php`
- `view_database.php`
- `setup_aplikasi.php`
- `add_tahun_masuk_column.php`
- `jalankan_migration_tahun_masuk.php`
- `force_logout_all.php`
- `cek_file_sampah.php` (script ini sendiri)

### 2. Dokumentasi Perbaikan
File-file dokumentasi perbaikan bisa dipindahkan ke folder `docs/old/` atau dihapus:

- `FIX_*.md`
- `PERBAIKAN_*.md`
- `SOLUSI_*.md`
- `TROUBLESHOOTING_*.md`
- `VERIFIKASI_*.md`
- `LAPORAN_*.md`
- `CARA_PERBAIKI_*.md`
- `CARA_MEMPERBAIKI_*.md`
- `RINGKASAN_*.md`

**Catatan:** Beberapa dokumentasi mungkin masih berguna untuk referensi, jadi pertimbangkan untuk memindahkan ke folder archive daripada menghapus.

### 3. Route Perbaikan
Route-route perbaikan di `routes/web.php` bisa dihapus setelah aplikasi stabil:

- `/fix-admin-password`
- `/create-admin-now`
- `/fix-login-admin`
- `/migrate-tahun-masuk`
- `/buat-admin`
- `/fix-all`
- `/fix-login-saput`
- `/force-logout`
- `/admin/test-data-santri`

## 📝 Rekomendasi Pembersihan

### Langkah 1: Backup File Penting

Sebelum menghapus, backup file-file penting:

```bash
# Buat folder backup
mkdir backup
mkdir backup/scripts
mkdir backup/docs

# Pindahkan file ke backup (jangan langsung hapus)
# Contoh:
# mv fix_*.php backup/scripts/
# mv FIX_*.md backup/docs/
```

### Langkah 2: Organisasi File

**Buat struktur folder:**
```
project/
├── scripts/
│   ├── old/          # Script perbaikan lama
│   └── tools/        # Script tools yang masih digunakan
├── docs/
│   ├── old/          # Dokumentasi perbaikan lama
│   └── archive/      # Dokumentasi archive
└── ...
```

### Langkah 3: Hapus Route Perbaikan

Edit `routes/web.php` dan hapus route-route perbaikan yang tidak diperlukan lagi.

### Langkah 4: Hapus File

Setelah backup dan organisasi, hapus file-file yang tidak diperlukan:

```bash
# Hapus file temporary (setelah backup)
rm fix_*.php
rm clear_*.php
rm test_*.php
# dll...
```

## ⚠️ Peringatan

**JANGAN HAPUS:**
- ✅ File-file penting aplikasi (app/, routes/, config/, dll)
- ✅ File dokumentasi utama (README.md, dll)
- ✅ File konfigurasi (.env, composer.json, dll)
- ✅ File migration dan seeder
- ✅ File yang masih digunakan di routes

**BISA DIHAPUS (setelah backup):**
- ⚠️ Script perbaikan yang sudah tidak digunakan
- ⚠️ Dokumentasi perbaikan yang sudah tidak relevan
- ⚠️ Route perbaikan yang sudah tidak diperlukan

## 📊 Script Cek File Sampah

Script `cek_file_sampah.php` akan menampilkan:

1. **File Temporary/Perbaikan** - File-file script perbaikan
2. **Dokumentasi Perbaikan** - File-file dokumentasi perbaikan
3. **Script Tidak Digunakan** - Script yang tidak digunakan di routes
4. **Route Perbaikan** - Route perbaikan yang masih ada
5. **File Besar** - File besar yang mungkin tidak diperlukan

## ✅ Checklist Pembersihan

- [ ] Backup file-file penting
- [ ] Buat folder backup/archive
- [ ] Pindahkan file ke backup
- [ ] Hapus route perbaikan dari routes/web.php
- [ ] Hapus file temporary setelah verifikasi
- [ ] Organisasi dokumentasi ke folder docs/old/
- [ ] Test aplikasi setelah pembersihan
- [ ] Commit perubahan ke git (jika menggunakan git)

---

**Dibuat oleh:** AI Assistant  
**Tanggal:** $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")

