-- ==============================================================
-- DATABASE SQL DUMP: SISTEM INFORMASI LAYANAN DAN KONSULTASI HUKUM
-- KANTOR AHMAD HANDOKO LAW OFFICE BANDAR LAMPUNG
-- Skema Ternormalisasi (1NF - 3NF Lengkap)
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `db_ahmad_handoko_law` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_ahmad_handoko_law`;

SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- 1. Tabel: user_roles (Master Hak Akses)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_role` varchar(50) NOT NULL,
  `deskripsi` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_roles_nama_role_unique` (`nama_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_roles` (`id`, `nama_role`, `deskripsi`) VALUES
(1, 'sekretaris', 'Admin / Sekretaris Kantor Hukum'),
(2, 'pengacara', 'Advokat / Pengacara Pembimbing'),
(3, 'klien', 'Klien / Pemohon Layanan Hukum');

-- -------------------------------------------------------------
-- 2. Tabel: users (Data Akun & Kredensial Pengguna)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(25) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) NOT NULL DEFAULT 'default.png',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password untuk semua akun default di bawah ini adalah: password
INSERT INTO `users` (`id`, `role_id`, `nama_lengkap`, `username`, `email`, `password`, `no_hp`, `alamat`, `is_active`) VALUES
(1, 1, 'Siti Rahayu, S.H.', 'admin', 'admin@ahmadhandoko.law', '$2y$10$21yy5TXcHuKUWJbw8Z7o0uzpvTzuhyQySejALnkmbBzOncr8Wmwym', '085211110001', 'Kantor Ahmad Handoko Law Office, Bandar Lampung', 1),
(2, 2, 'Yopi Hendro, S.H., M.H.', 'pengacara1', 'yopi@ahmadhandoko.law', '$2y$10$21yy5TXcHuKUWJbw8Z7o0uzpvTzuhyQySejALnkmbBzOncr8Wmwym', '085211110002', 'Jl. Raden Intan No. 52, Bandar Lampung', 1),
(3, 2, 'Ahmad Handoko, S.H.', 'pengacara2', 'handoko@ahmadhandoko.law', '$2y$10$21yy5TXcHuKUWJbw8Z7o0uzpvTzuhyQySejALnkmbBzOncr8Wmwym', '085211110003', 'Jl. Pangeran Diponegoro No. 71, Bandar Lampung', 1),
(4, 3, 'Budi Santoso', 'klien1', 'budi@email.com', '$2y$10$21yy5TXcHuKUWJbw8Z7o0uzpvTzuhyQySejALnkmbBzOncr8Wmwym', '081234567890', 'Jl. Way Halim No. 12, Bandar Lampung', 1),
(5, 3, 'Dewi Kartika', 'klien2', 'dewi@email.com', '$2y$10$21yy5TXcHuKUWJbw8Z7o0uzpvTzuhyQySejALnkmbBzOncr8Wmwym', '082345678901', 'Jl. Teuku Umar No. 55, Bandar Lampung', 1);

-- -------------------------------------------------------------
-- 3. Tabel: kategori_kasus (Master Klasterisasi Perkara)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `kategori_kasus`;
CREATE TABLE `kategori_kasus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kategori_kasus` (`id`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Hukum Pidana', 'Perkara tindak pidana kejahatan, penipuan, penggelapan, korupsi, dan pembelaan terdakwa.'),
(2, 'Hukum Perdata', 'Sengketa perikatan kontrak, warisan, perceraian, pembagian harta, sengketa hak tanah.'),
(3, 'Tata Usaha Negara (TUN)', 'Gugatan sengketa terhadap keputusan pejabat administrasi pemerintahan atau sertifikasi lahan.'),
(4, 'Hukum Perbankan & Korporasi', 'Sengketa keuangan, investasi perusahaan, perjanjian kredit bank, dan restrukturisasi utang.'),
(5, 'Hukum Ketenagakerjaan', 'Perselisihan pemutusan hubungan kerja (PHK), hak pesangon buruh, dan perjanjian kerja bersama.');

-- -------------------------------------------------------------
-- 4. Tabel: formulir_konsultasi (Transaksi Permohonan Kasus)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `formulir_konsultasi`;
CREATE TABLE `formulir_konsultasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no_permohonan` varchar(50) NOT NULL,
  `klien_id` bigint(20) unsigned NOT NULL,
  `pengacara_id` bigint(20) unsigned DEFAULT NULL,
  `kategori_id` bigint(20) unsigned NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `agama` varchar(50) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `uraian_singkat_perkara` text NOT NULL,
  `bukti_awal` varchar(255) DEFAULT NULL,
  `ttd_pemohon` varchar(255) DEFAULT NULL,
  `ttd_konsultan` varchar(255) DEFAULT NULL,
  `status_verifikasi` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending',
  `status_kasus` enum('baru','dalam_progres','selesai') NOT NULL DEFAULT 'baru',
  `status_jadwal_temu` enum('belum','dijadwalkan','selesai') NOT NULL DEFAULT 'belum',
  `tgl_waktu_jadwal_temu` datetime DEFAULT NULL,
  `catatan_jadwal_temu` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `formulir_no_permohonan_unique` (`no_permohonan`),
  KEY `formulir_klien_id_foreign` (`klien_id`),
  KEY `formulir_pengacara_id_foreign` (`pengacara_id`),
  KEY `formulir_kategori_id_foreign` (`kategori_id`),
  CONSTRAINT `formulir_klien_id_foreign` FOREIGN KEY (`klien_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `formulir_pengacara_id_foreign` FOREIGN KEY (`pengacara_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `formulir_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_kasus` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `formulir_konsultasi` (`id`, `no_permohonan`, `klien_id`, `pengacara_id`, `kategori_id`, `tempat_lahir`, `tgl_lahir`, `jenis_kelamin`, `agama`, `pekerjaan`, `uraian_singkat_perkara`, `status_verifikasi`, `status_kasus`, `status_jadwal_temu`, `tgl_waktu_jadwal_temu`, `catatan_jadwal_temu`) VALUES
(1, 'REG/2026/NOV/0001', 4, 2, 2, 'Bandar Lampung', '1990-05-15', 'L', 'Islam', 'Wiraswasta', 'Saya mengalami sengketa tanah dengan pihak ketiga di mana tanah warisan orang tua saya diklaim tanpa dasar hukum yang jelas. Sertifikat tanah atas nama almarhum ayah saya masih ada namun pihak tersebut mengklaim berdasarkan girik lama.', 'disetujui', 'dalam_progres', 'dijadwalkan', '2026-11-10 10:00:00', 'Ruang Konsultasi Lt. 1 - Kantor Ahmad Handoko Law Office'),
(2, 'REG/2026/NOV/0002', 5, NULL, 5, 'Metro', '1995-08-22', 'P', 'Kristen', 'Karyawan Swasta', 'Saya di-PHK sepihak oleh perusahaan tanpa pesangon dan surat peringatan sebelumnya. Masa kerja saya sudah 5 tahun namun perusahaan tidak memberikan kompensasi sesuai UU Ketenagakerjaan.', 'pending', 'baru', 'belum', NULL, NULL),
(3, 'REG/2026/OKT/0001', 4, 3, 4, 'Palembang', '1988-03-10', 'L', 'Islam', 'Direktur CV', 'Perusahaan saya memiliki fasilitas kredit di bank namun mengalami kesulitan mencicil. Membutuhkan bantuan hukum mediasi dan restrukturisasi utang dengan perbankan.', 'disetujui', 'selesai', 'selesai', '2026-10-15 14:00:00', 'Ruang Mediasi Lt. 2');

-- -------------------------------------------------------------
-- 5. Tabel: kontrak_kesepakatan (Pendampingan Hukum & Kuasa)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `kontrak_kesepakatan`;
CREATE TABLE `kontrak_kesepakatan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `formulir_id` bigint(20) unsigned NOT NULL,
  `berkas_kontrak` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status_sidang` varchar(100) NOT NULL DEFAULT 'Pemberkasan',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kontrak_formulir_id_foreign` (`formulir_id`),
  CONSTRAINT `kontrak_formulir_id_foreign` FOREIGN KEY (`formulir_id`) REFERENCES `formulir_konsultasi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 6. Tabel: pembayaran (Tagihan & Verifikasi Transfer)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `pembayaran`;
CREATE TABLE `pembayaran` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `formulir_id` bigint(20) unsigned NOT NULL,
  `jumlah_tagihan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('belum_bayar','menunggu_verifikasi','lunas') NOT NULL DEFAULT 'belum_bayar',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pembayaran_formulir_id_foreign` (`formulir_id`),
  CONSTRAINT `pembayaran_formulir_id_foreign` FOREIGN KEY (`formulir_id`) REFERENCES `formulir_konsultasi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pembayaran` (`id`, `formulir_id`, `jumlah_tagihan`, `keterangan`, `status`) VALUES
(1, 1, 2500000.00, 'Biaya konsultasi hukum awal dan telaah berkas perkara sengketa tanah.', 'menunggu_verifikasi'),
(2, 3, 5000000.00, 'Biaya jasa pendampingan hukum dan mediasi restrukturisasi perbankan.', 'lunas');

-- -------------------------------------------------------------
-- 7. Tabel: pesan_chat (Konsultasi Daring Interaktif)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `pesan_chat`;
CREATE TABLE `pesan_chat` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `formulir_id` bigint(20) unsigned NOT NULL,
  `pengirim_id` bigint(20) unsigned NOT NULL,
  `pesan` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pesan_chat_formulir_id_foreign` (`formulir_id`),
  KEY `pesan_chat_pengirim_id_foreign` (`pengirim_id`),
  CONSTRAINT `pesan_chat_formulir_id_foreign` FOREIGN KEY (`formulir_id`) REFERENCES `formulir_konsultasi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesan_chat_pengirim_id_foreign` FOREIGN KEY (`pengirim_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pesan_chat` (`id`, `formulir_id`, `pengirim_id`, `pesan`, `is_read`, `created_at`) VALUES
(1, 1, 4, 'Selamat pagi Bapak, saya ingin menanyakan perkembangan penelaahan kasus sengketa tanah warisan saya.', 1, '2026-11-05 08:30:00'),
(2, 1, 2, 'Selamat pagi Pak Budi. Berkas dan sertifikat sudah kami telaah. Kami jadwalkan temu resmi di kantor untuk finalisasi gugatan.', 1, '2026-11-05 08:35:00'),
(3, 1, 4, 'Baik Pak Yopi, saya akan hadir sesuai jadwal yang tertera. Terima kasih banyak.', 0, '2026-11-05 08:38:00'),
(4, 3, 4, 'Terima kasih banyak Pak Ahmad Handoko, kesepakatan restrukturisasi perbankan telah berhasil ditandatangani.', 1, '2026-10-25 15:00:00'),
(5, 3, 3, 'Sama-sama Pak Budi. Senang dapat memberikan pendampingan hukum terbaik untuk kelangsungan usaha Bapak.', 1, '2026-10-25 15:05:00');

SET FOREIGN_KEY_CHECKS = 1;

