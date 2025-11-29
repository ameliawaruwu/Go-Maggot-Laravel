<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Pengguna;
use App\Models\Produk;  
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManageReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $listPengguna = Pengguna::all(); // atau DB::table('pengguna')->get();
        $listProduk   = Produk::all();   // atau DB::table('produk')->get();

        // Cek jika data kosong, hentikan seeder
        if ($listPengguna->isEmpty() || $listProduk->isEmpty()) {
            $this->command->info('Data Pengguna atau Produk kosong. Seeder Review dilewati.');
            return;
        }

        // 2. Siapkan Data Dummy Komentar biar variatif
        $dummyComments = [
            [
                'komentar' => 'Maggotnya kering sempurna, ayam saya suka sekali!',
                'kualitas' => 'Sangat Baik',
                'kegunaan' => 'Pakan Ternak',
                'rating'   => 5
            ],
            [
                'komentar' => 'Pengiriman cepat, tapi kemasan agak penyok sedikit. Isinya aman.',
                'kualitas' => 'Baik',
                'kegunaan' => 'Pakan Ikan',
                'rating'   => 4
            ],
            [
                'komentar' => 'Barang sesuai deskripsi, seller ramah. Recommended!',
                'kualitas' => 'Sangat Baik',
                'kegunaan' => 'Pakan Burung',
                'rating'   => 5
            ],
            [
                'komentar' => 'Lumayan lah dengan harga segini.',
                'kualitas' => 'Standar',
                'kegunaan' => 'Umpan Pancing',
                'rating'   => 3
            ],
            [
                'komentar' => 'Kecewa, pengiriman lama banget sampai 5 hari.',
                'kualitas' => 'Buruk',
                'kegunaan' => 'Pakan Ternak',
                'rating'   => 2
            ],
        ];

        // 3. Loop untuk membuat 10-15 Review Dummy
        for ($i = 1; $i <= 10; $i++) {
            $idReview = 'REV' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $penggunaRandom = $listPengguna->random();
            $produkRandom   = $listProduk->random();
            $dataKomentar = $dummyComments[array_rand($dummyComments)];
            $cek = Review::where('id_review', $idReview)->exists();
            if ($cek) continue;

            Review::create([
                'id_review'          => $idReview,
                'id_pengguna'        => $penggunaRandom->id_pengguna, // Sesuaikan nama kolom PK di tabel pengguna
                'id_produk'          => $produkRandom->id_produk,     // Sesuaikan nama kolom PK di tabel produk
                'komentar'           => $dataKomentar['komentar'],
                'foto'               => null, 
                'video'              => null,
                'kualitas'           => $dataKomentar['kualitas'],
                'kegunaan'           => $dataKomentar['kegunaan'],
                'tampilkan_username' => rand(0, 1), // 1 = Tampil, 0 = Anonymous
                'rating_seller'      => $dataKomentar['rating'],
                'tanggal_review'     => Carbon::now()->subDays(rand(1, 30)), 
                'status'             => 'approved', 
            ]);
        }
    }
}