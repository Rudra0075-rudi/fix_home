<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Worker>
 */
class WorkerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specializations = [
            ['deep cleaning', 'laundry', 'child care'],
            ['deep cleaning', 'cooking'],
            ['elderly care', 'pet care'],
            ['electrical repair', 'plumbing'],
            ['gardening', 'moving help'],
            ['deep cleaning', 'laundry'],
        ];

        return [
            'bio' => fake()->paragraph(),
            'rating' => fake()->randomFloat(2, 3.5, 5.0),
            'completed_jobs' => fake()->numberBetween(10, 500),
            'recommended_by' => fake()->numberBetween(5, 400),
            'is_verified' => fake()->boolean(70),
            'is_available' => fake()->boolean(80),
            'specializations' => fake()->randomElement($specializations),
            'location' => fake()->city(),
            'hourly_rate' => fake()->randomFloat(2, 15, 50),
        ];
    }
}
