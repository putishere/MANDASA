# Solusi: HTTP 500 Error - Database Configuration

## Masalah
Error HTTP 500 dengan pesan:
```
Database file at path [managemen_data_santri] does not exist. 
Ensure this is an absolute path to the database.
```

## Penyebab
File `.env` masih menggunakan `DB_CONNECTION=sqlite` padahal database yang digunakan adalah MySQL. Laravel mencoba mencari file SQLite dengan nama "managemen_data_santri" yang tidak ada.

## Solusi yang Sudah Diterapkan

### 1. Update File .env
Script `scripts/fix_database_config.php` sudah mengupdate:
- `DB_CONNECTION=mysql` (dari sqlite)
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=managemen_data_santri`
- `DB_USERNAME=root`
- `DB_PASSWORD=` (kosong)

### 2. Clear Cache
Sudah dilakukan:
- `php artisan config:clear`
- `php artisan cache:clear`
- `php artisan view:clear`
- `php artisan route:clear`

## Verifikasi

### Cek Konfigurasi
```bash
php artisan tinker
```
Kemudian:
```php
config('database.default'); // Harus return 'mysql'
config('database.connections.mysql.database'); // Harus return 'managemen_data_santri'
```

### Test Koneksi
```bash
php scripts/cek_database.php
```

## Jika Masih Error

### 1. Pastikan MySQL Berjalan
- Buka Laragon
- Pastikan lampu MySQL hijau (berjalan)
- Jika tidak, klik "Start All"

### 2. Pastikan Database Ada
```bash
php scripts/cek_database.php
```
Script ini akan menampilkan semua database yang ada.

### 3. Clear Semua Cache Lagi
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
```

### 4. Restart Laragon
1. Klik "Stop All" di Laragon
2. Tunggu 5 detik
3. Klik "Start All"
4. Refresh halaman aplikasi

## Catatan Penting

- Pastikan `DB_CONNECTION=mysql` di file `.env`
- Jangan gunakan `DB_CONNECTION=sqlite` jika database MySQL sudah ada
- Setelah perubahan `.env`, selalu jalankan `php artisan config:clear`

## Status Saat Ini

✅ File `.env` sudah diupdate ke MySQL
✅ Cache sudah di-clear
✅ Database `managemen_data_santri` ada dan berisi data
✅ MySQL berjalan dengan baik

Aplikasi seharusnya sudah berfungsi normal sekarang!

