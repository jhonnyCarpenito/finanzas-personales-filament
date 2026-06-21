<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Models\User;
use App\Support\FilamentPanelRoutes;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class FilamentPanelRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_route_for_regular_user_points_to_app_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertSame('filament.app.pages.dashboard', FilamentPanelRoutes::dashboardRoute($user));
    }

    public function test_dashboard_route_for_admin_points_to_admin_panel(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->assertSame('filament.admin.pages.dashboard', FilamentPanelRoutes::dashboardRoute($user));
    }

    public function test_login_route_for_regular_user_points_to_app_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertSame('filament.app.auth.login', FilamentPanelRoutes::loginRoute($user));
    }

    public function test_login_route_for_admin_points_to_admin_panel(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->assertSame('filament.admin.auth.login', FilamentPanelRoutes::loginRoute($user));
    }

    public function test_profile_route_uses_current_panel(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('app'));

        $this->assertSame('filament.app.auth.profile', FilamentPanelRoutes::profileRoute());

        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $this->assertSame('filament.admin.auth.profile', FilamentPanelRoutes::profileRoute());
    }

    public function test_login_route_for_current_panel_uses_app_panel_by_default(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $this->assertSame('filament.admin.auth.login', FilamentPanelRoutes::loginRouteForCurrentPanel());
    }
}
