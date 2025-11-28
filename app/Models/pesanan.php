<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\StatusPesanan;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $incrementing = false;     
    protected $keyType = 'string';

    protected $fillable = [
        'id_pesanan',
        'id_pengguna',
        'nama_penerima',
        'alamat_pengiriman',
        'nomor_telepon',
        'tanggal_pesanan',
        'metode_pembayaran',
        'total_harga',
        'id_status_pesanan',
    ];

    protected $dates = [
        'tanggal_pesanan',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function status()
    {
        return $this->belongsTo(Pengguna::class, 'id_status_pesanan');
    }
    

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
    

