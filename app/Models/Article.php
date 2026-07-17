<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id', 'brand_id', 'active'];
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
}