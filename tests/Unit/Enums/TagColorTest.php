<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\TagColor;
use PHPUnit\Framework\TestCase;

class TagColorTest extends TestCase
{
    public function test_labels_are_correct(): void
    {
        $this->assertSame('Verde', TagColor::Success->getLabel());
        $this->assertSame('Rojo', TagColor::Danger->getLabel());
        $this->assertSame('Amarillo', TagColor::Warning->getLabel());
        $this->assertSame('Azul', TagColor::Info->getLabel());
        $this->assertSame('Gris', TagColor::Gray->getLabel());
        $this->assertSame('Ámbar', TagColor::Primary->getLabel());
        $this->assertSame('Púrpura', TagColor::Purple->getLabel());
        $this->assertSame('Violeta', TagColor::Violet->getLabel());
        $this->assertSame('Verde azulado', TagColor::Teal->getLabel());
        $this->assertSame('Cian', TagColor::Cyan->getLabel());
        $this->assertSame('Naranja', TagColor::Orange->getLabel());
        $this->assertSame('Rosa', TagColor::Rose->getLabel());
        $this->assertSame('Fucsia', TagColor::Fuchsia->getLabel());
        $this->assertSame('Rosa fuerte', TagColor::Pink->getLabel());
    }

    public function test_color_matches_value(): void
    {
        foreach (TagColor::cases() as $case) {
            $this->assertSame($case->value, $case->getColor());
        }
    }

    public function test_options_returns_all_cases(): void
    {
        $options = TagColor::options();

        $this->assertSame([
            'success' => 'Verde',
            'danger' => 'Rojo',
            'warning' => 'Amarillo',
            'info' => 'Azul',
            'gray' => 'Gris',
            'primary' => 'Ámbar',
            'purple' => 'Púrpura',
            'violet' => 'Violeta',
            'teal' => 'Verde azulado',
            'cyan' => 'Cian',
            'orange' => 'Naranja',
            'rose' => 'Rosa',
            'fuchsia' => 'Fucsia',
            'pink' => 'Rosa fuerte',
        ], $options);
    }
}
