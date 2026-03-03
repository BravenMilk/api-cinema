<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(90, 180),
            'poster_url' => 'https://placehold.co/300x450?text=' . urlencode(fake()->word()),
            'release_date' => fake()->date(),
            'rating' => fake()->randomElement(['SU', 'R13', 'D17']),
        ];
    }
}
