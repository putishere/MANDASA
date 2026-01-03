# Quick Fix: phpMyAdmin HTTP 500 Error

## Solusi Cepat (Coba ini dulu!)

### Langkah 1: Restart Laragon
1. Buka Laragon
2. Klik **Stop All**
3. Tunggu 5 detik
4. Klik **Start All**
5. Coba akses phpMyAdmin lagi: `http://localhost/phpmyadmin/`

### Langkah 2: Clear Browser Cache
1. Tekan `Ctrl + Shift + Delete`
2. Pilih "Cookies and other site data"
3. Pilih "All time"
4. Klik "Clear data"
5. Refresh halaman phpMyAdmin

### Langkah 3: Cek Folder Temp
Pastikan folder `C:\laragon\tmp` ada dan bisa diakses.

### Langkah 4: Cek PHP Error Log
Buka Laragon → Menu → Logs → PHP Error Log
Lihat apakah ada error spesifik yang muncul.

## Solusi Alternatif

### Gunakan TablePlus (Recommended)
TablePlus adalah alternatif yang lebih modern dan stabil:
1. Download: https://tableplus.com/
2. Install dan buka
3. Klik "Create a new connection"
4. Pilih MySQL
5. Isi:
   - Host: `127.0.0.1`
   - Port: `3306`
   - User: `root`
   - Password: (kosongkan jika default)
6. Klik Connect

### Gunakan MySQL Command Line
1. Buka Command Prompt atau PowerShell
2. Masuk ke folder MySQL:
   ```bash
   cd C:\laragon\bin\mysql\mysql-8.0.30\bin
   ```
3. Login:
   ```bash
   mysql -u root -p
   ```
4. Masukkan password (kosongkan jika default, langsung Enter)

### Gunakan Laravel Tinker
Jika hanya perlu cek data Laravel:
```bash
php artisan tinker
```
Kemudian jalankan query:
```php
DB::table('users')->get();
```

## Penyebab Umum Error 500

1. **Session/Temp Directory tidak bisa diakses**
   - Solusi: Pastikan `C:\laragon\tmp` ada dan writable

2. **PHP Memory Limit terlalu kecil**
   - Solusi: Edit `php.ini`, set `memory_limit = 256M`

3. **Konfigurasi phpMyAdmin salah**
   - Solusi: Cek file `config.inc.php`

4. **Port conflict**
   - Solusi: Cek apakah port 3306 dan 80 tidak digunakan aplikasi lain

5. **MySQL tidak berjalan**
   - Solusi: Pastikan MySQL service berjalan di Laragon

## Status Saat Ini

✅ MySQL berjalan (port 3306 aktif)
✅ phpMyAdmin terinstall (versi 5.2.3)
❓ Perlu cek konfigurasi dan log error

## Langkah Selanjutnya

1. Coba restart Laragon dulu
2. Jika masih error, buka Laragon → Menu → Logs → Apache Error Log
3. Copy error message dan cari solusi spesifik

