# Panduan Menggunakan Terminal dengan PHP 8.4.2

## Masalah
Sistem menggunakan PHP 8.3.16 dari PATH, padahal proyek memerlukan PHP >= 8.4.0.

## Solusi

### Opsi 1: Gunakan File Batch (Paling Mudah) ✅

Gunakan file batch yang sudah dibuat:

```bash
# Untuk menjalankan server
.\scripts\serve.bat

# Atau dari folder scripts
cd scripts
.\serve.bat

# Untuk perintah artisan lainnya
.\artisan.bat migrate
.\artisan.bat cache:clear
.\artisan.bat tinker

# Untuk composer
.\composer.bat update
.\composer.bat install
```

### Opsi 2: Setup Alias PowerShell

Jalankan sekali di PowerShell:

```powershell
. .\scripts\setup-php-alias.ps1
```

Setelah itu, Anda bisa menggunakan:
```bash
php artisan serve
php artisan migrate
php -v
```

**Catatan:** Alias hanya berlaku untuk sesi PowerShell saat ini. Untuk membuat permanen, tambahkan ke `$PROFILE`.

### Opsi 3: Update PATH Permanen (Advanced)

1. Buka **System Properties** → **Environment Variables**
2. Edit **Path** di **User variables**
3. Tambahkan: `C:\laragon\bin\php\php-8.4.2-nts-Win32-vs17-x64`
4. Pastikan path ini berada **di atas** path PHP 8.3.16 lainnya
5. Restart terminal/PowerShell

### Opsi 4: Gunakan Laragon Menu

1. Buka Laragon
2. Klik kanan ikon Laragon di system tray
3. Pilih **Menu** → **Terminal** (akan membuka terminal dengan PATH yang benar)

## File Batch yang Tersedia

- `scripts/serve.bat` - Menjalankan `php artisan serve`
- `artisan.bat` - Menjalankan perintah artisan
- `composer.bat` - Menjalankan composer

## Verifikasi

Setelah setup, verifikasi dengan:

```bash
# Menggunakan file batch
.\scripts\serve.bat --version

# Atau jika sudah setup alias
php -v
```

Harus menampilkan: **PHP 8.4.2**

