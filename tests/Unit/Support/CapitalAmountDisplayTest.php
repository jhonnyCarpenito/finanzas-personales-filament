<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\CapitalAmountDisplay;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

final class CapitalAmountDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_format_shows_currency_when_visible(): void
    {
        $this->assertSame('$1,234.56', CapitalAmountDisplay::format(1234.56, true));
    }

    public function test_format_masks_when_not_visible(): void
    {
        $masked = CapitalAmountDisplay::format(1234.56, false);

        $this->assertStringNotContainsString('1', $masked);
        $this->assertStringNotContainsString('2', $masked);
        $this->assertSame(strlen('$1,234.56'), strlen($masked));
    }

    public function test_format_uses_minimum_mask_length_for_small_amounts(): void
    {
        $masked = CapitalAmountDisplay::format(0.01, false);

        $this->assertSame(8, strlen($masked));
        $this->assertSame('********', $masked);
    }

    public function test_format_using_session_reflects_session_flag(): void
    {
        Session::put(CapitalAmountDisplay::SESSION_KEY, true);

        $this->assertSame('$100.00', CapitalAmountDisplay::formatUsingSession(100.0));

        Session::put(CapitalAmountDisplay::SESSION_KEY, false);

        $this->assertStringNotContainsString('100', CapitalAmountDisplay::formatUsingSession(100.0));
    }
}
