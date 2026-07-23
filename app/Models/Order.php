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
        'order_date',
        'booking_date',
        'schedule_id'
    ];

    // Relación con el Usuario (requerida por with('user'))
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el artículo (experiencia)
    public function experience()
    {
        return $this->belongsTo(Article::class, 'experience_id', 'id');
    }

    public function schedule()
    {
        return $this->belongsTo(ArticleSchedule::class, 'schedule_id', 'id');
    }
}