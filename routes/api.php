<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;

// =========================================================================
// RUTAS PÚBLICAS
// =========================================================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/experiencias', [ArticleController::class, 'index']);
Route::get('/experiencias/{id}', [ArticleController::class, 'show']);
Route::get('/experiencias/{experience_id}/resenas', [ReviewController::class, 'index']);
Route::get('/experiencias/{experience_id}/horarios', [BookingController::class, 'getSchedules']);

// =========================================================================
// PANEL ADMINISTRATIVO WEB (Forzamos autenticación por Sesión Web)
// =========================================================================
Route::middleware(['web', 'auth:web'])->group(function () {
    
    // Control de Reservaciones
    Route::get('/admin/orders', [OrderController::class, 'index']);
    Route::put('/admin/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/admin/orders/{id}', [OrderController::class, 'destroy'])->name('reservaciones.destroy');

    // Módulos CRUD de Admin
    Route::resource('admin/articles', ArticleController::class);
    Route::resource('admin/categories', CategoryController::class);
    Route::resource('admin/brands', BrandController::class);
    Route::resource('admin/reviews', ReviewController::class);
});

// =========================================================================
// RUTAS API (Usa autenticación por Token 'auth:sanctum')
// =========================================================================
Route::middleware('auth:sanctum')->group(function () {

    // --- TURISTA ---
    Route::middleware('role:tourist')->group(function () {
        Route::post('/carrito-comprar', [BookingController::class, 'store']);
        Route::get('/mis-reservas', [BookingController::class, 'myBookings']);
        Route::post('/experiencias/{experience_id}/resenas', [ReviewController::class, 'store']);
        Route::put('/resenas/{id}', [ReviewController::class, 'update']);
    });

    // --- PRESTADOR ---
    Route::middleware('role:provider')->group(function () {
        Route::post('/experiencias', [ArticleController::class, 'store']);
        Route::get('/mis-experiencias', [ArticleController::class, 'myExperiences']);
    });

    // --- ADMIN API ---
    Route::middleware('role:admin')->group(function () {
        Route::get('/usuarios', [AuthController::class, 'index']);
        Route::get('/usuarios/{id}', [AuthController::class, 'show']);
        Route::post('/usuarios', [AuthController::class, 'store']);
        Route::put('/usuarios/{id}', [AuthController::class, 'update']);
        Route::delete('/usuarios/{id}', [AuthController::class, 'destroy']);
    });
});