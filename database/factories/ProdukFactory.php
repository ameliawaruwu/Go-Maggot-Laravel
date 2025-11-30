<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produk;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Produk::class;
    public function definition(): array
    {
        return [
            'id_produk' => strtoupper($this->faker->bothify('PR##')),
            'nama_produk' => $this->faker->randomElement([
                'Bibit Maggot',
                'Maggot Dewasa',
                'Pupuk Kompos',
                'Kandang Maggot',
                'Paket Bundling 1',
                'Paket Bundling 2',
            ]),

            'deskripsi_produk' => $this->faker->randomElement([
                'Bibit maggot adalah fase awal larva siap ternak',
                'Maggot Dewasa adalah pakan ternak protein tinggi alami',
                'Pupuk Kompos adalah pupuk organik hasil sisa maggot',
                'Kandang maggot adalah tempat budidaya maggot dalam skala kecil',
                'Paket hemat budidaya maggot',
                'Paket Komplit budidaya manggot',
            ]),

            'kategori' => $this->faker->randomElement([
                'BSF',
                'Kompos',
                'Kandang Maggot',
                'Lainnya',
            ]),

            'merk' => $this->faker->randomElement(['GoMaggot']),
            'masa_penyimpanan' => $this->faker->randomElement([
                '7 Hari',
                '14 hari',
            ]),
            'pengiriman' => $this->faker->randomElement([
                'Instan',
                'Reguler',
                'Cargo',
            ]),

            'berat'=> $this->faker->randomElement([
                '500 gr',
                '1 Kg',
            ]),

            'harga' => $this->faker->randomElement([
                '25000', 
                '50000',
                '60000',
                '85000',
            ]),

            'stok' => fake()->numberBetween(0, 100),
            'gambar'=> null,
        ];
    }
}
