<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'stock', 
        'category_id', 
        'brand_id', 
        'active',
        'location',
        'duration_minutes',
        'included_persons',
        'extra_person_price'
        ];
    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Agregar esta relación
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function schedules()
    {
        return $this->hasMany(ArticleSchedule::class, 'article_id');
    }

        public function register(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'required|string'
            ]);

            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                // Es muy importante encriptar la contraseña
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Creamos el token para la app móvil
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Usuario registrado con éxito',
                'user' => $user,
                'token' => $token,
                'role' => $user->role
            ], 201);
        }

}