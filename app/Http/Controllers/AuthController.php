<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 🔑 Iniciar Sesión (Compatible con Web y Flutter API)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Detectamos si la petición es para la API
        $isApi = $request->wantsJson() || $request->segment(1) === 'api';

        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();

            if ($isApi) {
                $token = method_exists($user, 'createToken') 
                    ? $user->createToken('auth_token')->plainTextToken 
                    : 'token_session_' . $user->id;

                return response()->json([
                    'message' => '¡Bienvenido a Vivra!',
                    'token' => $token,
                    'role'  => $user->role, // 👈 ¡AQUÍ ESTÁ EL CAMBIO CLAVE!
                    'user'  => $user
                ], 200);
            }

            $request->session()->regenerate();
            return redirect('/admin/dashboard')->with('success', 'Sesión iniciada');
        }

        if ($isApi) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas',
        ])->onlyInput('email');
    }

    // 🟩 Registro de Usuarios para Flutter
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4',
            'role' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'turista',
        ]);

        $token = method_exists($user, 'createToken') 
            ? $user->createToken('auth_token')->plainTextToken 
            : 'token_session_' . $user->id;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'token' => $token,
            'role'  => $user->role, // 👈 También lo agregamos aquí por consistencia
            'user'  => $user
        ], 201);
    }

    // 🚪 Cerrar Sesión
    // 🚪 Cerrar Sesión (Diferencia entre Web y API)
        public function logout(Request $request)
        {
            // Si la petición viene de la API (/api/logout o petición AJAX de Flutter)
            if ($request->is('api/*') || $request->ajax()) {
                if (auth()->check() && method_exists(auth()->user(), 'tokens')) {
                    auth()->user()->tokens()->delete();
                }
                return response()->json(['message' => 'Sesión cerrada exitosamente']);
            }

            // Si viene del formulario Web (Navegador)
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login');
        }
}