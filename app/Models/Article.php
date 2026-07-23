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
        return $this->hasMany(ArticleSchedule::class);
    }
}