<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatusPesanan;

class StatusPesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'id_status_pesanan' => 'MENUNGGU_PEMBAYARAN', 
                'status' => 'Menunggu Pembayaran', 
                'deskripsi' => 'Pesanan telah dibuat, menunggu konfirmasi pembayaran dari pembeli.',
                'urutan_tampilan' => 1
            ],
            [
                'id_status_pesanan' => 'PEMBAYARAN_DITERIMA', 
                'status' => 'Pembayaran Diterima', 
                'deskripsi' => 'Pembayaran telah terverifikasi atau bukti telah diunggah. Siap diproses oleh admin.',
                'urutan_tampilan' => 2
            ],
            [
                'id_status_pesanan' => 'DIKEMAS', 
                'status' => 'Dikemas', 
                'deskripsi' => 'Pesanan sedang disiapkan dan dikemas oleh penjual.',
                'urutan_tampilan' => 3
            ],
            [
                'id_status_pesanan' => 'DIKIRIM', 
                'status' => 'Dikirim', 
                'deskripsi' => 'Pesanan telah diserahkan kepada kurir dan dalam perjalanan menuju alamat tujuan.',
                'urutan_tampilan' => 4
            ],
            [
                'id_status_pesanan' => 'SELESAI', 
                'status' => 'Selesai', 
                'deskripsi' => 'Pesanan telah diterima oleh pembeli atau status pengiriman sudah selesai.',
                'urutan_tampilan' => 5
            ],
            [
                'id_status_pesanan' => 'DIBATALKAN', 
                'status' => 'Dibatalkan', 
                'deskripsi' => 'Pesanan dibatalkan oleh pembeli atau penjual.',
                'urutan_tampilan' => 99 
            ],
        ];

        foreach ($statuses as $status) {
            StatusPesanan::updateOrCreate(
                ['id_status_pesanan' => $status['id_status_pesanan']],
                $status
            );
        }
    }
}