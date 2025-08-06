<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Rotas públicas (sem autenticação)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas (requer autenticação)
Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {
    // Rotas de autenticação
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    // Rotas para Contas
    Route::apiResource('accounts', AccountController::class);

    // Rotas para Transações
    Route::prefix('accounts/{account}/transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/search', [TransactionController::class, 'search']);
        Route::post('/', [TransactionController::class, 'store']);
    });
    Route::apiResource('transactions', TransactionController::class)->only(['update', 'destroy']);
});
