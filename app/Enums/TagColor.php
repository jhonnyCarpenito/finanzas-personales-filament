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
    case Primary = 'primary';
    case Purple = 'purple';
    case Violet = 'violet';
    case Teal = 'teal';
    case Cyan = 'cyan';
    case Orange = 'orange';
    case Rose = 'rose';
    case Fuchsia = 'fuchsia';
    case Pink = 'pink';

    public function getLabel(): string
    {
        return match ($this) {
            self::Success => 'Verde',
            self::Danger => 'Rojo',
            self::Warning => 'Amarillo',
            self::Info => 'Azul',
            self::Gray => 'Gris',
            self::Primary => 'Ámbar',
            self::Purple => 'Púrpura',
            self::Violet => 'Violeta',
            self::Teal => 'Verde azulado',
            self::Cyan => 'Cian',
            self::Orange => 'Naranja',
            self::Rose => 'Rosa',
            self::Fuchsia => 'Fucsia',
            self::Pink => 'Rosa fuerte',
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
