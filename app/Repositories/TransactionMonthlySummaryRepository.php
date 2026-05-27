<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class TransactionMonthlySummaryRepository
{
    /**
     * @return Collection<int, object{year_month: string, total_income: string|float|null, total_expense: string|float|null}>
     */
    public function forUserAndYear(int $userId, int $year): Collection
    {
        $start = Carbon::create($year, 1, 1)->startOfYear();
        $end = Carbon::create($year, 12, 31)->endOfYear();
        $yearMonthExpression = $this->yearMonthExpression();

        return Transaction::query()
            ->where('user_id', $userId)
            ->whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<=', $end->toDateString())
            ->selectRaw("{$yearMonthExpression} as year_month")
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense")
            ->groupByRaw($yearMonthExpression)
            ->orderByDesc('year_month')
            ->get();
    }

    public function findEarliestTransactionYear(int $userId): ?int
    {
        $minDate = Transaction::query()
            ->where('user_id', $userId)
            ->min('date');

        if ($minDate === null) {
            return null;
        }

        return (int) Carbon::parse((string) $minDate)->year;
    }

    private function yearMonthExpression(): string
    {
        if (DB::getDriverName() === 'sqlite') {
            return "strftime('%Y-%m', date)";
        }

        return "DATE_FORMAT(date, '%Y-%m')";
    }
}
