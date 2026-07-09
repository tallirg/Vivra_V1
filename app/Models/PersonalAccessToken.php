<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumToken;
use MongoDB\Laravel\Eloquent\DocumentModel;

class PersonalAccessToken extends SanctumToken
{
    use DocumentModel;

    protected $connection = 'mongodb';
    protected $table = 'personal_access_tokens'; // <- Obligatorio para Sanctum
    protected $primaryKey = '_id';
    protected $keyType = 'string'; // <- Esto era lo que faltaba y rompía todo
}
