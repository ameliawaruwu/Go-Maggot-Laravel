<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    /**
     *
     * 
     * 
     *
     * @var string
     */
    protected $table = 'pembayaran';

    /**
     * 
     *
     * @var string
     */
    protected $primaryKey = 'id_pembayaran';

    /**
     * 
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * 
     * 
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * 
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_pembayaran',
        'id_pengguna',
        'id_pesanan',
        'tanggal_bayar',
        'bukti_bayar',
    ];

    /**
     * Menentukan tipe data untuk casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    
    /**
     * Relasi ke model Pengguna (User)
     */
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Relasi ke model Pesanan yang dibayar.
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}