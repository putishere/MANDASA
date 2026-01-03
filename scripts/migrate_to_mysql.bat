@echo off
echo ========================================
echo   MIGRASI DATABASE KE MYSQL
echo ========================================
echo.

REM Cek apakah Laragon PHP tersedia
set PHP_PATH=C:\laragon\bin\php\php-8.2.0\php.exe
if not exist "%PHP_PATH%" (
    set PHP_PATH=php
    echo Menggunakan PHP dari PATH...
) else (
    echo Menggunakan PHP dari Laragon...
)

echo.
echo Step 1: Update file .env ke MySQL...
call "%PHP_PATH%" scripts\update_env_to_mysql.php
if errorlevel 1 (
    echo.
    echo ERROR: Gagal update .env
    echo Silakan update manual file .env:
    echo DB_CONNECTION=mysql
    echo DB_HOST=127.0.0.1
    echo DB_PORT=3306
    echo DB_DATABASE=managemen_data_santri
    echo DB_USERNAME=root
    echo DB_PASSWORD=
    pause
    exit /b 1
)

echo.
echo Step 2: Menjalankan migrasi database...
call "%PHP_PATH%" scripts\migrate_to_mysql.php
if errorlevel 1 (
    echo.
    echo ERROR: Migrasi gagal!
    pause
    exit /b 1
)

echo.
echo Step 3: Clear cache...
call "%PHP_PATH%" artisan config:clear
call "%PHP_PATH%" artisan cache:clear

echo.
echo ========================================
echo   MIGRASI SELESAI!
echo ========================================
echo.
echo Silakan test aplikasi di browser.
pause

