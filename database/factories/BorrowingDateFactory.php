<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BorrowingDate>
 */
class BorrowingDateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_date' => fake()->dateTimeBetween('now', '+1 months'),
            'end_date' => fake()->dateTimeBetween('+1 months', '+2 months'),
            'asset_id' => rand(1, 50),
        ];
    }
}
