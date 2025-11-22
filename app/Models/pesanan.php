<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $incrementing = false;     // primary key string
    protected $keyType = 'string';

    protected $fillable = [
        'id_pesanan',
        'id_pengguna',
        'nama_penerima',
        'alamat_pengiriman',
        'nomor_telepon',
        'tanggal_pesanan',
        'metode_pembayaran',
        'total_harga'
    ];

    protected $dates = [
        'tanggal_pesanan',
    ];

    // Relasi ke Pengguna (many to one)
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}
