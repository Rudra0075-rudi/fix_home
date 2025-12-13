<?php

namespace Database\Seeders;

use App\Models\JobRequest;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create payments for completed job requests
        $completedJobs = JobRequest::where('status', 'completed')
            ->whereNotNull('worker_id')
            ->whereNotNull('final_price')
            ->get();

        foreach ($completedJobs as $job) {
            if (!$job->payment) {
                $amount = $job->final_price ?? $job->budget;
                $platformFee = $amount * 0.1;
                $workerPayout = $amount - $platformFee;

                Payment::factory()->create([
                    'job_request_id' => $job->id,
                    'user_id' => $job->user_id,
                    'worker_id' => $job->worker_id,
                    'amount' => $amount,
                    'platform_fee' => $platformFee,
                    'worker_payout' => $workerPayout,
                    'status' => 'completed',
                ]);
            }
        }
    }
}
