<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $badges = [
            ['name' => 'Trusted Pro', 'description' => 'Verified and trusted professional', 'color' => '#3b82f6'],
            ['name' => 'Punctual', 'description' => 'Always on time', 'color' => '#10b981'],
            ['name' => 'Highly Rated', 'description' => 'Consistently receives 5-star ratings', 'color' => '#f59e0b'],
            ['name' => 'Top Performer', 'description' => 'Exceeds expectations', 'color' => '#8b5cf6'],
            ['name' => 'Reliable', 'description' => 'Dependable and consistent', 'color' => '#06b6d4'],
            ['name' => 'Expert', 'description' => 'Master of their craft', 'color' => '#ef4444'],
        ];

        $badge = fake()->randomElement($badges);

        return [
            'name' => $badge['name'],
            'description' => $badge['description'],
            'icon' => null,
            'color' => $badge['color'],
            'is_active' => true,
        ];
    }
}
