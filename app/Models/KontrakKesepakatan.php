<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontrakKesepakatan extends Model
{
    protected $table = 'kontrak_kesepakatan';

    protected $fillable = [
        'formulir_id', 'berkas_kontrak', 'keterangan', 'status_sidang',
    ];

    public function formulir()
    {
        return $this->belongsTo(FormulirKonsultasi::class, 'formulir_id');
    }
}

