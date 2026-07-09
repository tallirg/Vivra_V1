<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumToken;
use MongoDB\Laravel\Eloquent\DocumentModel;

class PersonalAccessToken extends SanctumToken
{
    use DocumentModel;

    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';
    protected $primaryKey = '_id';

    // ¡Sobreescribimos la función original de Sanctum para que entienda MongoDB!
    public static function findToken($token)
    {
        // Si el token no tiene el símbolo "|", lo buscamos directo por el hash
        if (strpos($token, '|') === false) {
            return static::where('token', hash('sha256', $token))->first();
        }

        // Si sí lo tiene, lo separamos
        [$id, $token] = explode('|', $token, 2);

        // AQUÍ ESTÁ LA MAGIA: Usamos where() como texto en lugar de find() como número
        if ($instance = static::where('_id', $id)->first()) {
            return hash_equals($instance->token, hash('sha256', $token)) ? $instance : null;
        }
    }
}
