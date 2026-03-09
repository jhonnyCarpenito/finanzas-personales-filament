<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TagColor: string implements HasLabel, HasColor
{
    case Success = 'success';
    case Danger = 'danger';
    case Warning = 'warning';
    case Info = 'info';
    case Gray = 'gray';

    public function getLabel(): string
    {
        return match ($this) {
            self::Success => 'Verde',
            self::Danger => 'Rojo',
            self::Warning => 'Amarillo',
            self::Info => 'Azul',
            self::Gray => 'Gris',
        };
    }

    public function getColor(): string
    {
        return $this->value;
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->getLabel()])
            ->toArray();
    }
}
