<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('galeri')->insert([
            [
                'id_galeri'   => (string) Str::uuid(),
                'gambar'      => 'galeri/workshop-laravel.jpg',
                'keterangan'  => 'Workshop Laravel untuk dosen dan mahasiswa',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id_galeri'   => (string) Str::uuid(),
                'gambar'      => 'galeri/seminar-ti.jpg',
                'keterangan'  => 'Seminar nasional teknologi informasi',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ]);
    }
}
