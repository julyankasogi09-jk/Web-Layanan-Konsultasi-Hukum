<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesanChat extends Model
{
    protected $table = 'pesan_chat';

    protected $fillable = [
        'formulir_id', 'pengirim_id', 'pesan', 'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function formulir()
    {
        return $this->belongsTo(FormulirKonsultasi::class, 'formulir_id');
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }
}

