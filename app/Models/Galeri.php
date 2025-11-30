<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeri';
    protected $primaryKey = 'id_galeri';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_galeri', 'gambar', 'keterangan'];
}
