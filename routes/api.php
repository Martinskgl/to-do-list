<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{TaskController, UserController, RoleController};
use App\Http\Controllers\Api\AuthController;

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

// Rotas públicas (sem autenticação)
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas (requerem autenticação)
Route::middleware('auth:sanctum')->group(function () {
    // Rotas de autenticação
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::get('tasks/user', [TaskController::class, 'getByUser']);
    Route::apiResource('tasks', TaskController::class);
   
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);

    Route::apiResource('users', UserController::class);

    Route::apiResource('roles', RoleController::class);
});