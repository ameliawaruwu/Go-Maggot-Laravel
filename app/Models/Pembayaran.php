<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id_pembayaran',
        'id_pengguna',
        'id_pesanan',
        'tanggal_bayar',
        'bukti_bayar',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

   
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}