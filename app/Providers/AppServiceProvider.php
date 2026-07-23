<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum; // <- Importamos Sanctum
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Le decimos a Sanctum que use el modelo personalizado que acabamos de crear
        Sanctum::usePersonalAccessTokenModel(\App\Models\PersonalAccessToken::class);

        if ($this->app->environment('production') || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}
