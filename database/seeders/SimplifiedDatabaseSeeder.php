<?php

namespace Database\Seeders;

use App\Models\JobRequest;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\WorkerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SimplifiedDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with simplified schema.
     */
    public function run(): void
    {
        // Get or create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'password' => Hash::make('password'),
                'type' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Get existing user profiles or create new ones
        $userProfiles = UserProfile::all();
        if ($userProfiles->isEmpty()) {
            for ($i = 1; $i <= 10; $i++) {
                $user = User::firstOrCreate(
                    ['email' => "user{$i}@example.com"],
                    [
                        'password' => Hash::make('password'),
                        'type' => 'user',
                        'email_verified_at' => now(),
                    ]
                );

                UserProfile::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => "User {$i}",
                        'email' => "user{$i}@example.com",
                        'phone' => '+123456789' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    ]
                );
            }
            $userProfiles = UserProfile::all();
        }

        // Get existing worker profiles or create new ones
        $workerProfiles = WorkerProfile::with('services')->get();
        if ($workerProfiles->isEmpty()) {
            $workerNames = [
                'John Smith', 'Maria Garcia', 'Ahmed Hassan', 'Priya Patel', 'David Kim',
                'Sarah Johnson', 'Michael Brown', 'Emma Wilson', 'James Taylor', 'Lisa Anderson',
                'Robert Martinez', 'Jennifer Lee', 'William Davis', 'Amanda White', 'Christopher Moore',
            ];

            for ($i = 0; $i < 15; $i++) {
                $worker = User::firstOrCreate(
                    ['email' => strtolower(str_replace(' ', '', $workerNames[$i])) . '@example.com'],
                    [
                        'password' => Hash::make('password'),
                        'type' => 'worker',
                        'email_verified_at' => now(),
                    ]
                );

                $workerProfile = WorkerProfile::firstOrCreate(
                    ['user_id' => $worker->id],
                    [
                        'name' => $workerNames[$i],
                        'email' => strtolower(str_replace(' ', '', $workerNames[$i])) . '@example.com',
                        'phone' => '+123456789' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    ]
                );

                // Create services if they don't exist
                if ($workerProfile->services->isEmpty()) {
                    $serviceNames = [
                        'Deep Cleaning', 'Laundry', 'Child Care', 'Cooking', 'Pet Care',
                        'Gardening', 'Plumbing', 'Electrical Repair', 'Moving Help', 'Elderly Care',
                    ];
                    $randomServices = array_rand($serviceNames, rand(2, 4));
                    foreach ($randomServices as $serviceIndex) {
                        Service::create([
                            'worker_id' => $workerProfile->id,
                            'name' => $serviceNames[$serviceIndex],
                            'description' => "Professional {$serviceNames[$serviceIndex]} service",
                        ]);
                    }
                }
            }
            $workerProfiles = WorkerProfile::with('services')->get();
        }

        // Only create job requests if they don't exist
        if (JobRequest::count() === 0) {
            // Create demo job requests
            $statuses = ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'];
            $priorities = ['normal', 'urgent'];
            $jobTitles = [
                'House Cleaning', 'Laundry Service', 'Child Care', 'Cooking Service', 'Pet Care',
                'Garden Maintenance', 'Plumbing Repair', 'Electrical Work', 'Moving Assistance', 'Elderly Care',
            ];

            $jobRequests = [];
            for ($i = 0; $i < 20; $i++) {
                $userProfile = $userProfiles->random();
                $workerProfile = $workerProfiles->random();
                $service = $workerProfile->services->random();
                
                if (!$service) {
                    continue;
                }

                $status = $statuses[array_rand($statuses)];
                $budget = rand(50, 500);
                $finalPrice = in_array($status, ['completed', 'in_progress']) ? $budget + rand(-20, 50) : null;

                $jobRequest = JobRequest::create([
                    'user_id' => $userProfile->id,
                    'worker_id' => $status !== 'pending' ? $workerProfile->id : null,
                    'service_id' => $service->id,
                    'title' => $jobTitles[array_rand($jobTitles)],
                    'description' => "Need help with {$service->name} service. Please contact me for details.",
                    'status' => $status,
                    'priority' => $priorities[array_rand($priorities)],
                    'budget' => $budget,
                    'final_price' => $finalPrice,
                    'scheduled_at' => now()->addDays(rand(1, 30)),
                    'completed_at' => $status === 'completed' ? now()->subDays(rand(1, 10)) : null,
                    'location' => "Address " . rand(100, 999) . " Street",
                ]);
                $jobRequests[] = $jobRequest;
            }

            // Create demo payments for completed job requests
            foreach ($jobRequests as $jobRequest) {
                if ($jobRequest->status === 'completed' && $jobRequest->final_price) {
                    $amount = $jobRequest->final_price;
                    $platformFee = round($amount * 0.15, 2); // 15% platform fee
                    $workerPayout = round($amount - $platformFee, 2);

                    Payment::create([
                        'job_request_id' => $jobRequest->id,
                        'user_id' => $jobRequest->user_id,
                        'worker_id' => $jobRequest->worker_id,
                        'amount' => $amount,
                        'platform_fee' => $platformFee,
                        'worker_payout' => $workerPayout,
                        'payment_method' => ['cash', 'digital', 'card', 'wallet'][array_rand(['cash', 'digital', 'card', 'wallet'])],
                        'status' => 'completed',
                        'transaction_id' => 'TXN' . strtoupper(uniqid()),
                    ]);
                }
            }

            // Create a few refunded payments
            $refundedJobs = array_filter($jobRequests, fn($job) => $job->status === 'cancelled');
            foreach (array_slice($refundedJobs, 0, 3) as $jobRequest) {
                if ($jobRequest->final_price) {
                    Payment::create([
                        'job_request_id' => $jobRequest->id,
                        'user_id' => $jobRequest->user_id,
                        'worker_id' => $jobRequest->worker_id,
                        'amount' => $jobRequest->final_price ?? $jobRequest->budget,
                        'platform_fee' => 0,
                        'worker_payout' => 0,
                        'payment_method' => 'digital',
                        'status' => 'refunded',
                        'transaction_id' => 'REF' . strtoupper(uniqid()),
                    ]);
                }
            }
        }
    }
}

