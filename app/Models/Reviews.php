<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengguna;
use App\Models\Produk;


class Reviews extends Model
{
    use HasFactory;
    protected $table = 'reviews';
    protected $primaryKey = 'id_review';
    protected $keyType = 'string';
    protected $fillable = ['id_review', 'id_pengguna', 'id_produk', 'komentar', 
                            'foto', 'video', 'kualitas', 'kegunaan', 'tampilkan_username',
                            'rating_seller', 'tanggal_review', 'status'];

    protected $casts = ['tanggal_review']; 

    public function pengguna() {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function produk(){
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
    
}
