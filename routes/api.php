<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\{RoleController, TaskController, UserController};
use App\Http\Middleware\UserUnauthorized;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('tasks/user', [TaskController::class, 'getByUser']);

    Route::middleware(UserUnauthorized::class)->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('tasks', TaskController::class)->except(['destroy']);
    });

    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('tasks/{task}/status', [TaskController::class, 'getStatus']);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);

});
