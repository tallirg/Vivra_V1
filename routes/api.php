<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\AuthController;

// --- RUTAS PÚBLICAS (No requieren Token) ---
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Cualquier invitado (o turista) puede ver el catálogo de experiencias
Route::get('/experiencias', [ExperienceController::class, 'index']);
Route::get('/experiencias/{id}', [ExperienceController::class, 'show']);

// --- RUTAS PROTEGIDAS (Requieren Token y Roles Específicos) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Solo 'admin' y 'prestador' pueden CREAR o ACTUALIZAR experiencias
    Route::middleware('role:admin,prestador')->group(function () {
        Route::post('/experiencias', [ExperienceController::class, 'store']);
        Route::put('/experiencias/{id}', [ExperienceController::class, 'update']);
    });

    // Solo el 'admin' puede ELIMINAR experiencias del sistema
    Route::middleware('role:admin')->group(function () {
        Route::delete('/experiencias/{id}', [ExperienceController::class, 'destroy']);
    });

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
    // (Aquí agregaríamos después las rutas de "carrito" para el rol 'turista')
});
