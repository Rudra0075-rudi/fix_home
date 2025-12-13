<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobRequest>
 */
class JobRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'];
        $priority = fake()->randomElement(['normal', 'urgent']);
        $status = fake()->randomElement($statuses);
        $budget = fake()->randomFloat(2, 20, 200);
        $finalPrice = ($status === 'completed' || $status === 'in_progress') ? fake()->randomFloat(2, $budget * 0.9, $budget * 1.1) : null;

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => $status,
            'priority' => $priority,
            'budget' => $budget,
            'final_price' => $finalPrice,
            'scheduled_at' => fake()->dateTimeBetween('now', '+30 days'),
            'completed_at' => ($status === 'completed') ? fake()->dateTimeBetween('-7 days', 'now') : null,
            'location' => fake()->address(),
            'user_notes' => fake()->optional()->paragraph(),
            'worker_notes' => ($status !== 'pending') ? fake()->optional()->paragraph() : null,
        ];
    }
}
