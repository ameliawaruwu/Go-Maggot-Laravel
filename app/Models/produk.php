<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;
    
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $incrementing = false;
    protected $keyType = 'string';

    // 1. TAMBAHKAN INI: Agar field 'gambar_url' otomatis muncul di JSON response
    protected $appends = ['gambar_url'];

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

    // url gambar
    public function getGambarUrlAttribute()
    {
        if (!$this->gambar) {
            return null;
        }
        return url('photo/' . $this->gambar); 
        
    
    }
  
    public function produk() {
        return $this->hasMany(Review::class, 'id_produk');
    }
    
    public function reviews() {
        return $this->hasMany(Review::class, 'id_review');
    }
}