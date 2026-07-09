<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $collection = 'users';
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Puede ser 'admin', 'prestador' o 'turista'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
