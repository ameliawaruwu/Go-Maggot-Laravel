<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reviews;
use App\Models\Produk;
use App\Models\Pengguna;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reviews>
 */
class ReviewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Reviews::class;

    public function definition(): array
    {
        return [
            'id_review' => 'RV'. $this->faker->unique()->numberBetween(1, 99),
            'id_pengguna' => Pengguna::factory(),
            'id_produk'   => Produk::factory(),

            'komentar' => $this->faker->sentence(10), 
            
            'rating_seller' => $this->faker->numberBetween(1, 5), 
            'foto' => null, 
            'video' => null,
            
            'kualitas' => $this->faker->randomElement(['Sangat Baik', 'Baik', 'Standar', 'Kurang']),
            'kegunaan' => $this->faker->randomElement(['Sangat Bermanfaat', 'Bermanfaat', 'Biasa Saja']),
            'tampilkan_username' => $this->faker->boolean(80), 
            'status' => $this->faker->randomElement(['approved', 'rejected']),
            'tanggal_review' => $this->faker->dateTimeThisYear(),
        ];
    }
}