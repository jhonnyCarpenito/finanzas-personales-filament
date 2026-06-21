<?php

declare(strict_types=1);

namespace Tests\Unit\Providers;

use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Tests\TestCase;

final class FilamentPanelProvidersTest extends TestCase
{
    public function test_app_panel_is_default_and_has_collapsible_sidebar(): void
    {
        $panel = Filament::getPanel('app');

        $this->assertTrue($panel->isDefault());
        $this->assertSame('app', $panel->getId());
        $this->assertSame('app', $panel->getPath());
        $this->assertTrue($panel->isSidebarCollapsibleOnDesktop());
    }

    public function test_app_panel_uses_amber_primary_color(): void
    {
        $panel = Filament::getPanel('app');

        $this->assertSame(Color::Amber, $panel->getColors()['primary']);
    }

    public function test_admin_panel_is_not_default_and_has_collapsible_sidebar(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertFalse($panel->isDefault());
        $this->assertSame('admin', $panel->getId());
        $this->assertSame('admin', $panel->getPath());
        $this->assertTrue($panel->isSidebarCollapsibleOnDesktop());
    }

    public function test_admin_panel_uses_slate_primary_color(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertSame(Color::Slate, $panel->getColors()['primary']);
        $this->assertNotSame(
            Filament::getPanel('app')->getColors()['primary'],
            $panel->getColors()['primary'],
        );
    }
}
