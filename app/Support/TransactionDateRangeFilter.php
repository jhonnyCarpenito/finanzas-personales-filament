<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class TransactionDateRangeFilter
{
    public static function apply(Builder $query, Carbon $start, Carbon $end): void
    {
        $startDate = $start->toDateString();
        $endDate = $end->toDateString();

        if (DB::getDriverName() === 'sqlite') {
            $query
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate);

            return;
        }

        $query
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate);
    }

    public static function applyFromStrings(Builder $query, string $startDate, ?string $endDate = null): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $query->whereDate('date', '>=', $startDate);

            if ($endDate !== null) {
                $query->whereDate('date', '<=', $endDate);
            }

            return;
        }

        $query->where('date', '>=', $startDate);

        if ($endDate !== null) {
            $query->where('date', '<=', $endDate);
        }
    }

    public static function applyUpperBound(Builder $query, string $endDate): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $query->whereDate('date', '<=', $endDate);

            return;
        }

        $query->where('date', '<=', $endDate);
    }
}
