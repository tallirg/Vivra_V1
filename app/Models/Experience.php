<?php

namespace App\Models;

// ¡OJO AQUÍ! Usamos el modelo de MongoDB
use MongoDB\Laravel\Eloquent\Model; 

class Experience extends Model
{
    // Le indicamos explícitamente a qué colección se conecta
    protected $collection = 'experiences';

    // Definimos qué campos se pueden llenar desde la API (POST/PUT)
    protected $fillable = [
        'prestador_id', 
        'categoria', 
        'titulo', 
        'descripcion', 
        'precio', 
        'ubicacion', 
        'status', 
        'imagenes' // Como es Mongo, esto guardará el arreglo de Cloudinary sin problema
    ];
public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
