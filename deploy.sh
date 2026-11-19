#!/bin/bash
# Script ini dijalankan otomatis oleh Railway saat deploy pertama kali
# atau bisa dijalankan manual via Railway Shell

echo "=== Deploy Ahmad Handoko Law Office ==="

# Generate APP_KEY jika belum ada
php artisan key:generate --force

# Jalankan migrasi database
php artisan migrate --force

# Seed data awal (hanya pertama kali - hapus baris ini setelah deploy pertama)
php artisan db:seed --force

# Buat storage link
php artisan storage:link

# Cache konfigurasi untuk performa
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Deploy selesai! ==="
