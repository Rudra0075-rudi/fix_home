<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = JobRequest::with(['user', 'worker', 'service']);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('worker_id')) {
            $query->where('worker_id', $request->worker_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        $perPage = $request->get('per_page', 15);
        $jobRequests = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $jobRequests,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:user_profiles,id'],
            'service_id' => ['required', 'exists:services,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['sometimes', 'in:normal,urgent'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $jobRequest = JobRequest::create($validated);
        $jobRequest->load(['user', 'service']);

        return response()->json([
            'success' => true,
            'message' => 'Job request created successfully',
            'data' => $jobRequest,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $jobRequest = JobRequest::with(['user', 'worker', 'service', 'payment'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $jobRequest,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $jobRequest = JobRequest::findOrFail($id);

        $validated = $request->validate([
            'status' => ['sometimes', 'in:pending,accepted,in_progress,completed,cancelled'],
            'worker_id' => ['sometimes', 'nullable', 'exists:worker_profiles,id'],
            'final_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'worker_notes' => ['sometimes', 'nullable', 'string'],
        ]);

        $jobRequest->update($validated);
        $jobRequest->load(['user', 'worker', 'service']);

        return response()->json([
            'success' => true,
            'message' => 'Job request updated successfully',
            'data' => $jobRequest,
        ]);
    }
}
