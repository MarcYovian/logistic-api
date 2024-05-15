<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'type' => fake()->word(),
            'description' => fake()->paragraph(mt_rand(5, 10)),
            'image_Path' => fake()->imageUrl(640, 480, 'assets', true),
            'admin_id' => rand(1, 4)
        ];
    }
}
