<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faq';
    protected $primaryKey = 'id_faq';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['id_faq', 'pertanyaan', 'jawaban'];
}
