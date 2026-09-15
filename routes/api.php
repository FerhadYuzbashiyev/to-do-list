<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tasks', TaskController::class)->only('index', 'store', 'show', 'destroy');
Route::post('tasks/{task}', [TaskController::class, 'update']);
Route::post('tasks/{task}/complete', [TaskController::class, 'complete']);