# Cara Reinstall phpMyAdmin di Laragon

## Masalah
phpMyAdmin tidak bisa diakses karena file `index.php` hilang, menyebabkan HTTP 500 error.

## Solusi: Reinstall phpMyAdmin

### Langkah 1: Download phpMyAdmin
1. Kunjungi: https://www.phpmyadmin.net/downloads/
2. Download versi terbaru (zip file)
   - Pilih: **phpMyAdmin-5.x.x-all-languages.zip**
   - Jangan pilih yang "english-only"

### Langkah 2: Backup (Opsional)
Jika ada konfigurasi penting:
```powershell
# Backup config jika ada
Copy-Item "C:\laragon\bin\phpmyadmin\phpMyAdmin-5.2.3-all-languages\config.inc.php" "C:\laragon\bin\phpmyadmin\config.inc.php.backup"
```

### Langkah 3: Hapus Folder Lama
```powershell
Remove-Item "C:\laragon\bin\phpmyadmin\phpMyAdmin-5.2.3-all-languages" -Recurse -Force
```

### Langkah 4: Extract File Baru
1. Extract file zip yang sudah didownload
2. Copy folder hasil extract ke `C:\laragon\bin\phpmyadmin\`
3. Pastikan struktur folder:
   ```
   C:\laragon\bin\phpmyadmin\
   └── phpMyAdmin-5.x.x-all-languages\
       ├── index.php          ← File ini harus ada!
       ├── config.sample.inc.php
       ├── libraries\
       └── ...
   ```

### Langkah 5: Setup Konfigurasi
1. Copy file config sample:
   ```powershell
   Copy-Item "C:\laragon\bin\phpmyadmin\phpMyAdmin-5.x.x-all-languages\config.sample.inc.php" "C:\laragon\bin\phpmyadmin\phpMyAdmin-5.x.x-all-languages\config.inc.php"
   ```

2. Edit file `config.inc.php`:
   ```php
   $cfg['Servers'][1]['host'] = '127.0.0.1';
   $cfg['Servers'][1]['port'] = '3306';
   $cfg['Servers'][1]['user'] = 'root';
   $cfg['Servers'][1]['password'] = ''; // Kosongkan jika default
   ```

### Langkah 6: Restart Laragon
1. Buka Laragon
2. Klik **Stop All**
3. Tunggu 5 detik
4. Klik **Start All**

### Langkah 7: Test
1. Buka browser
2. Akses: `http://localhost/phpmyadmin/`
3. Login dengan:
   - Username: `root`
   - Password: (kosongkan jika default)

## Alternatif: Gunakan TablePlus (Lebih Mudah!)

Jika reinstall phpMyAdmin terlalu rumit, gunakan **TablePlus**:

1. **Download**: https://tableplus.com/
2. **Install** aplikasi
3. **Buka** TablePlus
4. **Klik** "Create a new connection"
5. **Pilih** MySQL
6. **Isi**:
   - Name: Laragon MySQL
   - Host: `127.0.0.1`
   - Port: `3306`
   - User: `root`
   - Password: (kosongkan)
7. **Klik** "Test" → "Save" → "Connect"

**Keuntungan TablePlus:**
- ✅ Lebih cepat dan stabil
- ✅ UI modern dan mudah digunakan
- ✅ Tidak perlu konfigurasi kompleks
- ✅ Support multiple database types
- ✅ Gratis untuk personal use

## Quick Fix: Gunakan Script PHP

Jika hanya perlu cek data, gunakan script yang sudah ada:

```bash
# Cek database
php scripts/cek_database.php

# Test koneksi MySQL
php scripts/test_mysql_connection.php
```

## Kesimpulan

**phpMyAdmin bisa diperbaiki** dengan reinstall, tapi:
- ⚠️ Prosesnya agak rumit
- ⚠️ Perlu download dan extract manual
- ⚠️ Perlu konfigurasi ulang

**Rekomendasi:**
- ✅ **TablePlus** - Paling mudah dan cepat
- ✅ **MySQL Command Line** - Untuk quick access
- ✅ **Script PHP** - Untuk cek data via code

Pilih yang paling sesuai dengan kebutuhan Anda!

