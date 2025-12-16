# Cara Memperbaiki Error "no such column: tahun_masuk"

## Masalah
Error yang muncul:
```
SQLSTATE[HY000]: General error: 1 no such column: tahun_masuk
```

Error ini terjadi karena kolom `tahun_masuk` belum ada di tabel `santri_detail` di database.

## Solusi

### Cara 1: Menggunakan Script Perbaikan (Paling Mudah)

1. **Buka Terminal/PowerShell di folder project**
   - Klik kanan di folder project → "Open in Terminal" atau "Open PowerShell here"

2. **Jalankan script perbaikan**
   
   **Jika menggunakan Laragon:**
   ```bash
   C:\laragon\bin\php\php-8.2.0\php.exe fix_tahun_masuk.php
   ```
   
   **Atau jika PHP sudah di PATH:**
   ```bash
   php fix_tahun_masuk.php
   ```

3. **Tunggu sampai selesai**
   - Script akan menambahkan kolom `tahun_masuk` ke tabel `santri_detail`
   - Jika berhasil, akan muncul pesan "SELESAI"

4. **Refresh halaman browser**
   - Buka kembali halaman edit santri
   - Error sudah teratasi

### Cara 2: Menggunakan Artisan Migrate

1. **Buka Terminal di folder project**

2. **Jalankan migration**
   
   **Jika menggunakan Laragon:**
   ```bash
   C:\laragon\bin\php\php-8.2.0\php.exe artisan migrate
   ```
   
   **Atau jika PHP sudah di PATH:**
   ```bash
   php artisan migrate
   ```

3. **Refresh halaman browser**

### Cara 3: Manual dengan SQLite Browser (Alternatif)

Jika kedua cara di atas tidak berhasil:

1. **Buka database dengan SQLite Browser**
   - File database: `database/database.sqlite`

2. **Jalankan SQL berikut:**
   ```sql
   ALTER TABLE santri_detail ADD COLUMN tahun_masuk INTEGER;
   ```

3. **Refresh halaman browser**

## Verifikasi

Setelah menjalankan salah satu cara di atas, verifikasi dengan:

1. Buka halaman edit santri
2. Pastikan field "Tahun Masuk" muncul dan bisa diisi
3. Coba simpan data
4. Tidak ada error lagi

## Catatan

- Script `fix_tahun_masuk.php` sudah dibuat dan siap digunakan
- Migration sudah diperbaiki untuk kompatibel dengan SQLite
- Kolom `tahun_masuk` menggunakan tipe INTEGER untuk SQLite (bukan YEAR)

## Jika Masih Ada Masalah

1. Pastikan database file (`database/database.sqlite`) bisa diakses
2. Pastikan folder `database` memiliki permission write
3. Cek log error di `storage/logs/laravel.log`

