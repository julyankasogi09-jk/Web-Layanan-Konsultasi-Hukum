<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'role_id', 'nama_lengkap', 'username', 'email',
        'password', 'no_hp', 'alamat', 'foto', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'password'  => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function formulirKonsultasiSebagaiKlien()
    {
        return $this->hasMany(FormulirKonsultasi::class, 'klien_id');
    }

    public function formulirKonsultasiSebagaiPengacara()
    {
        return $this->hasMany(FormulirKonsultasi::class, 'pengacara_id');
    }

    public function pesanChat()
    {
        return $this->hasMany(PesanChat::class, 'pengirim_id');
    }

    public function isAdmin(): bool
    {
        return $this->role->nama_role === 'sekretaris';
    }

    public function isPengacara(): bool
    {
        return $this->role->nama_role === 'pengacara';
    }

    public function isKlien(): bool
    {
        return $this->role->nama_role === 'klien';
    }
}
