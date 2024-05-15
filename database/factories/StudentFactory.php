<?php

namespace Database\Factories;

use App\Enums\Major;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nim' => rand(1000000000, 1202249999),
            'major' => fake()->randomElement(Major::values()),
            'email' => fake()->email(),
            'username' => fake()->userName(),
            'password' => Hash::make('password'),
        ];
    }
}
