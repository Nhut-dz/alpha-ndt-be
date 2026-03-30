<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\AdminController;

// ===== PUBLIC ROUTES (no auth required) =====

// Auth
Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/register', [RegisterController::class, 'register']);

// Public endpoints for frontend website (prefix: /public)
Route::prefix('public')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{slug}', [PostController::class, 'showBySlug']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{slug}', [ProjectController::class, 'showBySlug']);
    Route::get('/recruitments', [RecruitmentController::class, 'index']);
    Route::get('/recruitments/{slug}', [RecruitmentController::class, 'showBySlug']);
});

// Public read endpoints (for admin dashboard without /public prefix)
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show'])->whereNumber('id');
Route::get('/post-categories', [PostCategoryController::class, 'index']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{id}', [ProjectController::class, 'show'])->whereNumber('id');
Route::get('/recruitments', [RecruitmentController::class, 'index']);
Route::get('/recruitments/{id}', [RecruitmentController::class, 'show'])->whereNumber('id');

// ===== PROTECTED ROUTES (require auth) =====
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [LoginController::class, 'logout']);
    Route::get('/auth/profile', [LoginController::class, 'profile']);
    Route::post('/auth/change-password', [LoginController::class, 'changePassword']);

    // Posts CRUD
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    // Post Categories CRUD
    Route::post('/post-categories', [PostCategoryController::class, 'store']);
    Route::put('/post-categories/{id}', [PostCategoryController::class, 'update']);
    Route::delete('/post-categories/{id}', [PostCategoryController::class, 'destroy']);

    // Contacts
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/contacts/{id}', [ContactController::class, 'show']);
    Route::patch('/contacts/{id}/status', [ContactController::class, 'updateStatus']);
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);

    // Projects CRUD
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    // Recruitments CRUD
    Route::post('/recruitments', [RecruitmentController::class, 'store']);
    Route::put('/recruitments/{id}', [RecruitmentController::class, 'update']);
    Route::delete('/recruitments/{id}', [RecruitmentController::class, 'destroy']);

    // Admin management
    Route::get('/admins', [AdminController::class, 'index']);
    Route::get('/admins/{id}', [AdminController::class, 'show']);
    Route::patch('/admins/{id}/status', [AdminController::class, 'updateStatus']);
});
