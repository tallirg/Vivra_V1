<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatbotController;


// =========================================================================
// RUTAS PÚBLICAS
// =========================================================================
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/experiencias', [ArticleController::class, 'index']);
Route::get('/experiencias/{id}', [ArticleController::class, 'show']);
Route::get('/experiencias/{experience_id}/resenas', [ReviewController::class, 'index']);


// =========================================================================
// RUTAS PROTEGIDAS
// =========================================================================
Route::middleware('auth:sanctum')->group(function () {

    // --- PRESTADOR ---
    Route::middleware('role:prestador')->group(function () {
        Route::post('/experiencias', [ArticleController::class, 'store']);
        Route::get('/mis-experiencias', [ArticleController::class, 'myExperiences']);
    });

    // --- ADMIN Y PRESTADOR ---
    Route::middleware('role:admin,prestador')->group(function () {
        Route::put('/experiencias/{id}', [ArticleController::class, 'update']);
        Route::delete('/experiencias/{id}', [ArticleController::class, 'destroy']);
    });

    // --- TURISTA ---
    Route::middleware('role:turista')->group(function () {
        Route::post('/carrito-comprar', [BookingController::class, 'store']);
        Route::get('/mis-reservas', [BookingController::class, 'myBookings']);
        Route::post('/experiencias/{experience_id}/resenas', [ReviewController::class, 'store']);
        Route::put('/resenas/{id}', [ReviewController::class, 'update']);
    });

    // --- ADMIN Y TURISTA ---
    Route::middleware('role:admin,turista')->group(function () {
        Route::delete('/resenas/{id}', [ReviewController::class, 'destroy']);
    });

    // --- ADMIN ---
    Route::middleware('role:admin')->group(function () {
        Route::get('/usuarios', [AuthController::class, 'index']);
        Route::get('/usuarios/{id}', [AuthController::class, 'show']);
        Route::post('/usuarios', [AuthController::class, 'store']);
        Route::put('/usuarios/{id}', [AuthController::class, 'update']);
        Route::delete('/usuarios/{id}', [AuthController::class, 'destroy']);
    });
});
