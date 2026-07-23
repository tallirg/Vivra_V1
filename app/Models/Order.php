<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Liberamos las columnas para poder guardar la información
    protected $fillable = [
        'user_id', 
        'experience_id', 
        'quantity', 
        'total_price', 
        'status', 
        'payment_method', 
        'notes', 
        'order_date'
    ];

    // Relación con el artículo (experiencia)
    public function experience()
    {
        return $this->belongsTo(Article::class, 'experience_id', 'id');
    }
}