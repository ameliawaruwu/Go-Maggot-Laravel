<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPesanan extends Model
{
    use HasFactory;
    protected $table = 'status_pesanan';
    protected $primaryKey = 'id_status_pesanan';
    public $incrementing = false; // Karena primary key adalah string, bukan auto increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_status_pesanan',
        'status',
        'deskripsi',
        'urutan_tampilan',
    ];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_status_pesanan', 'id_status_pesanan');
    }
}

