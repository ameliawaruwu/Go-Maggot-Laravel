<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_produk',
        'nama_produk',
        'deskripsi_produk',
        'kategori',
        'merk',
        'masa_penyimpanan',
        'pengiriman',
        'berat',
        'harga',
        'stok',
        'gambar'
    ];

    public function produk() {
        return $this->hasMany(Reviews::class, 'id_produk');
    }
    public function reviews() {
        return $this->hasMany(Reviews::class, 'id_review');
    }
}
