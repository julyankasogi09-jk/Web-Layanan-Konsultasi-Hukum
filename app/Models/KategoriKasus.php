<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKasus extends Model
{
    protected $table = 'kategori_kasus';
    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function formulirKonsultasi()
    {
        return $this->hasMany(FormulirKonsultasi::class, 'kategori_id');
    }
}

