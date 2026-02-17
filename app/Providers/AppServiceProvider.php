<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Detrás de proxy HTTPS (ej. CapRover), forzar que todas las URLs generadas usen https
        // para que los assets (CSS/JS) carguen y no haya contenido mixto bloqueado.
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
