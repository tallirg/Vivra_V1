<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificamos si el usuario está logueado y si su rol está en la lista permitida
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return response()->json([
                'error' => 'Acceso denegado. No tienes los permisos necesarios para esta acción.'
            ], 403);
        }

        return $next($request);
    }
}
