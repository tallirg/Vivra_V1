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

	// NUEVO: ADMIN edita cualquier usuario (Maneja la relación con Person y User en MongoDB)
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $request->validate([
            'email' => 'email|unique:users,email,' . $id . ',_id',
            'role' => 'string'
        ]);

        // Actualizar datos del usuario
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->update($request->only(['email', 'role']));

        // Actualizar nombre en la colección de personas asociada
        if ($request->has('name') && $user->person_id) {
            $person = Person::find($user->person_id);
            if ($person) {
                $person->update(['name' => $request->name]);
            }
        }

        return response()->json(['mensaje' => 'Usuario actualizado con éxito', 'usuario' => $user], 200);
    }

    // NUEVO: ADMIN elimina un usuario y su registro de persona vinculado
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Eliminar persona vinculada primero
        if ($user->person_id) {
            $person = Person::find($user->person_id);
            if ($person) {
                $person->delete();
            }
        }

        $user->delete();
        return response()->json(['mensaje' => 'Usuario y datos personales eliminados con éxito'], 200);
    }

// Método para que el Admin cree cualquier tipo de usuario de forma directa
public function store(Request $request)
{
    // 1. Validación (aquí sí podemos pedir que el rol sea obligatorio si deseas)
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string',
        'role' => 'required|string' // El admin decide qué rol ponerle
    ]);

    // 2. Crear registro en colección People
    $person = Person::create([
        'name' => $request->name,
    ]);

    // 3. Crear usuario vinculado
    $user = User::create([
        'person_id' => $person->_id,
        'email' => $request->email,
        'password' => Hash::make($request->password), 
        'role' => $request->role 
    ]);

    return response()->json([
        'mensaje' => 'Usuario creado exitosamente por el administrador',
        'usuario' => $user
    ], 201);
}

	// NUEVO: Permite al Admin ver el detalle de un usuario específico por ID
public function show($id)
{
    // Buscar al usuario por su ID de MongoDB
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

    // Opcional: Si el usuario tiene una persona vinculada, cargar sus datos (como el nombre)
    if ($user->person_id) {
        $user->persona = Person::find($user->person_id);
    }

    return response()->json($user, 200);
}

public function index()
    {
        // Trae todos los usuarios de la colección 'users'
        $users = User::all();
	return response()->json($users, 200);
    }
}
