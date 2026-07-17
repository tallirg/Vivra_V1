<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'experience_id',
        'quantity',
        'total_price',
        'status',
        'payment_method',
        'notes',
        'order_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
}$_COOKIE