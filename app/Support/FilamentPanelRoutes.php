<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Panel;

final class FilamentPanelRoutes
{
    public static function panelIdForUser(User $user): string
    {
        return $user->is_admin ? 'admin' : 'app';
    }

    public static function panelForUser(User $user): Panel
    {
        return Filament::getPanel(self::panelIdForUser($user));
    }

    public static function dashboardRoute(User $user): string
    {
        return self::panelForUser($user)->generateRouteName('pages.dashboard');
    }

    public static function loginRoute(User $user): string
    {
        return self::panelForUser($user)->generateRouteName('auth.login');
    }

    public static function profileRoute(?Panel $panel = null): string
    {
        $panel ??= Filament::getCurrentPanel() ?? Filament::getDefaultPanel();

        return $panel->generateRouteName('auth.profile');
    }

    public static function loginRouteForCurrentPanel(): string
    {
        $panel = Filament::getCurrentPanel() ?? Filament::getDefaultPanel();

        return $panel->generateRouteName('auth.login');
    }
}
