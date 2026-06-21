<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Cache;

final class DashboardCache
{
    private const TTL_SECONDS = 300;

    /**
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    public static function rememberFinanceStats(int $userId, callable $callback): mixed
    {
        return Cache::remember(
            self::financeStatsKey($userId),
            self::TTL_SECONDS,
            $callback,
        );
    }

    /**
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    public static function rememberIncomeExpenseChart(int $userId, int $year, callable $callback): mixed
    {
        return Cache::remember(
            self::incomeExpenseChartKey($userId, $year),
            self::TTL_SECONDS,
            $callback,
        );
    }

    /**
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    public static function rememberCapitalPie(int $userId, callable $callback): mixed
    {
        return Cache::remember(
            self::capitalPieKey($userId),
            self::TTL_SECONDS,
            $callback,
        );
    }

    public static function forgetTransactionsForUser(int $userId): void
    {
        Cache::forget(self::financeStatsKey($userId));
        Cache::forget(self::incomeExpenseChartKey($userId, now()->year));
    }

    public static function forgetFundOriginsForUser(int $userId): void
    {
        Cache::forget(self::financeStatsKey($userId));
        Cache::forget(self::capitalPieKey($userId));
    }

    private static function financeStatsKey(int $userId): string
    {
        return "dashboard:{$userId}:finance-stats";
    }

    private static function incomeExpenseChartKey(int $userId, int $year): string
    {
        return "dashboard:{$userId}:income-expense:{$year}";
    }

    private static function capitalPieKey(int $userId): string
    {
        return "dashboard:{$userId}:capital-pie";
    }
}
