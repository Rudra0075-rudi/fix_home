<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceCombo>
 */
class ServiceComboFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $originalPrice = fake()->randomFloat(2, 100, 300);
        $discountPercentage = fake()->numberBetween(10, 30);
        $discountedPrice = $originalPrice * (1 - $discountPercentage / 100);

        $comboNames = [
            'Cleaning + Laundry Combo',
            'Deep Clean + Child Care Package',
            'Maintenance Bundle',
            'Complete Home Care',
            'Weekly Service Package',
        ];

        return [
            'name' => fake()->randomElement($comboNames),
            'description' => fake()->paragraph(),
            'original_price' => $originalPrice,
            'discounted_price' => $discountedPrice,
            'discount_percentage' => $discountPercentage,
            'is_active' => true,
        ];
    }
}
