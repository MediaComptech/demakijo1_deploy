@echo off
title SDN Demakijo 1 - Local Dev Server
color 0A
echo ===============================================
echo   SDN Demakijo 1 - PHP Development Server
echo ===============================================
echo.

:: Pastikan berada di direktori yang benar
cd /d "%~dp0"

:: Cek apakah PHP tersedia
php -v > nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP tidak ditemukan di PATH!
    echo Pastikan PHP sudah diinstall dan ada di PATH.
    echo.
    echo Download PHP dari: https://windows.php.net/download/
    pause
    exit /b 1
)

:: Tampilkan versi PHP
echo PHP Version:
php -v
echo.

:: Cek apakah .env ada
if not exist ".env" (
    echo [WARNING] File .env tidak ditemukan!
    echo Menyalin dari .env.example...
    if exist ".env.example" (
        copy .env.example .env
        echo .env berhasil dibuat dari .env.example
        echo PENTING: Edit file .env dan sesuaikan konfigurasi database Anda!
    ) else (
        echo [ERROR] .env.example juga tidak ditemukan. Buat file .env secara manual.
    )
    echo.
)

:: Hapus cache Blade sebelum mulai
if exist "storage\cache" (
    echo Membersihkan Blade cache...
    del /q "storage\cache\*.php" 2>nul
    echo Cache dibersihkan.
    echo.
)

echo Server berjalan di: http://localhost:8000
echo Document root    : public\
echo.
echo Tekan Ctrl+C untuk menghentikan server.
echo ===============================================
echo.

:: Jalankan PHP built-in server dengan document root di folder public
php -S localhost:8000 -t public

pause
