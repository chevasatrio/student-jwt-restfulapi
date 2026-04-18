<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// ─── Auth Routes (public) ───────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// ─── Protected Routes (JWT required) ────────
Route::middleware('auth:api')->group(function () {

    // Auth actions
    Route::prefix('auth')->group(function () {
        Route::post('logout',  [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me',      [AuthController::class, 'me']);
    });

    // 5 Student endpoints
    Route::apiResource('students', StudentController::class);
});
