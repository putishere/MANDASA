@echo off
REM Script untuk konversi Markdown ke Word menggunakan Pandoc
REM Pastikan Pandoc sudah terinstall

echo ==========================================
echo   Konversi Markdown ke Word
echo   Skripsi/Tugas Akhir
echo ==========================================
echo.

REM Cek apakah Pandoc terinstall
where pandoc >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] Pandoc tidak ditemukan!
    echo.
    echo Silakan install Pandoc terlebih dahulu:
    echo 1. Download dari: https://pandoc.org/installing.html
    echo 2. Atau install via Chocolatey: choco install pandoc
    echo.
    pause
    exit /b 1
)

echo [INFO] Pandoc ditemukan!
echo.

REM Input file
set /p input_file="Masukkan nama file markdown (contoh: BAB_2_LANDASAN_TEORI.md): "

if not exist "%input_file%" (
    echo [ERROR] File tidak ditemukan: %input_file%
    pause
    exit /b 1
)

REM Output file
set output_file=%input_file:.md=.docx%

echo [INFO] Mengkonversi %input_file% ke %output_file%...
echo.

REM Konversi dengan opsi lengkap
pandoc "%input_file%" -o "%output_file%" ^
    --toc ^
    --number-sections ^
    --highlight-style=tango ^
    --reference-doc=template.docx 2>nul

if %errorlevel% neq 0 (
    echo [WARNING] Template tidak ditemukan, menggunakan default...
    pandoc "%input_file%" -o "%output_file%" ^
        --toc ^
        --number-sections ^
        --highlight-style=tango
)

if %errorlevel% equ 0 (
    echo [SUCCESS] File berhasil dikonversi: %output_file%
    echo.
    echo [INFO] File Word sudah siap untuk diedit!
    echo [INFO] Jangan lupa untuk:
    echo   - Format font menjadi Times New Roman 12pt
    echo   - Set margin sesuai ketentuan kampus
    echo   - Format tabel dan gambar sesuai template
    echo   - Periksa referensi dan daftar pustaka
) else (
    echo [ERROR] Gagal mengkonversi file!
    echo.
    echo [TIPS] Pastikan:
    echo   - File markdown valid
    echo   - Tidak ada karakter khusus yang tidak didukung
    echo   - Pandoc versi terbaru
)

echo.
pause

