<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pesanan; 
use App\Models\Produk;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanan';
    protected $primaryKey = 'id_detail';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_detail', 
        'id_pesanan',
        'id_produk',
        'jumlah',
        'harga_saat_pembelian', 
    ];
    
    
    /**
     * 
     * 
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            // Membuat ID unik
            if (empty($model->id_detail)) {
                $model->id_detail = 'DET-' . time() . uniqid();
            }
            
            // Memindahkan data 'harga_satuan' ke 'harga_saat_pembelian'
            if (isset($model->harga_satuan)) {
                $model->harga_saat_pembelian = $model->harga_satuan;
                unset($model->harga_satuan); // Hapus property sementara
            }
        });
    }

    // Relasi ke Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}