<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Admin and Regular User
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{student}', [StudentController::class, 'show']);

    // Admin Only
    Route::middleware('role:admin')->group(function () {

        Route::post('/students', [StudentController::class, 'store']);

        Route::put('/students/{student}', [StudentController::class, 'update']);

        Route::delete('/students/{student}', [StudentController::class, 'destroy']);

    });

});