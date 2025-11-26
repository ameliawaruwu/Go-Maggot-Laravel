<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        Pengguna::factory()->create([
            'id_pengguna'   => 'PG001',
            'username'      => 'Admin Goma',
            'email'         => 'admin@gmail.com',
            'password'      => Hash::make('password'), 
            'role'          => 'admin',
            'nomor_telepon' => '081234567890',
            'alamat'        => 'Kantor Pusat GoMaggot',
            'foto_profil'   => null,
        ]);

        Pengguna::factory()->create([
            'id_pengguna'   => 'PG138',
            'username'      => 'Esa jelita',
            'email'         => 'jelita@gmail.com',
            'password'      => Hash::make('password'), 
            'role'          => 'pelanggan',
            'nomor_telepon' => '081987654321',
            'alamat'        => 'Kopo Permai',
            'foto_profil'   => null,
        ]);

        Pengguna::factory(10)->create();
    }
}