Write-Host ""
Write-Host "======================================================================" -ForegroundColor Cyan
Write-Host "  AHMAD HANDOKO LAW OFFICE (AHLO) - SISTEM INFORMASI HUKUM" -ForegroundColor Yellow
Write-Host "======================================================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "[*] Menyiapkan service MySQL dan Apache XAMPP..." -ForegroundColor Green
Start-Process "C:\xampp\mysql\bin\mysqld.exe" -ArgumentList "--defaults-file=C:\xampp\mysql\bin\my.ini", "--standalone" -WindowStyle Hidden -ErrorAction SilentlyContinue
Start-Process "C:\xampp\apache\bin\httpd.exe" -WindowStyle Hidden -ErrorAction SilentlyContinue

Set-Location "C:\xampp\htdocs\Project_skripsi_2"

Write-Host "[*] Membuka browser: http://127.0.0.1:8000" -ForegroundColor Cyan
Start-Process "http://127.0.0.1:8000"

Write-Host ""
Write-Host "----------------------------------------------------------------------" -ForegroundColor DarkGray
Write-Host " Server Laravel Berjalan!" -ForegroundColor White
Write-Host " Akses: http://127.0.0.1:8000" -ForegroundColor Gray
Write-Host " Tekan Ctrl + C untuk mematikan server." -ForegroundColor Yellow
Write-Host "----------------------------------------------------------------------" -ForegroundColor DarkGray
Write-Host ""

php artisan serve --port=8000
