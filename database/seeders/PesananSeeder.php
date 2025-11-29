<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pengguna;
use App\Models\StatusPesanan;
use App\Models\Produk;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $pengguna = Pengguna::all();
        $status   = StatusPesanan::all();
        $produk   = Produk::all();

        if ($pengguna->isEmpty() || $status->isEmpty() || $produk->isEmpty()) {
            return;
        }
        
        for ($i = 1; $i <= 10; $i++) {

            $idPesanan = 'PSN' . str_pad($i, 3, '0', STR_PAD_LEFT);

            $pesanan = Pesanan::updateOrCreate([
                'id_pesanan' => $idPesanan
            ],[
                'id_pengguna'       => $pengguna->random()->id_pengguna,
                'nama_penerima'     => $pengguna->random()->username,
                'alamat_pengiriman' => $pengguna->random()->alamat ?? 'Alamat tidak tersedia',
                'nomor_telepon'     => $pengguna->random()->nomor_telepon ?? '0000000',
                'tanggal_pesanan'   => now(),
                'metode_pembayaran' => fake()->randomElement(['QRIS','COD']),
                'id_status_pesanan' => $status->random()->id_status_pesanan,
                'total_harga'       => 0, 
            ]);
            $jumlahBarang = rand(1, 3);
            $totalHarga = 0;

            for ($d = 1; $d <= $jumlahBarang; $d++) {
                $pilihProduk = $produk->random();
                $qty = rand(1, 5);

                $idDetail = 'DPS' . str_pad($i, 3, '0', STR_PAD_LEFT);

                DetailPesanan::create([
                    'id_detail'             => $idDetail,
                    'id_pesanan'            => $idPesanan,
                    'id_produk'             => $pilihProduk->id_produk,
                    'jumlah'                => $qty,
                    'harga_saat_pembelian'  => $pilihProduk->harga,
                ]);

            
                $pilihProduk->decrement('stok', $qty);
                $totalHarga += ($pilihProduk->harga * $qty);
            }

            $pesanan->update([
                'total_harga' => $totalHarga
            ]);
        }
    }
}
