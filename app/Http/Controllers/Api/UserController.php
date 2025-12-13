<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreUserRequest;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = UserProfile::with('user');

        // Filter by type
        if ($request->has('type')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        // Search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $users = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        // Create user (auth)
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => $validated['type'] ?? 'user',
        ]);

        // Create user profile
        $userProfile = UserProfile::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $userProfile->load('user'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $userProfile = UserProfile::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $userProfile,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $userProfile = UserProfile::findOrFail($id);
        $validated = $request->validated();

        $userProfile->update([
            'name' => $validated['name'] ?? $userProfile->name,
            'email' => $validated['email'] ?? $userProfile->email,
            'phone' => $validated['phone'] ?? $userProfile->phone,
        ]);

        if (isset($validated['password'])) {
            $userProfile->user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $userProfile->fresh()->load('user'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $userProfile = UserProfile::findOrFail($id);
        $userProfile->user->delete(); // This will cascade delete the profile

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
