<?php

namespace Database\Seeders;

use App\Models\JobRequest;
use App\Models\Service;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Seeder;

class JobRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $workers = Worker::all();
        $services = Service::all();

        if ($users->isEmpty() || $services->isEmpty()) {
            return;
        }

        // Create 50 job requests
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $service = $services->random();
            $worker = fake()->boolean(60) ? $workers->random() : null;

            JobRequest::factory()->create([
                'user_id' => $user->id,
                'worker_id' => $worker?->id,
                'service_id' => $service->id,
            ]);
        }
    }
}
