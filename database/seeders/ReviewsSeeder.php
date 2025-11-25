<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use App\Models\Produk;
use App\Models\Reviews;

class ReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $users = Pengguna::all();
        $produk = Produk::all();

        Reviews::factory()
            ->count(30)
            ->state(fn (array $attributes) => [
                'id_pengguna' => $users->random()->id_pengguna,
                'id_produk'   => $produk->random()->id_produk,
            ])
            ->create();
    }
}