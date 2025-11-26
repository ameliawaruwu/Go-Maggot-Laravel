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
    
    // --- Penambahan Relasi Baru ---
    
    /**
     * Relasi One-to-Many ke DetailPesanan (Satu Pesanan memiliki banyak Detail Produk).
     * Diasumsikan Foreign Key di tabel 'detail_pesanan' adalah 'id_pesanan'.
     */
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }
    
    /**
     * Relasi Many-to-One ke StatusPesanan (Satu Pesanan memiliki satu Status Terkini).
     * StatusPesanan::class harus sudah di-import di bagian atas file jika menggunakan namespace.
     */
    public function status()
    {
        // Parameter 1: Model tujuan (StatusPesanan::class)
        // Parameter 2: Foreign Key di tabel Pesanan ('id_status_pesanan')
        // Parameter 3: Primary Key di tabel StatusPesanan ('id_status_pesanan')
        return $this->belongsTo(StatusPesanan::class, 'id_status_pesanan', 'id_status_pesanan');
    }
    
}
    

