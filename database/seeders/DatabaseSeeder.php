<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\UserRole;
use App\Models\User;
use App\Models\KategoriKasus;
use App\Models\FormulirKonsultasi;
use App\Models\Pembayaran;
use App\Models\PesanChat;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $roleSekretaris = UserRole::create(['nama_role' => 'sekretaris', 'deskripsi' => 'Admin / Sekretaris Kantor Hukum']);
        $rolePengacara  = UserRole::create(['nama_role' => 'pengacara',  'deskripsi' => 'Advokat / Pengacara Kantor Hukum']);
        $roleKlien      = UserRole::create(['nama_role' => 'klien',      'deskripsi' => 'Klien / Pemohon Layanan Hukum']);

        // 2. Users
        $admin = User::create([
            'role_id'      => $roleSekretaris->id,
            'nama_lengkap' => 'Siti Rahayu, S.H.',
            'username'     => 'admin',
            'email'        => 'admin@ahmadhandoko.law',
            'password'     => Hash::make('password'),
            'no_hp'        => '085211110001',
            'alamat'       => 'Kantor Ahmad Handoko Law Office, Bandar Lampung',
            'is_active'    => true,
        ]);

        $pengacara1 = User::create([
            'role_id'      => $rolePengacara->id,
            'nama_lengkap' => 'Yopi Hendro, S.H., M.H.',
            'username'     => 'pengacara1',
            'email'        => 'yopi@ahmadhandoko.law',
            'password'     => Hash::make('password'),
            'no_hp'        => '085211110002',
            'alamat'       => 'Jl. Raden Intan No.52, Bandar Lampung',
            'is_active'    => true,
        ]);

        $pengacara2 = User::create([
            'role_id'      => $rolePengacara->id,
            'nama_lengkap' => 'Ahmad Handoko, S.H.',
            'username'     => 'pengacara2',
            'email'        => 'handoko@ahmadhandoko.law',
            'password'     => Hash::make('password'),
            'no_hp'        => '085211110003',
            'alamat'       => 'Jl. Pangeran Diponegoro No. 71, Bandar Lampung',
            'is_active'    => true,
        ]);

        $klien1 = User::create([
            'role_id'      => $roleKlien->id,
            'nama_lengkap' => 'Budi Santoso',
            'username'     => 'klien1',
            'email'        => 'budi@email.com',
            'password'     => Hash::make('password'),
            'no_hp'        => '081234567890',
            'alamat'       => 'Jl. Way Halim No. 12, Bandar Lampung',
            'is_active'    => true,
        ]);

        $klien2 = User::create([
            'role_id'      => $roleKlien->id,
            'nama_lengkap' => 'Dewi Kartika',
            'username'     => 'klien2',
            'email'        => 'dewi@email.com',
            'password'     => Hash::make('password'),
            'no_hp'        => '082345678901',
            'alamat'       => 'Jl. Teuku Umar No. 55, Bandar Lampung',
            'is_active'    => true,
        ]);

        // 3. Kategori Kasus
        $pidana     = KategoriKasus::create(['nama_kategori' => 'Hukum Pidana',              'deskripsi' => 'Perkara yang melibatkan tindak kriminal dan pelanggaran hukum pidana.']);
        $perdata    = KategoriKasus::create(['nama_kategori' => 'Hukum Perdata',             'deskripsi' => 'Sengketa antara individu atau badan hukum, warisan, kontrak, dll.']);
        $tun        = KategoriKasus::create(['nama_kategori' => 'Tata Usaha Negara (TUN)',   'deskripsi' => 'Sengketa terkait keputusan pejabat atau badan administrasi negara.']);
        $korporasi  = KategoriKasus::create(['nama_kategori' => 'Hukum Perbankan & Korporasi','deskripsi' => 'Sengketa keuangan, perbankan, investasi, dan hukum perusahaan.']);
        $naker      = KategoriKasus::create(['nama_kategori' => 'Hukum Ketenagakerjaan',     'deskripsi' => 'Perselisihan hubungan industrial antara karyawan dan perusahaan.']);

        // 4. Formulir Konsultasi Contoh
        $formulir1 = FormulirKonsultasi::create([
            'no_permohonan'          => 'REG/2026/NOV/0001',
            'klien_id'               => $klien1->id,
            'pengacara_id'           => $pengacara1->id,
            'kategori_id'            => $perdata->id,
            'tempat_lahir'           => 'Bandar Lampung',
            'tgl_lahir'              => '1990-05-15',
            'jenis_kelamin'          => 'L',
            'agama'                  => 'Islam',
            'pekerjaan'              => 'Wiraswasta',
            'uraian_singkat_perkara' => 'Saya mengalami sengketa tanah dengan tetangga di mana tanah warisan orang tua saya diklaim oleh pihak lain tanpa dasar hukum yang jelas. Sertifikat tanah atas nama almarhum ayah saya masih ada namun pihak tersebut mengklaim kepemilikan berdasarkan girik lama.',
            'status_verifikasi'      => 'disetujui',
            'status_kasus'           => 'dalam_progres',
            'status_jadwal_temu'     => 'dijadwalkan',
            'tgl_waktu_jadwal_temu'  => now()->addDays(3)->setTime(10, 0),
            'catatan_jadwal_temu'    => 'Ruang Konsultasi Lt. 1 - Kantor Ahmad Handoko Law Office',
        ]);

        $formulir2 = FormulirKonsultasi::create([
            'no_permohonan'          => 'REG/2026/NOV/0002',
            'klien_id'               => $klien2->id,
            'pengacara_id'           => null,
            'kategori_id'            => $naker->id,
            'tempat_lahir'           => 'Metro',
            'tgl_lahir'              => '1995-08-22',
            'jenis_kelamin'          => 'P',
            'agama'                  => 'Kristen',
            'pekerjaan'              => 'Karyawan Swasta',
            'uraian_singkat_perkara' => 'Saya di-PHK oleh perusahaan tanpa pesangon dan surat peringatan sebelumnya. Masa kerja saya sudah 5 tahun namun perusahaan tidak memberikan hak-hak saya sesuai ketentuan Undang-Undang Ketenagakerjaan.',
            'status_verifikasi'      => 'pending',
            'status_kasus'           => 'baru',
            'status_jadwal_temu'     => 'belum',
        ]);

        $formulir3 = FormulirKonsultasi::create([
            'no_permohonan'          => 'REG/2026/OKT/0001',
            'klien_id'               => $klien1->id,
            'pengacara_id'           => $pengacara2->id,
            'kategori_id'            => $korporasi->id,
            'tempat_lahir'           => 'Palembang',
            'tgl_lahir'              => '1988-03-10',
            'jenis_kelamin'          => 'L',
            'agama'                  => 'Islam',
            'pekerjaan'              => 'Direktur CV',
            'uraian_singkat_perkara' => 'Perusahaan saya mendapatkan kredit dari bank namun saat ini mengalami kesulitan membayar cicilan akibat pandemi. Saya membutuhkan bantuan hukum untuk restrukturisasi utang dan negosiasi dengan pihak bank.',
            'status_verifikasi'      => 'disetujui',
            'status_kasus'           => 'selesai',
            'status_jadwal_temu'     => 'selesai',
        ]);

        // 5. Pembayaran
        Pembayaran::create([
            'formulir_id'    => $formulir1->id,
            'jumlah_tagihan' => 2500000.00,
            'keterangan'     => 'Biaya konsultasi awal dan pendampingan hukum perkara sengketa tanah.',
            'status'         => 'menunggu_verifikasi',
        ]);

        Pembayaran::create([
            'formulir_id'    => $formulir3->id,
            'jumlah_tagihan' => 5000000.00,
            'keterangan'     => 'Biaya pendampingan hukum restrukturisasi utang perbankan.',
            'status'         => 'lunas',
        ]);

        // 6. Pesan Chat
        PesanChat::create(['formulir_id' => $formulir1->id, 'pengirim_id' => $klien1->id,    'pesan' => 'Selamat pagi Pak, saya ingin menanyakan perkembangan kasus sengketa tanah saya.', 'is_read' => true]);
        PesanChat::create(['formulir_id' => $formulir1->id, 'pengirim_id' => $pengacara1->id, 'pesan' => 'Selamat pagi Pak Budi. Saat ini kami sedang menyiapkan surat gugatan. Mohon disiapkan dokumen sertifikat tanah asli untuk pertemuan tanggal ' . now()->addDays(3)->format('d M Y') . '.', 'is_read' => true]);
        PesanChat::create(['formulir_id' => $formulir1->id, 'pengirim_id' => $klien1->id,    'pesan' => 'Baik Pak, sertifikat akan saya bawa. Apakah ada dokumen lain yang perlu disiapkan?', 'is_read' => false]);
        PesanChat::create(['formulir_id' => $formulir3->id, 'pengirim_id' => $klien1->id,    'pesan' => 'Terima kasih Pak Ahmad, kasus restrukturisasi utang saya sudah selesai dengan hasil yang memuaskan.', 'is_read' => true]);
        PesanChat::create(['formulir_id' => $formulir3->id, 'pengirim_id' => $pengacara2->id, 'pesan' => 'Sama-sama Pak Budi. Senang dapat membantu. Semoga usaha Bapak semakin berkembang ke depannya.', 'is_read' => true]);
    }
}
