@echo off
REM Script Helper untuk Deploy Laravel ke Hosting (Windows)
REM Gunakan script ini untuk persiapan sebelum upload ke hosting

echo ==========================================
echo   Laravel Deploy Helper Script
echo   Managemen Data Santri
echo ==========================================
echo.

REM Cek apakah di folder aplikasi Laravel
if not exist "artisan" (
    echo [ERROR] File artisan tidak ditemukan. Pastikan Anda berada di root folder aplikasi Laravel.
    pause
    exit /b 1
)

echo [INFO] Memulai proses persiapan deploy...
echo.

REM 1. Install/Update Composer Dependencies
echo [INFO] 1. Menginstall dependencies Composer...
call composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo [ERROR] Gagal menginstall Composer dependencies
    pause
    exit /b 1
)
echo [SUCCESS] Composer dependencies berhasil diinstall
echo.

REM 2. Build Assets
echo [INFO] 2. Build assets untuk production...
call npm run build
if %errorlevel% neq 0 (
    echo [WARNING] Gagal build assets. Pastikan npm install sudah dijalankan.
) else (
    echo [SUCCESS] Assets berhasil di-build
)
echo.

REM 3. Optimize Autoloader
echo [INFO] 3. Optimize autoloader...
call composer dump-autoload --optimize
if %errorlevel% neq 0 (
    echo [WARNING] Gagal optimize autoloader
) else (
    echo [SUCCESS] Autoloader berhasil dioptimize
)
echo.

REM 4. Clear Cache
echo [INFO] 4. Membersihkan cache...
call php artisan config:clear
call php artisan cache:clear
call php artisan route:clear
call php artisan view:clear
echo [SUCCESS] Cache berhasil dibersihkan
echo.

echo ==========================================
echo [SUCCESS] Persiapan deploy selesai!
echo ==========================================
echo.
echo [INFO] Langkah selanjutnya:
echo   - Upload semua file ke hosting (kecuali node_modules, .git, .env)
echo   - Buat file .env di server dengan konfigurasi production
echo   - Jalankan: php artisan key:generate
echo   - Jalankan: php artisan migrate --force
echo   - Jalankan: php artisan db:seed
echo   - Jalankan: php artisan storage:link
echo   - Set permission folder storage dan bootstrap/cache ke 775
echo   - Install SSL certificate
echo.

pause

