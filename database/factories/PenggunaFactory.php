<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;
    protected static $counter = 2;
    public function definition(): array
    {
        return [
            'id_pengguna'    => 'PG' . str_pad(self::$counter++, 3, '0', STR_PAD_LEFT),
            'username'       => $this->faker->userName(),
            'email'          => $this->faker->unique()->safeEmail(),
            'password'       => Hash::make('password'),
            'nomor_telepon'  => $this->faker->phoneNumber(),
            'alamat'         => $this->faker->address(),
            'role'           => 'pelanggan',
            'tanggal_daftar' => $this->faker->dateTimeThisYear(),
            'last_login'     => $this->faker->dateTimeThisMonth(),
            'foto_profil'    => null,
            'reset_otp'      => null,
            'reset_token'    => null,
            'is_deleted'     => 0,
        ];
    }
}
