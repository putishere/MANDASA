# Troubleshooting MySQL di Laragon

## Masalah Umum MySQL Tidak Bisa di Laragon

### 1. MySQL Service Tidak Berjalan

**Gejala:**
- Lampu MySQL di Laragon merah/abu-abu
- Error "Can't connect to MySQL server"
- Port 3306 tidak listening

**Solusi:**
1. Buka Laragon
2. Klik **Stop All**
3. Tunggu 5 detik
4. Klik **Start All**
5. Pastikan lampu MySQL hijau

### 2. Port 3306 Sudah Digunakan

**Cek port:**
```powershell
netstat -ano | findstr :3306
```

**Solusi:**
- Jika ada proses lain menggunakan port 3306, hentikan proses tersebut
- Atau ubah port MySQL di Laragon:
  1. Laragon → Menu → Preferences → MySQL
  2. Ubah port ke 3307 atau port lain
  3. Update `.env` dengan port baru

### 3. MySQL Service Tidak Bisa Start

**Cek error log:**
- `C:\laragon\logs\mysql_error.log`
- `C:\laragon\bin\mysql\mysql-8.x.x\data\*.err`

**Solusi umum:**
1. **Hapus file lock:**
   ```powershell
   # Hapus file .pid jika ada
   Remove-Item "C:\laragon\bin\mysql\mysql-8.x.x\data\*.pid" -Force
   ```

2. **Repair database:**
   ```bash
   # Masuk ke folder MySQL
   cd C:\laragon\bin\mysql\mysql-8.x.x\bin
   
   # Repair
   mysqlcheck -u root -p --all-databases --repair
   ```

3. **Reinstall MySQL:**
   - Laragon → Menu → MySQL → Remove
   - Laragon → Menu → MySQL → Install

### 4. Password MySQL Tidak Sesuai

**Cek password:**
- Default Laragon biasanya kosong
- Jika sudah diubah, pastikan sesuai di `.env`

**Reset password:**
1. Stop MySQL di Laragon
2. Buka Command Prompt sebagai Administrator
3. Masuk ke folder MySQL:
   ```bash
   cd C:\laragon\bin\mysql\mysql-8.x.x\bin
   ```
4. Start MySQL dengan skip-grant-tables:
   ```bash
   mysqld --skip-grant-tables --console
   ```
5. Buka terminal baru, login:
   ```bash
   mysql -u root
   ```
6. Reset password:
   ```sql
   USE mysql;
   UPDATE user SET authentication_string='' WHERE User='root';
   FLUSH PRIVILEGES;
   EXIT;
   ```
7. Stop MySQL dan start normal lagi

### 5. Data Directory Corrupt

**Gejala:**
- MySQL start tapi langsung crash
- Error di log tentang corrupted tables

**Solusi:**
1. Backup data penting dulu
2. Stop MySQL
3. Hapus folder data:
   ```powershell
   Remove-Item "C:\laragon\bin\mysql\mysql-8.x.x\data" -Recurse -Force
   ```
4. Reinitialize MySQL:
   ```bash
   cd C:\laragon\bin\mysql\mysql-8.x.x\bin
   mysqld --initialize-insecure --console
   ```
5. Start MySQL lagi

### 6. Windows Firewall/Antivirus Block

**Solusi:**
1. Buka Windows Defender Firewall
2. Allow MySQL melalui firewall
3. Atau disable firewall sementara untuk test
4. Cek antivirus tidak memblokir Laragon

### 7. Permission Issues

**Solusi:**
1. Pastikan Laragon di-run sebagai Administrator
2. Cek permission folder MySQL:
   ```powershell
   icacls "C:\laragon\bin\mysql" /grant Everyone:F /T
   ```

## Quick Fix Checklist

1. ✅ **Restart Laragon** (Stop All → Start All)
2. ✅ **Cek port 3306** tidak digunakan aplikasi lain
3. ✅ **Cek log error** MySQL
4. ✅ **Run sebagai Administrator**
5. ✅ **Cek Windows Firewall**
6. ✅ **Cek antivirus** tidak memblokir

## Test Koneksi MySQL

### Via Command Line
```bash
cd C:\laragon\bin\mysql\mysql-8.x.x\bin
mysql -u root -p
```
(Tekan Enter jika password kosong)

### Via Script PHP
```bash
php scripts/cek_database.php
```

### Via Laravel Tinker
```bash
php artisan tinker
```
Kemudian:
```php
DB::connection()->getPdo();
```

## Alternatif: Gunakan XAMPP atau WAMP

Jika Laragon MySQL masih bermasalah:
1. Install XAMPP: https://www.apachefriends.org/
2. Atau WAMP: https://www.wampserver.com/
3. Update `.env` dengan konfigurasi baru

## Bantuan Lebih Lanjut

Jika masih bermasalah:
1. Cek log error: `C:\laragon\logs\mysql_error.log`
2. Screenshot error message
3. Cek versi MySQL yang digunakan
4. Cek Windows version dan compatibility

