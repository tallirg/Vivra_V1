<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Registrar un nuevo usuario
    public function register(Request $request)
    {
        // 1. Validación estricta
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
        ]);

        // 2. Creamos el registro en la tabla People
        $person = Person::create([
            'name' => $request->name,
        ]);

        // 3. Creamos el registro vinculado en la tabla Users
        $user = User::create([
            'person_id' => $person->_id,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role ?? 'turista' 
        ]);

        return response()->json(['mensaje' => 'Usuario registrado con éxito'], 201);
    }

    // Iniciar sesión y generar Token
    public function login(Request $request)
    {
        // Validación para asegurar que siempre envíen email y password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        // Generamos el token de Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'mensaje' => 'Bienvenido',
            'token' => $token,
            'role' => $user->role
        ], 200);
    }
public function index()
    {
        // Trae todos los usuarios de la colección 'users'
        $users = User::all();
	return response()->json($users, 200);
    }
}
