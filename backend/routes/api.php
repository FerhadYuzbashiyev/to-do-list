<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('auth/me', [AuthController::class, 'getUser']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/update', [AuthController::class, 'update']);
    Route::delete('auth/delete', [AuthController::class, 'destroy']);

    Route::apiResource('tasks', TaskController::class)->only(['index', 'store', 'show', 'destroy']);

    Route::put('tasks/{task}', [TaskController::class, 'update']);
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::delete('admin/users/{user}', [AuthController::class, 'adminDestroy']);
});