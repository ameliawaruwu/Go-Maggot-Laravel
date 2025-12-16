<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID terakhir (contoh: GL005)
        $lastGaleri = DB::table('galeri')
            ->orderBy('id_galeri', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastGaleri) {
            $lastNumber = (int) substr($lastGaleri->id_galeri, 2);
        }

        $data = [
            [
                'gambar' => 'galeri/budidaya-maggot.jpg',
                'keterangan' => 'Proses budidaya maggot Black Soldier Fly (BSF) sebagai solusi pengolahan limbah organik',
            ],
            [
                'gambar' => 'galeri/panen-maggot.jpg',
                'keterangan' => 'Kegiatan panen maggot siap pakai untuk pakan ternak dan ikan',
            ],
            [
                'gambar' => 'galeri/pakan-maggot.jpg',
                'keterangan' => 'Maggot kering sebagai pakan alternatif bernutrisi tinggi',
            ],
            [
                'gambar' => 'galeri/kandang-bsf.jpg',
                'keterangan' => 'Kandang lalat BSF untuk proses pembiakan maggot',
            ],
        ];

        $insertData = [];

        foreach ($data as $index => $item) {
            $number = $lastNumber + $index + 1;

            $insertData[] = [
                'id_galeri'  => 'GL' . str_pad($number, 3, '0', STR_PAD_LEFT),
                'gambar'     => $item['gambar'],
                'keterangan' => $item['keterangan'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('galeri')->insert($insertData);
    }
}
