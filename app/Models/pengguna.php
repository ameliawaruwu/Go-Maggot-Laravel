<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Notifications\Notifiable;


class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; 
    use SoftDeletes;
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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pengguna');
    }
    public function reviews() {
        return $this->hasMany(Review::class, 'id_review');
    }
    
}
