<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormulirKonsultasi extends Model
{
    protected $table = 'formulir_konsultasi';

    protected $fillable = [
        'no_permohonan', 'klien_id', 'pengacara_id', 'kategori_id',
        'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'agama', 'pekerjaan',
        'uraian_singkat_perkara', 'bukti_awal', 'ttd_pemohon', 'ttd_konsultan',
        'status_verifikasi', 'status_kasus', 'status_jadwal_temu',
        'tgl_waktu_jadwal_temu', 'catatan_jadwal_temu',
    ];

    protected $casts = [
        'tgl_lahir'           => 'date',
        'tgl_waktu_jadwal_temu' => 'datetime',
    ];

    public function klien()
    {
        return $this->belongsTo(User::class, 'klien_id');
    }

    public function pengacara()
    {
        return $this->belongsTo(User::class, 'pengacara_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriKasus::class, 'kategori_id');
    }

    public function kontrak()
    {
        return $this->hasOne(KontrakKesepakatan::class, 'formulir_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'formulir_id');
    }

    public function pesanChat()
    {
        return $this->hasMany(PesanChat::class, 'formulir_id');
    }

    public function getLabelStatusVerifikasiAttribute(): string
    {
        return match ($this->status_verifikasi) {
            'pending'   => '<span class="badge bg-warning text-dark">Menunggu Verifikasi</span>',
            'disetujui' => '<span class="badge bg-success">Disetujui</span>',
            'ditolak'   => '<span class="badge bg-danger">Ditolak</span>',
            default     => '-',
        };
    }

    public function getLabelStatusKasusAttribute(): string
    {
        return match ($this->status_kasus) {
            'baru'          => '<span class="badge bg-info text-dark">Baru</span>',
            'dalam_progres' => '<span class="badge bg-warning text-dark">Dalam Progres</span>',
            'selesai'       => '<span class="badge bg-success">Selesai</span>',
            default         => '-',
        };
    }
}

