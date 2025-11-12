<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\CommentController;

Route::prefix('v1')->group(function () {

    /* AUTH */
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/refresh-token', [AuthController::class, 'refresh']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
    });

    /* PROJECTS */
    Route::middleware('auth:api')->group(function () {
        Route::get('/projects', [ProjectController::class, 'index']);
        Route::get('/projects/{id}', [ProjectController::class, 'show']);
        Route::post('/projects', [ProjectController::class, 'store'])->middleware('role:admin');
        Route::put('/projects/{id}', [ProjectController::class, 'update'])->middleware('role:admin');
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->middleware('role:admin');

        /* TASKS */
        Route::get('/projects/{project_id}/tasks', [TaskController::class, 'index']);
        Route::get('/tasks/{id}', [TaskController::class, 'show']);
        Route::post('/projects/{project_id}/tasks', [TaskController::class, 'store'])->middleware('role:manager');
        Route::put('/tasks/{id}', [TaskController::class, 'update']); // logic in controller: only manager or assigned user
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->middleware('role:manager');

        /* COMMENTS */
        Route::get('/tasks/{task_id}/comments', [CommentController::class, 'index']);
        Route::post('/tasks/{task_id}/comments', [CommentController::class, 'store']);
    });
});
