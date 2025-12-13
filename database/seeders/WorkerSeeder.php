<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Service;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerPortfolio;
use App\Models\ServiceCombo;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 workers
        $workers = User::factory(20)->create(['role' => 'worker']);

        foreach ($workers as $user) {
            $worker = Worker::factory()->create(['user_id' => $user->id]);

            // Attach random services (2-5 services per worker)
            $services = Service::inRandomOrder()->take(rand(2, 5))->get();
            foreach ($services as $service) {
                $worker->services()->attach($service->id, [
                    'price' => fake()->randomFloat(2, 20, 100),
                    'description' => fake()->sentence(),
                ]);
            }

            // Attach random badges (0-3 badges per worker)
            $badges = Badge::inRandomOrder()->take(rand(0, 3))->get();
            foreach ($badges as $badge) {
                $worker->badges()->attach($badge->id, [
                    'awarded_by' => User::where('role', 'admin')->first()?->id,
                    'reason' => fake()->sentence(),
                ]);
            }

            // Create 2-4 portfolio items per worker
            WorkerPortfolio::factory(rand(2, 4))->create(['worker_id' => $worker->id]);

            // Create 1-2 service combos per worker
            if ($services->count() >= 2) {
                ServiceCombo::factory(rand(1, 2))->create(['worker_id' => $worker->id])
                    ->each(function ($combo) use ($services) {
                        $comboServices = $services->random(rand(2, min(3, $services->count())));
                        $combo->services()->attach($comboServices->pluck('id')->toArray());
                    });
            }
        }
    }
}
