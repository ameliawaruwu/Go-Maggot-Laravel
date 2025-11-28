<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table = 'reviews';
    protected $primaryKey = 'id_review';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_review',
        'id_pengguna',
        'id_produk',
        'komentar',
        'foto',
        'video',
        'kualitas',
        'kegunaan',
        'tampilkan_username',
        'rating_seller',
        'tanggal_review',
        'status',
    ];
}
