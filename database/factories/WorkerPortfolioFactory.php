<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkerPortfolio>
 */
class WorkerPortfolioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['work_sample', 'tool', 'certification'];
        $titles = [
            'work_sample' => ['Clean Kitchen Transformation', 'Organized Living Room', 'Sparkling Bathroom'],
            'tool' => ['Professional Cleaning Equipment', 'High-Quality Tools', 'Modern Equipment Set'],
            'certification' => ['Certified Professional', 'Safety Certified', 'Industry Certified'],
        ];

        $type = fake()->randomElement($types);
        $title = fake()->randomElement($titles[$type]);

        return [
            'title' => $title,
            'description' => fake()->paragraph(),
            'image_url' => 'https://images.unsplash.com/photo-' . fake()->numerify('#########') . '?w=400',
            'type' => $type,
            'is_featured' => fake()->boolean(30),
        ];
    }
}
