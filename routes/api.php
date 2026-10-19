<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AnimalApplicationController;
use App\Http\Controllers\ReportController;

// 1. Rotas Públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/animals', [AnimalController::class, 'index']);
Route::get('/animals/{id}', [AnimalController::class, 'show']);

// 2. Rotas Comuns (Acedidas por Staff OU Adotantes)
// O middleware 'auth:adopter,staff' verifica se o utilizador está autenticado em qualquer um dos dois
Route::middleware(['auth:adopter,staff', 'throttle:60,1'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::patch('/me', [AuthController::class, 'updateProfile']);
});

// 3. Rotas Exclusivas de Adotantes
Route::middleware(['auth:adopter', 'throttle:60,1'])->group(function () {
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/my-applications', [ApplicationController::class, 'myApplications']);
});

// 4. Rotas Exclusivas de Staff
Route::middleware(['auth:staff', 'throttle:60,1'])->group(function () {
    Route::post('/register-staff', [StaffController::class, 'store']);
    Route::post('/animals', [AnimalController::class, 'store']);
    Route::patch('/animals/{id}', [AnimalController::class, 'update']);
    Route::delete('/animals/{id}', [AnimalController::class, 'destroy']);
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
    Route::get('/animals/{id}/applications', [AnimalApplicationController::class, 'index']);
    Route::get('/reports/adoptions', [ReportController::class, 'adoptions']);
});