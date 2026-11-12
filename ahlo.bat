@echo off
title Ahmad Handoko Law Office - AHLO Server
color 0B

echo.
echo ================================================================
echo   AHMAD HANDOKO LAW OFFICE - SISTEM INFORMASI HUKUM
echo   Versi: 1.0  ^|  Laravel 12  ^|  PHP 8.2  ^|  MySQL XAMPP
echo ================================================================
echo.

echo [1] Memeriksa MySQL XAMPP...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I "mysqld.exe" >NUL
if errorlevel 1 (
    echo [*] Memulai MySQL...
    start "" /B "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
    timeout /T 3 /NOBREAK >NUL
) else (
    echo [OK] MySQL sudah berjalan.
)

echo [2] Memeriksa Apache XAMPP...
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I "httpd.exe" >NUL
if errorlevel 1 (
    echo [*] Memulai Apache...
    start "" /B "C:\xampp\apache\bin\httpd.exe"
    timeout /T 2 /NOBREAK >NUL
) else (
    echo [OK] Apache sudah berjalan.
)

echo [3] Pindah ke folder proyek...
cd /D "C:\xampp\htdocs\Project_skripsi_2"

echo [4] Membuka browser...
timeout /T 2 /NOBREAK >NUL
start http://127.0.0.1:8000

echo.
echo ================================================================
echo   Server Laravel siap diakses!
echo   URL     : http://127.0.0.1:8000
echo   Login   : admin / password
echo   Tekan Ctrl+C untuk menghentikan server.
echo ================================================================
echo.

"C:\xampp\php\php.exe" artisan serve --port=8000
