<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\JobRequestController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::get('workers', [WorkerController::class, 'index']);
    Route::get('workers/{id}', [WorkerController::class, 'show']);
    Route::get('services', [ServiceController::class, 'index']);
    Route::apiResource('job-requests', JobRequestController::class)->except(['destroy']);
    Route::get('admin/stats', [AdminController::class, 'stats']);
});
