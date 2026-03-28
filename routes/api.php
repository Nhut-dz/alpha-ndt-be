<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

// Public routes (no auth required)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Protected routes (auth:sanctum required)
Route::middleware('auth:sanctum')->group(function () {

    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']); // Super Admin only
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::get('/profile', [AuthController::class, 'profile']);
    });

    // Post management routes - Super Admin & Post_Admin
    Route::middleware('role:Post_Admin')->group(function () {
        Route::apiResource('posts', PostController::class);
        Route::apiResource('post-categories', PostCategoryController::class)->except(['show']);
    });

    // Contact management routes - Super Admin & Contact_Admin
    Route::middleware('role:Contact_Admin')->prefix('contacts')->group(function () {
        Route::get('/', [ContactController::class, 'index']);
        Route::get('/{id}', [ContactController::class, 'show']);
        Route::patch('/{id}/status', [ContactController::class, 'updateStatus']);
        Route::delete('/{id}', [ContactController::class, 'destroy']);
    });

    // Admin management routes - Super Admin only
    Route::middleware('role:Super Admin')->prefix('admins')->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::get('/profile', [AdminController::class, 'profile']);
        Route::get('/{id}', [AdminController::class, 'show']);
        Route::patch('/{id}/status', [AdminController::class, 'updateStatus']);
    });

    // HR management routes - Super Admin & HR_Admin
    Route::middleware('role:HR_Admin')->prefix('hr')->group(function () {
        // TODO: HR routes
    });
});
