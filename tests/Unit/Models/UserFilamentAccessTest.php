<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UserFilamentAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_can_access_app_panel_when_not_blocked(): void
    {
        $user = User::factory()->create(['is_admin' => false, 'blocked_at' => null]);
        $panel = Filament::getPanel('app');

        $this->assertTrue($user->canAccessPanel($panel));
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false, 'blocked_at' => null]);
        $panel = Filament::getPanel('admin');

        $this->assertFalse($user->canAccessPanel($panel));
    }

    public function test_admin_can_access_admin_panel_when_not_blocked(): void
    {
        $user = User::factory()->create(['is_admin' => true, 'blocked_at' => null]);
        $panel = Filament::getPanel('admin');

        $this->assertTrue($user->canAccessPanel($panel));
    }

    public function test_admin_cannot_access_app_panel(): void
    {
        $user = User::factory()->create(['is_admin' => true, 'blocked_at' => null]);
        $panel = Filament::getPanel('app');

        $this->assertFalse($user->canAccessPanel($panel));
    }

    public function test_blocked_user_cannot_access_any_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false, 'blocked_at' => now()]);

        $this->assertFalse($user->canAccessPanel(Filament::getPanel('app')));
        $this->assertFalse($user->canAccessPanel(Filament::getPanel('admin')));
    }
}
