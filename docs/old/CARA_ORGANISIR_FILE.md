# 📁 Cara Organisir Susunan File Project

## 🎯 Tujuan

Mengorganisir file-file project agar lebih rapi dan terstruktur dengan memindahkan file-file temporary/perbaikan ke folder yang sesuai.

## 📋 Langkah-Langkah

### Langkah 1: Jalankan Script Organisasi

Jalankan script untuk mengorganisir file:

```bash
php organisir_file.php
```

**Script ini akan:**
- ✅ Membuat struktur folder yang rapi
- ✅ Memindahkan file script temporary ke `scripts/old/`
- ✅ Memindahkan dokumentasi perbaikan ke `docs/old/`
- ✅ Membuat dokumentasi struktur folder

### Langkah 2: Verifikasi Struktur Folder

Setelah script selesai, cek struktur folder:

```
project/
├── scripts/
│   ├── old/          # Script perbaikan/temporary
│   └── tools/        # Script tools yang masih digunakan
├── docs/
│   ├── old/          # Dokumentasi perbaikan
│   ├── archive/      # Dokumentasi archive
│   └── guides/       # Panduan dan tutorial
├── backup/
│   ├── scripts/      # Backup script
│   └── docs/         # Backup dokumentasi
└── ...
```

### Langkah 3: Cek File yang Dipindahkan

File-file yang dipindahkan:
- Script temporary → `scripts/old/`
- Dokumentasi perbaikan → `docs/old/`

## 📂 Struktur Folder Baru

### scripts/
- **old/**: Script perbaikan/temporary yang sudah tidak digunakan
  - `fix_*.php`
  - `clear_*.php`
  - `test_*.php`
  - dll...

- **tools/**: Script tools yang masih digunakan
  - Script yang masih diperlukan untuk maintenance

### docs/
- **old/**: Dokumentasi perbaikan yang sudah tidak relevan
  - `FIX_*.md`
  - `PERBAIKAN_*.md`
  - `SOLUSI_*.md`
  - dll...

- **archive/**: Dokumentasi archive
  - Dokumentasi lama yang di-archive

- **guides/**: Panduan dan tutorial
  - Panduan penggunaan aplikasi

### backup/
- **scripts/**: Backup script
- **docs/**: Backup dokumentasi

## 📄 File yang Tetap di Root

File-file berikut tetap berada di root folder karena penting:

- `README.md` - Dokumentasi utama
- `composer.json` - Composer dependencies
- `package.json` - NPM dependencies
- `.env` - Environment configuration
- `artisan` - Laravel artisan command
- File konfigurasi lainnya
- Dokumentasi penting (ALUR_APLIKASI.md, dll)

## ✅ Manfaat Organisasi

1. **Root folder lebih bersih** - File penting lebih mudah ditemukan
2. **File terorganisir** - File temporary terpisah dari file penting
3. **Mudah maintenance** - File perbaikan terpusat di satu folder
4. **Backup lebih mudah** - File backup terorganisir
5. **Professional** - Struktur project lebih profesional

## 🔄 Setelah Organisasi

### Akses File yang Dipindahkan

File-file yang dipindahkan masih bisa diakses:
- Script: `scripts/old/nama_file.php`
- Dokumentasi: `docs/old/nama_file.md`

### Update Route (Jika Perlu)

Jika ada route yang menggunakan script yang dipindahkan, update path:
```php
// Sebelum
include base_path('fix_all_issues.php');

// Sesudah
include base_path('scripts/old/fix_all_issues.php');
```

### Update Dokumentasi

Jika ada referensi ke file yang dipindahkan, update path:
```markdown
<!-- Sebelum -->
[File Perbaikan](FIX_419_ERROR.md)

<!-- Sesudah -->
[File Perbaikan](docs/old/FIX_419_ERROR.md)
```

## 📝 Dokumentasi Struktur

Setelah organisasi, file `STRUKTUR_FOLDER.md` akan dibuat yang berisi:
- Struktur folder lengkap
- Keterangan setiap folder
- File yang dipindahkan
- Status organisasi

## ⚠️ Catatan

1. **File masih bisa diakses** - File yang dipindahkan masih bisa diakses di folder baru
2. **Tidak menghapus file** - Script hanya memindahkan, tidak menghapus
3. **Backup dulu** - Disarankan backup sebelum organisasi
4. **Update referensi** - Update referensi ke file yang dipindahkan jika ada

## 🆘 Troubleshooting

### Jika File Tidak Bisa Dipindahkan:

1. Cek permission folder
2. Pastikan file tidak sedang digunakan
3. Cek apakah ada error di log

### Jika Struktur Folder Tidak Terbuat:

1. Cek permission root folder
2. Pastikan bisa membuat folder
3. Jalankan script dengan permission yang sesuai

---

**Dibuat oleh:** AI Assistant  
**Tanggal:** $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")

