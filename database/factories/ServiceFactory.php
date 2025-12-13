<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = [
            ['name' => 'Deep Cleaning', 'category' => 'cleaning', 'description' => 'Thorough cleaning of entire home including bathrooms, kitchen, and living areas'],
            ['name' => 'Laundry', 'category' => 'cleaning', 'description' => 'Washing, drying, folding, and ironing clothes'],
            ['name' => 'Child Care', 'category' => 'care', 'description' => 'Professional childcare services'],
            ['name' => 'Elderly Care', 'category' => 'care', 'description' => 'Compassionate care for elderly individuals'],
            ['name' => 'Cooking', 'category' => 'food', 'description' => 'Meal preparation and cooking services'],
            ['name' => 'Pet Care', 'category' => 'care', 'description' => 'Walking, feeding, and caring for pets'],
            ['name' => 'Electrical Repair', 'category' => 'maintenance', 'description' => 'Electrical repairs and installations'],
            ['name' => 'Plumbing', 'category' => 'maintenance', 'description' => 'Plumbing repairs and installations'],
            ['name' => 'Gardening', 'category' => 'maintenance', 'description' => 'Garden maintenance and landscaping'],
            ['name' => 'Moving Help', 'category' => 'moving', 'description' => 'Assistance with moving and relocation'],
        ];

        $service = fake()->randomElement($services);

        return [
            'name' => $service['name'],
            'description' => $service['description'],
            'category' => $service['category'],
            'is_active' => true,
        ];
    }
}
