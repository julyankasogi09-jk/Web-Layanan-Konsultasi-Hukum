# Sistem Informasi Layanan dan Konsultasi Hukum
## Kantor Ahmad Handoko Law Office — Bandar Lampung

> **Judul Skripsi / Tugas Akhir:** Sistem Informasi Layanan dan Konsultasi Hukum Berbasis Website pada Kantor Ahmad Handoko Law Office Bandar Lampung  
> **Teknologi Utama:** Laravel 12 · PHP 8.2 · MySQL (3NF Normalized) · Dynamic QRIS Gateway (EMVCo) · Bootstrap 5 · AJAX Polling Chat

---

## 📄 Dokumen Laporan & Diagram Resmi (Siap Diperiksa Dosen)

Repositori ini telah dilengkapi dengan dokumen laporan komprehensif berstandar akademik tinggi yang siap diunduh dan dinilai:

| Dokumen | Format | Deskripsi & Isi Dokumen |
|:---|:---:|:---|
| **Laporan Tugas Proyek Perangkat Lunak** | [PDF](./Laporan_Tugas_Proyek_Sistem_Informasi_Ahmad_Handoko_Law_Office.pdf) &bull; [HTML](./Laporan_Tugas_Proyek_Sistem_Informasi_Ahmad_Handoko_Law_Office.html) | Laporan lengkap **Bab I s/d Bab VIII** mencakup latar belakang, tinjauan pustaka, analisis kebutuhan aktor, perancangan basis data 3NF, arsitektur MVC, algoritma CRC16 QRIS dinamis, hasil 15 skenario pengujian *Black Box*, serta kesimpulan. |
| **Kumpulan 8 Diagram Sistem & UML** | [PDF](./Kumpulan_Diagram_Sistem_Skripsi_Ahmad_Handoko_Law_Office.pdf) &bull; [HTML Interaktif](./diagram_sistem_skripsi.html) | Kumpulan diagram rekayasa: **ERD Crow's Foot 3NF**, **Use Case Diagram** (14 Use Cases, 3 Aktor), **5 Activity Diagrams**, **2 Flowcharts**, **Sequence Diagram MVC**, **Class Diagram**, **State Machine Lifecycle Perkara**, dan **Deployment Diagram 3-Tier**. |
| **Laporan Normalisasi Database 3NF** | [PDF](./Laporan_Normalisasi_Database.pdf) | Analisis ilmiah pemenuhan 1NF, 2NF, hingga 3NF pada seluruh skema tabel basis data. |

---

## 🚀 Cara Menjalankan Aplikasi (1 Perintah Cepat)

Buka **Command Prompt (CMD)** di Windows, lalu cukup ketik:

```cmd
ahlo
```

Perintah `ahlo` otomatis melakukan:
1. Memeriksa dan menyalakan service **MySQL XAMPP**
2. Memeriksa dan menyalakan service **Apache XAMPP**
3. Membuka peramban browser langsung ke `http://127.0.0.1:8000`
4. Menjalankan web server Laravel (`php artisan serve`)

---

## 🔐 Akun Uji Coba Default (Siap Login)

Seluruh role telah disediakan akun siap pakai untuk mempermudah evaluasi penguji:

| Peran (Role) | Username | Password | Deskripsi Wewenang |
|:---|:---:|:---:|:---|
| **Sekretaris (Admin)** | `admin` | `password` | Verifikasi perkara, penugasan advokat, jadwal temu, terbitkan tagihan, konfirmasi pembayaran, dan rekapitulasi laporan. |
| **Advokat / Pengacara** | `pengacara1` | `password` | Menerima disposisi perkara, perbarui tahapan linimasa, upload berkas kontrak/kuasa hukum, dan ruang chat konsultasi. |
| **Klien (Pemohon)** | `klien1` | `password` | Mengajukan konsultasi perkara, upload bukti awal, pantau progres, scan QRIS dinamis, upload bukti transfer, dan chat. |

*(Tersedia juga akun cadangan: `pengacara2` / `password` dan `klien2` / `password`)*

---

## 🗄️ Langkah Setup Manual (Clone dari GitHub)

Jika ingin menjalankan aplikasi secara manual setelah clone dari repositori GitHub:

```bash
# 1. Masuk ke direktori hasil clone
cd C:\xampp\htdocs\Project_skripsi_2

# 2. Salin environment konfigurasi
copy .env.example .env

# 3. Pasang paket dependensi PHP
composer install

# 4. Generate Application Key Laravel
php artisan key:generate

# 5. Eksekusi migrasi basis data & Seeder (Pastikan MySQL XAMPP aktif)
php artisan migrate:fresh --seed
```

> **Alternatif Import Database 1-Klik:** Anda juga dapat langsung mengimpor file `database.sql` yang tersedia di direktori utama repositori ini ke **phpMyAdmin** pada basis data `db_ahmad_handoko_law`.

---

## 💳 Inovasi Payment Gateway QRIS Dinamis (EMVCo Standard)

Aplikasi ini tidak sekadar menampilkan gambar statis, melainkan memiliki **algoritma internal konversi QRIS Statis ke Dinamis**:

1. **Tag 01 (Initiation Method):** Mengubah kode statis `010211` menjadi dinamis `010212`.
2. **Tag 54 (Transaction Amount):** Menyisipkan nominal tagihan perkara hukum secara presisi (contoh: Rp 500.000 menjadi `5406500000`).
3. **Tag 63 (CRC16 Checksum):** Menghitung ulang nilai checksum 4-digit heksadesimal dengan algoritma **CRC16-CCITT** (polinomial `0x1021`, inisial `0xFFFF`).
4. **Kompatibilitas Luas:** Dapat di-scan menggunakan seluruh aplikasi pembayaran nasional (**DANA, BCA Mobile, BRImo, GoPay, OVO, ShopeePay, LinkAja**) dengan nominal tagihan terkunci otomatis.
5. **Multi-tier Failover:** Dilengkapi 3 lapisan CDN generator gambar QR (QRServer, Google Charts, QuickChart) serta tombol salin kode raw QRIS.

---

## 🏛️ Struktur Modul & Rute Sistem

| Hak Akses | Modul Utama | URL Rute |
|:---|:---|:---|
| **Publik** | Halaman Depan & Profil Firma Hukum | `/` |
| **Autentikasi** | Login Sistem Multi-Role | `/login` |
| **Autentikasi** | Pendaftaran Akun Klien Mandiri | `/register` |
| **Klien** | Dashboard Monitoring Kasus | `/klien/dashboard` |
| **Klien** | Formulir Pengajuan Permohonan Hukum | `/klien/ajukan-konsultasi` |
| **Klien** | Detail Kasus, QRIS Bayar & Upload Bukti | `/klien/kasus/{id}` |
| **Sekretaris** | Dashboard Statistik Perkara & Verifikasi | `/admin/dashboard` |
| **Sekretaris** | Manajemen Perkara (Verifikasi, Assign, Jadwal, Tagihan) | `/admin/perkara` |
| **Sekretaris** | Verifikasi Pembayaran Transaksi Klien | `/admin/pembayaran` |
| **Sekretaris** | Pengaturan QRIS & String Merchant | `/admin/pengaturan/pembayaran` |
| **Sekretaris** | Rekapitulasi Laporan Data Perkara (Siap Cetak) | `/admin/laporan/kasus` |
| **Sekretaris** | Rekapitulasi Laporan Keuangan (Siap Cetak) | `/admin/laporan/pembayaran` |
| **Pengacara** | Dashboard Advokat & Perkara Ditugaskan | `/pengacara/dashboard` |
| **Pengacara** | Update Tahapan Sidang & Berkas Surat Kuasa | `/pengacara/perkara/{id}` |
| **Multi-Role** | Ruang Chat Konsultasi Interaktif (AJAX Polling) | `/chat/{formulirId}` |

---

## 🧪 Hasil Pengujian (Black Box Testing)

Pengujian sistem dilakukan terhadap 15 skenario fungsionalitas utama:
- ✅ Manajemen Autentikasi & Proteksi Role Middleware (Lolos)
- ✅ Validasi Input Formulir Konsultasi & Auto-generate No. Registrasi (Lolos)
- ✅ Disposisi Perkara & Notifikasi Penugasan Advokat (Lolos)
- ✅ Penjadwalan Temu Konsultasi Tatap Muka / Daring (Lolos)
- ✅ Penerbitan Tagihan & Engine QRIS Dinamis Otomatis (Lolos)
- ✅ Verifikasi Bukti Pembayaran LUNAS (Lolos)
- ✅ Manajemen Unggah Dokumen Berkas Sidang & Surat Kuasa (Lolos)
- ✅ Chat Konsultasi Dua Arah Real-time Polling (Lolos)
- ✅ Cetak Laporan Perkara & Rekapitulasi Pembayaran (Lolos)

Tingkat keberhasilan pengujian: **100% Valid**.

---

## 📂 Struktur Direktori Utama

```
Project_skripsi_2/
├── app/
│   ├── Http/Controllers/       # AuthController, KlienController, AdminController, dll.
│   ├── Http/Middleware/        # CheckRole.php (Proteksi Hak Akses)
│   ├── Models/                 # Eloquent Model: FormulirKonsultasi, Pembayaran, PaymentSetting, dll.
├── config/                     # Konfigurasi sistem (payment.php, app.php, database.php)
├── database/
│   ├── migrations/             # 8 Berkas Migrasi Skema Basis Data 3NF
│   ├── seeders/                # DatabaseSeeder.php (Dummy Data Siap Uji)
├── public/                     # Asset Publik & Entry Point (index.php)
├── resources/views/            # Blade Templates (layouts, auth, klien, admin, pengacara, chat, laporan)
├── routes/web.php              # Rute Lengkap Sistem Informasi
├── .env.example                # Templat Variabel Lingkungan Siap Pakai
├── ahlo.bat                    # Skrip Peluncur Otomatis 1-Klik
├── database.sql                # SQL Dump Lengkap untuk Import phpMyAdmin
├── diagram_sistem_skripsi.html # Visualisasi Interaktif 8 Diagram UML
├── Kumpulan_Diagram_Sistem_Skripsi_Ahmad_Handoko_Law_Office.pdf # PDF Diagram Resmi
├── Laporan_Tugas_Proyek_Sistem_Informasi_Ahmad_Handoko_Law_Office.pdf # PDF Laporan Proyek Lengkap
└── README.md                   # Dokumentasi Utama Repositori
```

---

*Dikembangkan untuk Tugas Akhir / Skripsi Sarjana Sistem Informasi &bull; Tahun 2026*
