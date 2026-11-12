<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('formulir_konsultasi', function (Blueprint $table) {
            $table->id();
            $table->string('no_permohonan', 50)->unique();
            $table->foreignId('klien_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pengacara_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('kategori_id')->constrained('kategori_kasus')->onDelete('restrict');
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('agama', 50)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->text('uraian_singkat_perkara');
            $table->string('bukti_awal', 255)->nullable();
            $table->string('ttd_pemohon', 255)->nullable();
            $table->string('ttd_konsultan', 255)->nullable();
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->enum('status_kasus', ['baru', 'dalam_progres', 'selesai'])->default('baru');
            $table->enum('status_jadwal_temu', ['belum', 'dijadwalkan', 'selesai'])->default('belum');
            $table->dateTime('tgl_waktu_jadwal_temu')->nullable();
            $table->string('catatan_jadwal_temu', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulir_konsultasi');
    }
};

