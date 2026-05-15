<?php

declare(strict_types=1);

namespace Tests\Unit\Providers;

use Filament\Facades\Filament;
use Tests\TestCase;

final class AdminPanelProviderTest extends TestCase
{
    public function test_admin_panel_has_collapsible_sidebar_on_desktop(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertTrue($panel->isSidebarCollapsibleOnDesktop());
    }
}
