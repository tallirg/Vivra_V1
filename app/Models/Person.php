<?php

namespace App\Models;

// CAMBIAMOS LA IMPORTACIÓN AQUÍ:
// Usamos el modelo de MongoDB en lugar del de Illuminate
use MongoDB\Laravel\Eloquent\Model;

class Person extends Model
{
    // Nombre de la colección en MongoDB
    protected $collection = 'people'; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol'
    ];
}
