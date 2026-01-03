# Solusi: Error Tabel 'sessions' Tidak Ditemukan

## Masalah
Error saat mengakses aplikasi:
```
SQLSTATE[42S02]: Base table or view not found: 1146 
Table 'managemen_data_santri.sessions' doesn't exist
```

## Penyebab
Tabel `sessions` belum dibuat di database MySQL. Ini terjadi karena:
- Aplikasi baru saja diubah dari SQLite ke MySQL
- Migration untuk tabel sessions belum dijalankan di database MySQL

## Solusi yang Sudah Diterapkan

### Migration Berhasil Dijalankan
Tabel `sessions` sudah dibuat dengan migration:
```bash
php artisan migrate
```

Hasil:
- ✅ `0001_01_01_000003_create_sessions_table` - DONE
- ✅ `2025_01_20_000000_add_tahun_masuk_to_santri_detail_table` - DONE

### Struktur Tabel Sessions
Tabel `sessions` memiliki struktur:
- `id` (string, primary key)
- `user_id` (foreign key, nullable, indexed)
- `ip_address` (string, nullable)
- `user_agent` (text, nullable)
- `payload` (longText)
- `last_activity` (integer, indexed)

## Verifikasi

### Cek Tabel Sessions
```bash
php scripts/test_mysql_connection.php
```

Atau via MySQL command line:
```bash
cd C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin
mysql -u root
USE managemen_data_santri;
SHOW TABLES LIKE 'sessions';
DESCRIBE sessions;
```

## Status Saat Ini

✅ Tabel `sessions` sudah dibuat
✅ Migration sudah dijalankan
✅ Semua tabel yang diperlukan sudah ada

## Langkah Selanjutnya

1. **Refresh halaman aplikasi** (Ctrl + F5)
2. **Clear browser cache** jika masih error
3. **Coba login** ke aplikasi

Aplikasi seharusnya sudah berfungsi normal sekarang!

## Jika Masih Error

### 1. Clear Semua Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 2. Cek Konfigurasi Session
Pastikan di file `.env`:
```
SESSION_DRIVER=database
```

### 3. Restart Laragon
1. Stop All
2. Tunggu 5 detik
3. Start All
4. Refresh halaman

## Catatan Penting

- Tabel `sessions` diperlukan untuk menyimpan session user
- Session driver harus `database` di file `.env`
- Migration harus dijalankan setiap kali setup database baru

