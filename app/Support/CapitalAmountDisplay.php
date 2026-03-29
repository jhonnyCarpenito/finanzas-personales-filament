<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Session;

final class CapitalAmountDisplay
{
    public const SESSION_KEY = 'capital_total_amount_visible';

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
        $visible = Session::get(self::SESSION_KEY, true);

        return self::format($amount, (bool) $visible);
    }
}
