# Troubleshooting phpMyAdmin HTTP 500 Error di Laragon

## Masalah
Error HTTP 500 ketika mengakses phpMyAdmin melalui Laragon.

## Solusi

### 1. Restart Services Laragon
1. Buka Laragon
2. Klik **Stop All**
3. Tunggu beberapa detik
4. Klik **Start All**
5. Coba akses phpMyAdmin lagi

### 2. Cek Log Error
Buka file log berikut untuk melihat error detail:
- `C:\laragon\logs\apache_error.log`
- `C:\laragon\logs\php_error.log`
- `C:\laragon\bin\phpmyadmin\error.log` (jika ada)

### 3. Cek Konfigurasi PHP
1. Buka `C:\laragon\bin\php\php-8.2.0\php.ini` (atau versi PHP yang digunakan)
2. Pastikan setting berikut:
   ```ini
   memory_limit = 256M
   max_execution_time = 300
   upload_max_filesize = 64M
   post_max_size = 64M
   session.save_path = "C:\laragon\tmp"
   ```
3. Restart Apache setelah perubahan

### 4. Cek Folder Temp/Session
1. Pastikan folder `C:\laragon\tmp` ada dan bisa diakses
2. Jika tidak ada, buat folder tersebut
3. Pastikan permission folder memungkinkan PHP untuk menulis

### 5. Cek Konfigurasi phpMyAdmin
1. Buka `C:\laragon\bin\phpmyadmin\config.inc.php`
2. Pastikan konfigurasi database benar:
   ```php
   $cfg['Servers'][1]['host'] = '127.0.0.1';
   $cfg['Servers'][1]['port'] = '3306';
   $cfg['Servers'][1]['user'] = 'root';
   $cfg['Servers'][1]['password'] = ''; // atau password MySQL Anda
   ```
3. Pastikan `$cfg['TempDir']` mengarah ke folder yang valid

### 6. Clear Cache phpMyAdmin
1. Hapus semua file di folder:
   - `C:\laragon\bin\phpmyadmin\tmp\`
   - `C:\laragon\tmp\`
2. Restart Apache

### 7. Cek MySQL Service
1. Pastikan MySQL/MariaDB berjalan
2. Coba koneksi manual dengan command:
   ```bash
   mysql -u root -p
   ```
3. Jika tidak bisa konek, restart MySQL service

### 8. Cek Port Conflict
1. Pastikan port 3306 (MySQL) dan 80/443 (Apache) tidak digunakan aplikasi lain
2. Gunakan `netstat -ano | findstr :3306` untuk cek port MySQL
3. Gunakan `netstat -ano | findstr :80` untuk cek port Apache

### 9. Update phpMyAdmin
Jika masalah masih terjadi, coba update phpMyAdmin:
1. Download versi terbaru dari https://www.phpmyadmin.net/
2. Extract ke `C:\laragon\bin\phpmyadmin\`
3. Copy file `config.inc.php` dari backup sebelumnya

### 10. Alternatif: Gunakan TablePlus atau MySQL Workbench
Jika phpMyAdmin masih bermasalah, gunakan aplikasi alternatif:
- **TablePlus**: https://tableplus.com/
- **MySQL Workbench**: https://dev.mysql.com/downloads/workbench/
- **HeidiSQL**: https://www.heidisql.com/

## Quick Fix (Coba ini dulu)

1. **Restart Laragon** (Stop All → Start All)
2. **Clear browser cache** dan cookies untuk localhost
3. **Coba akses langsung**: `http://localhost/phpmyadmin/`
4. **Cek apakah MySQL berjalan**: Di Laragon, pastikan lampu MySQL hijau

## Jika Masih Error

1. Buka Laragon → Menu → Logs → Apache Error Log
2. Copy error message yang muncul
3. Cari solusi spesifik berdasarkan error message tersebut

## Catatan Penting

- Error HTTP 500 biasanya disebabkan oleh masalah konfigurasi atau permission
- Pastikan semua service Laragon (Apache, MySQL) berjalan dengan baik
- Jika menggunakan Windows Defender atau antivirus, pastikan tidak memblokir Laragon

