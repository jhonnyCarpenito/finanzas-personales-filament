<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Resources\TagResource;
use App\Filament\Resources\UserResource;
use App\Providers\Filament\Concerns\ConfiguresFilamentPanels;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;

final class AdminPanelProvider extends PanelProvider
{
    use ConfiguresFilamentPanels;

    public function panel(Panel $panel): Panel
    {
        return $this->configureShared($panel)
            ->id('admin')
            ->path('admin')
            ->brandName('Administración')
            ->colors([
                'primary' => Color::Slate,
            ])
            ->resources([
                UserResource::class,
                TagResource::class,
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
            ]);
    }
}
