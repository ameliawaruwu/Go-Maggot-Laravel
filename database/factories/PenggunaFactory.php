<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash; 
use App\Models\Pengguna;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengguna>
 */
class PenggunaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Pengguna::class;
    public function definition(): array
    {
        
        return [
            'id_pengguna'    => 'PG' . $this->faker->unique()->numberBetween(1,999),
            'username'       => $this->faker->userName(),
            'email'          => $this->faker->unique()->safeEmail(),
            'password'       => Hash::make('password'), 
            'nomor_telepon'  => $this->faker->phoneNumber(),
            'alamat'         => $this->faker->address(),
            'role'           => $this->faker->randomElement(['pelanggan']),
            'tanggal_daftar' => $this->faker->dateTimeThisYear(),
            'last_login'     => $this->faker->dateTimeThisMonth(),
            'foto_profil'    => null,
            'reset_otp'      => null,
            'reset_token'    => null,
            'is_deleted'     => 0,
        ];
    }
}