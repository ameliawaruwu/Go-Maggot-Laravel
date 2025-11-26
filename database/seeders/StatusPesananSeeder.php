<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusPesananSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('status_pesanan')->insert([
            [
                'id_status_pesanan' => 'SP001',
                'status' => 'Menunggu Pembayaran',
                'deskripsi' => 'Pesanan menunggu pembayaran dari pelanggan.',
                'urutan_tampilan' => 1,
            ],
            [
                'id_status_pesanan' => 'SP002',
                'status' => 'Diproses',
                'deskripsi' => 'Pesanan sedang diproses.',
                'urutan_tampilan' => 2,
            ],
            [
                'id_status_pesanan' => 'SP003',
                'status' => 'Dikirim',
                'deskripsi' => 'Pesanan sedang dikirim.',
                'urutan_tampilan' => 3,
            ],
            [
                'id_status_pesanan' => 'SP004',
                'status' => 'Selesai',
                'deskripsi' => 'Pesanan selesai.',
                'urutan_tampilan' => 4,
            ],
            [
                'id_status_pesanan' => 'SP005',
                'status' => 'Dibatalkan',
                'deskripsi' => 'Pesanan dibatalkan.',
                'urutan_tampilan' => 5,
            ],
        ]);
    }
}
