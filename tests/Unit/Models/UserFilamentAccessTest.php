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

    public function test_can_access_panel_when_not_blocked(): void
    {
        $user = User::factory()->create(['blocked_at' => null]);
        $panel = Filament::getPanel('admin');

        $this->assertTrue($user->canAccessPanel($panel));
    }

    public function test_cannot_access_panel_when_blocked(): void
    {
        $user = User::factory()->create(['blocked_at' => now()]);
        $panel = Filament::getPanel('admin');

        $this->assertFalse($user->canAccessPanel($panel));
    }
}
