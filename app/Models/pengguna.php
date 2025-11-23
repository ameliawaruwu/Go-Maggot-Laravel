<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengguna extends Authenticatable
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pengguna',
        'username',
        'email',
        'password',
        'nomor_telepon',
        'alamat',
        'foto_profil',
        'last_login',
        'reset_otp',
        'reset_token',
        'tanggal_daftar',
        'role',
        'is_deleted',
        'deleted_at'
    ];

    protected $dates = [
        'deleted_at',
        'last_login',
        'tanggal_daftar'
    ];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pengguna');
    }
    public function reviews() {
        return $this->hasMany(Reviews::class, 'id_review');
    }
    
}
