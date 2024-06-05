<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowing>
 */
class BorrowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ukm_name' => fake()->company(),
            'event_name' => fake()->bs(),
            'num_of_participants' => rand(1, 50),
            'event_date' => fake()->dateTimeBetween('now', '+2 months'),
            'student_id' => rand(1, 50),
        ];
    }
}
