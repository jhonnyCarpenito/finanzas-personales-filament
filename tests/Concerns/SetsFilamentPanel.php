<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Filament\Facades\Filament;

trait SetsFilamentPanel
{
    protected function setFilamentPanel(string $id): void
    {
        Filament::setCurrentPanel(Filament::getPanel($id));
    }
}
