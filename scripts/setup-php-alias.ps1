# PowerShell script untuk setup alias PHP 8.4.2
# Jalankan dengan: . .\setup-php-alias.ps1

# Set alias untuk php, artisan, dan composer
Set-Alias -Name php -Value "C:\laragon\bin\php\php-8.4.2-nts-Win32-vs17-x64\php.exe" -Scope Global -Force
Set-Alias -Name artisan -Value "C:\laragon\bin\php\php-8.4.2-nts-Win32-vs17-x64\php.exe" -Scope Global -Force

Write-Host "Alias PHP 8.4.2 sudah di-set!" -ForegroundColor Green
Write-Host "Sekarang Anda bisa menggunakan:" -ForegroundColor Yellow
Write-Host "  php artisan serve" -ForegroundColor Cyan
Write-Host "  php artisan migrate" -ForegroundColor Cyan
Write-Host "  php -v" -ForegroundColor Cyan

