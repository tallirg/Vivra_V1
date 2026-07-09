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
        'person_id',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // LA REGLA DE ORO PARA LAS CONTRASEÑAS
    protected $casts = [
        'password' => 'hashed',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
