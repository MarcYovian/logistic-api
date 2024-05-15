<?php

namespace Database\Factories;

use App\Enums\StatusBorrowing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailBorrowing>
 */
class DetailBorrowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'borrowing_id' => rand(1, 10),
            'asset_id' => rand(1, 50),
            'admin_id' => rand(1, 4),
            'start_date' => fake()->dateTimeBetween('now', '+1 months'),
            'end_date' => fake()->dateTimeBetween('+1 months', '+2 months'),
            'description' => fake()->sentence(5),
            'num' => fake()->randomDigitNot(0),
            'status' => StatusBorrowing::PENDING->value,
        ];
    }
}
