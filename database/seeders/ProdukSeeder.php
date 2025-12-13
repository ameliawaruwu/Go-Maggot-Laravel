<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Produk; 

class ProdukSeeder extends Seeder
{
    public function run(): void
    {

        Produk::factory()
            ->count(8) 
            ->state(new Sequence(
              
                [
                    'id_produk'       => 'PR01',
                    'nama_produk'      => 'Bibit Maggot',
                    'deskripsi_produk' => 'Bibit maggot adalah fase awal larva siap ternak',
                    'kategori'         => 'BSF',
                    'merk'             => 'GoMaggot',
                    'masa_penyimpanan' => '7 Hari',
                    'pengiriman'       => 'Instan',
                    'berat'            => '500 gr',
                    'harga'            => 25000,
                    'stok'             => 50,
                    'gambar'           => null,
                ],
               
                [
                     'id_produk'       => 'PR02',
                    'nama_produk'      => 'Maggot Dewasa',
                    'deskripsi_produk' => 'Maggot Dewasa adalah pakan ternak protein tinggi alami',
                    'kategori'         => 'BSF',
                    'merk'             => 'GoMaggot',
                    'masa_penyimpanan' => '7 Hari',
                    'pengiriman'       => 'Instan',
                    'berat'            => '1 Kg',
                    'harga'            => 50000,
                    'stok'             => 100,
                    'gambar'           => null,
                ],
              
                [
                     'id_produk'       => 'PR03',
                    'nama_produk'      => 'Pupuk Kompos',
                    'deskripsi_produk' => 'Pupuk Kompos adalah pupuk organik hasil sisa maggot',
                    'kategori'         => 'Kompos',
                    'merk'             => 'GoMaggot',
                    'masa_penyimpanan' => '14 hari',
                    'pengiriman'       => 'Reguler',
                    'berat'            => '1 Kg',
                    'harga'            => 15000,
                    'stok'             => 87,
                    'gambar'           => null,
                ],
              
                [
                    'id_produk'       => 'PR04',
                    'nama_produk'      => 'Kandang Maggot',
                    'deskripsi_produk' => 'Kandang maggot adalah tempat budidaya maggot dalam skala kecil',
                    'kategori'         => 'Kandang Maggot',
                    'merk'             => 'GoMaggot',
                    'masa_penyimpanan' => '14 hari',
                    'pengiriman'       => 'Reguler',
                    'berat'            => '5 Kg',
                    'harga'            => 150000,
                    'stok'             => 60,
                    'gambar'           => null,
                ],
                
                [
                    'id_produk'       => 'PR05',
                    'nama_produk'      => 'Paket Bundling 1',
                    'deskripsi_produk' => 'Paket hemat budidaya maggot',
                    'kategori'         => 'Lainnya',
                    'merk'             => 'GoMaggot',
                    'masa_penyimpanan' => '7 Hari',
                    'pengiriman'       => 'Reguler',
                    'berat'            => '2 Kg',
                    'harga'            => 85000,
                    'stok'             => 20,
                    'gambar'           => null,
                ],
               
                [
                    'id_produk'       => 'PR06',
                    'nama_produk'      => 'Paket Bundling 2',
                    'deskripsi_produk' => 'Paket Komplit budidaya manggot',
                    'kategori'         => 'Lainnya',
                    'merk'             => 'GoMaggot',
                    'masa_penyimpanan' => '7 Hari',
                    'pengiriman'       => 'Cargo',
                    'berat'            => '5 Kg',
                    'harga'            => 120000,
                    'stok'             => 23,
                    'gambar'           => null,
                ],

                [
                    'id_produk'       => 'PR07',
                    'nama_produk'      => 'Paket Bundling 3',
                    'deskripsi_produk' => 'Paket Komplit budidaya manggot',
                    'kategori'         => 'Lainnya',
                    'merk'             => 'GoMaggot',
                    'masa_penyimpanan' => '7 Hari',
                    'pengiriman'       => 'Instan',
                    'berat'            => '5 Kg',
                    'harga'            => 120000,
                    'stok'             => 34,
                    'gambar'           => null,
                ],

                [
                    'id_produk'       => 'PR08',
                    'nama_produk'      => 'Paket Bundling 4',
                    'deskripsi_produk' => 'Paket Komplit budidaya manggot',
                    'kategori'         => 'Lainnya',
                    'merk'             => 'GoMaggot',
                    'masa_penyimpanan' => '7 Hari',
                    'pengiriman'       => 'Cargo',
                    'berat'            => '5 Kg',
                    'harga'            => 120000,
                    'stok'             => 60,
                    'gambar'           => null,
                ],

                
            ))
            ->create();
    }
}