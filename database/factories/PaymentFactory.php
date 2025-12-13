<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 25, 250);
        $platformFee = $amount * 0.1; // 10% platform fee
        $workerPayout = $amount - $platformFee;

        return [
            'amount' => $amount,
            'platform_fee' => $platformFee,
            'worker_payout' => $workerPayout,
            'payment_method' => fake()->randomElement(['cash', 'digital', 'card', 'wallet']),
            'status' => fake()->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'transaction_id' => 'TXN' . fake()->unique()->numerify('########'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
