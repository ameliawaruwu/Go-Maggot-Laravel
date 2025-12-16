<?php

namespace Database\Seeders;

use App\Models\Artikel;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ArtikelSeeder extends Seeder
{
    public function run()
    {
        $lastArtikel = Artikel::orderBy('id_artikel', 'desc')->first();

        $lastNumber = $lastArtikel
            ? (int) substr($lastArtikel->id_artikel, 3)
            : 0;

        $articlesData = [
            [
                'id_artikel' => 'ART' . str_pad(++$lastNumber, 3, '0', STR_PAD_LEFT),
                'judul' => 'Melakukan Budidaya Maggot Untuk Pemula',
                'penulis' => 'Tim GoMaggot',
                'tanggal' => '2024-05-15',
                'hak_cipta' => 'Hak Cipta: Tim GoMaggot',
                'gambar' => 'maggot1.jpg',
                'konten' => '...',
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(30),
            ],
            [
                'id_artikel' => 'ART' . str_pad(++$lastNumber, 3, '0', STR_PAD_LEFT),
                'judul' => 'Pemanfaatan Maggot BSF untuk Pakan Ternak',
                'penulis' => 'Penulis Internal',
                'tanggal' => '2024-06-01',
                'hak_cipta' => 'Hak Cipta: Penulis Internal',
                'gambar' => 'pakan-ayam.jpg',
                'konten' => '...',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'id_artikel' => 'ART' . str_pad(++$lastNumber, 3, '0', STR_PAD_LEFT),
                'judul' => 'Mengenal Lebih Dalam Maggot BSF',
                'penulis' => 'Admin GoMaggot',
                'tanggal' => '2024-07-10',
                'hak_cipta' => 'Sumber: Artikel Maggot BSF. Hak Cipta oleh Admin.',
                'gambar' => 'bsf-lifecycle.jpg',
                'konten' => '...',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($articlesData as $data) {
            Artikel::create($data);
        }
    }
}
