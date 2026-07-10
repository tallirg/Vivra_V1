<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;

// --- RUTAS PÚBLICAS (No requieren Token) ---
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/experiencias', [ExperienceController::class, 'index']);
Route::get('/experiencias/{id}', [ExperienceController::class, 'show']);
Route::get('/experiencias/{experience_id}/resenas', [ReviewController::class, 'index']);


// --- RUTAS PROTEGIDAS (Requieren Token y Roles Específicos) ---
Route::middleware('auth:sanctum')->group(function () {

    // 'admin' y 'prestador' pueden gestionar experiencias
    Route::middleware('role:admin,prestador')->group(function () {
        Route::post('/experiencias', [ExperienceController::class, 'store']);
        Route::put('/experiencias/{id}', [ExperienceController::class, 'update']);
        Route::delete('/experiencias/{id}', [ExperienceController::class, 'destroy']); // 👈 MOVIDO AQUÍ (Se valida dueño en Controlador)
        Route::get('/mis-experiencias', [ExperienceController::class, 'myExperiences']); // 👈 NUEVO: Ver solo las suyas
    });

    // Solo el 'admin' puede hacer estas acciones
    Route::middleware('role:admin')->group(function () {
        Route::get('/usuarios', [AuthController::class, 'index']);
	Route::get('/usuarios/{id}', [AuthController::class, 'show']);
        Route::put('/usuarios/{id}', [AuthController::class, 'update']); // 👈 NUEVO: Editar Usuario
        Route::delete('/usuarios/{id}', [AuthController::class, 'destroy']); // 👈 NUEVO: Borrar Usuario
	Route::post('/usuarios', [AuthController::class, 'store']);
        Route::delete('/resenas/{id}', [ReviewController::class, 'destroy']); // Admin borra CUALQUIER reseña
    });

    // Solo el 'turista' puede hacer estas acciones
    Route::middleware('role:turista')->group(function () {
        Route::post('/carrito-comprar', [App\Http\Controllers\BookingController::class, 'store']);
        Route::post('/experiencias/{experience_id}/resenas', [ReviewController::class, 'store']);
        Route::put('/resenas/{id}', [ReviewController::class, 'update']);
        Route::delete('/resenas/{id}', [ReviewController::class, 'destroyOwn']); // 👈 NUEVO: Turista borra su PROPIA reseña
    });

    // Ruta de debug token
    Route::get('/debug-token', function (Illuminate\Http\Request $request) {
        $token = $request->bearerToken();
        if (!$token) return response()->json(['error' => 'No enviaste el Bearer Token en Postman']);

        $accessToken = \App\Models\PersonalAccessToken::findToken($token);
        if (!$accessToken) return response()->json(['error' => 'El token no existe en Mongo o el ID no coincide']);

        $isExpired = false;
        $expiration = config('sanctum.expiration');
        if ($expiration) {
            $isExpired = $accessToken->created_at->lte(now()->subMinutes($expiration));
        }

        return response()->json([
            '1_token_encontrado' => true,
            '2_fecha_creacion' => $accessToken->created_at,
            '3_fecha_actual_servidor' => now(),
            '4_esta_expirado' => $isExpired,
            '5_usuario_encontrado' => $accessToken->tokenable ? $accessToken->tokenable->name : 'NULO - Falla la relación'
        ]);
    });
});
