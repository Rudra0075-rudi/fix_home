<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\WorkerProfile;
use App\Models\Service;
use App\Models\JobRequest;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    /**
     * Get admin dashboard statistics.
     */
    public function stats(): JsonResponse
    {
        // Count users and workers from the simplified schema
        $totalUsers = UserProfile::count();
        $totalWorkers = WorkerProfile::count();
        $totalServices = Service::count();
        
        // Count by type from users table
        $usersByType = User::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $totalUserAccounts = $usersByType['user'] ?? 0;
        $totalWorkerAccounts = $usersByType['worker'] ?? 0;
        $totalAdminAccounts = $usersByType['admin'] ?? 0;

        // Get job request statistics
        $totalRequests = JobRequest::count();
        $totalOrders = JobRequest::whereIn('status', ['accepted', 'in_progress', 'completed'])->count();
        
        // Get payment statistics
        $totalIncome = Payment::where('status', 'completed')->sum('amount');
        $totalPayouts = Payment::where('status', 'completed')->sum('worker_payout');
        $totalPlatformFees = Payment::where('status', 'completed')->sum('platform_fee');
        $disputedAmount = Payment::where('status', 'refunded')->sum('amount');

        // Get recent job requests
        $recentJobRequests = JobRequest::with(['user', 'worker', 'service'])
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'kpis' => [
                    'total_requests' => $totalRequests,
                    'total_orders' => $totalOrders,
                    'total_income' => round($totalIncome, 2),
                    'active_workers' => $totalWorkers,
                    'pending_approvals' => 0, // Not available in simplified schema
                ],
                'financial' => [
                    'payouts' => round($totalPayouts, 2),
                    'platform_fees' => round($totalPlatformFees, 2),
                    'disputed' => round($disputedAmount, 2),
                ],
                'recent_activities' => $recentJobRequests->map(function ($job) {
                    return [
                        'title' => "Job request: {$job->title}",
                        'detail' => "Status: {$job->status}",
                        'time' => $job->created_at->diffForHumans(),
                    ];
                }),
                'summary' => [
                    'total_users' => $totalUserAccounts,
                    'total_workers' => $totalWorkerAccounts,
                    'total_admins' => $totalAdminAccounts,
                    'total_services' => $totalServices,
                ],
            ],
        ]);
    }
}
