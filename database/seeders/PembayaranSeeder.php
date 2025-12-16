<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;
use App\Models\Pesanan;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua pesanan yang sudah ada
        $pesananList = Pesanan::all();

        if ($pesananList->isEmpty()) {
            $this->command->info('Tidak ada data pesanan. Jalankan PesananSeeder terlebih dahulu.');
            return;
        }

        foreach ($pesananList as $pesanan) {
            // Generate ID Pembayaran
            $idPembayaran = 'PAY-' . time() . '-' . uniqid();

            // Kita isi data sesuai kolom yang ada di Model Pembayaran
            Pembayaran::create([
                'id_pembayaran' => $idPembayaran,
                'id_pesanan'    => $pesanan->id_pesanan,
                'id_pengguna'   => $pesanan->id_pengguna,
                'tanggal_bayar' => $pesanan->tanggal_pesanan, // Asumsi tanggal bayar sama dengan tanggal pesan
                'bukti_bayar'   => 'dummy_proof.jpg', 
            ]);
        }
    }
}