<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Session;

final class CapitalAmountDisplay
{
    public const SESSION_KEY = 'capital_total_amount_visible';

    public const VISIBILITY_CHANGED_EVENT = 'capital-total-visibility-changed';

    public static function ensureDefaultVisibility(): void
    {
        if (! Session::has(self::SESSION_KEY)) {
            Session::put(self::SESSION_KEY, true);
        }
    }

    public static function isVisible(): bool
    {
        return (bool) Session::get(self::SESSION_KEY, true);
    }

    public static function toggle(): bool
    {
        $visible = ! self::isVisible();
        Session::put(self::SESSION_KEY, $visible);

        return $visible;
    }

    public static function format(float $amount, bool $visible): string
    {
        if ($visible) {
            return '$'.number_format($amount, 2);
        }

        $visibleLength = strlen('$'.number_format($amount, 2));

        return str_repeat('*', max(8, $visibleLength));
    }

    public static function formatUsingSession(float $amount): string
    {
        return self::format($amount, self::isVisible());
    }
}
