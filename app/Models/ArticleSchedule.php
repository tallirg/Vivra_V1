<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'day_of_week',
        'start_time',
        'stock'
    ];

    // Relación: Un horario pertenece a un artículo (experiencia)
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}