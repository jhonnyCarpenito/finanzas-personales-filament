<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
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
        FilamentColor::register([
            'purple' => Color::Purple,
            'violet' => Color::Violet,
            'teal' => Color::Teal,
            'cyan' => Color::Cyan,
            'orange' => Color::Orange,
            'rose' => Color::Rose,
            'fuchsia' => Color::Fuchsia,
            'pink' => Color::Pink,
        ]);

        // Detrás de proxy HTTPS (ej. CapRover), forzar que todas las URLs generadas usen https
        // para que los assets (CSS/JS) carguen y no haya contenido mixto bloqueado.
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
