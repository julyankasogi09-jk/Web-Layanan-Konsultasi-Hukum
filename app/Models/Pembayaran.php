<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'formulir_id', 'jumlah_tagihan', 'bukti_bayar', 'keterangan', 'status',
    ];

    protected $casts = [
        'jumlah_tagihan' => 'decimal:2',
    ];

    public function formulir()
    {
        return $this->belongsTo(FormulirKonsultasi::class, 'formulir_id');
    }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'belum_bayar'          => '<span class="badge bg-danger">Belum Bayar</span>',
            'menunggu_verifikasi'  => '<span class="badge bg-warning text-dark">Menunggu Verifikasi</span>',
            'lunas'                => '<span class="badge bg-success">Lunas</span>',
            default                => '-',
        };
    }
}

