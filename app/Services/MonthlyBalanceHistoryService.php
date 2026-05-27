<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\MonthlyBalanceSummary;
use App\Repositories\TransactionMonthlySummaryRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class MonthlyBalanceHistoryService
{
    public function __construct(
        private readonly TransactionMonthlySummaryRepository $transactionMonthlySummaryRepository,
    ) {}

    /**
     * @return Collection<int, MonthlyBalanceSummary>
     */
    public function getSummariesForUser(int $userId, int $year): Collection
    {
        return $this->transactionMonthlySummaryRepository
            ->forUserAndYear($userId, $year)
            ->map(function (object $row): MonthlyBalanceSummary {
                $yearMonth = (string) $row->year_month;

                return new MonthlyBalanceSummary(
                    yearMonth: $yearMonth,
                    monthLabel: $this->formatMonthLabel($yearMonth),
                    totalIncome: (float) ($row->total_income ?? 0),
                    totalExpense: (float) ($row->total_expense ?? 0),
                );
            });
    }

    /**
     * @return array<int, string>
     */
    public function getAvailableYearsForUser(int $userId): array
    {
        $currentYear = now()->year;
        $earliestYear = $this->transactionMonthlySummaryRepository
            ->findEarliestTransactionYear($userId) ?? $currentYear;

        $years = [];

        for ($year = $currentYear; $year >= $earliestYear; $year--) {
            $years[$year] = (string) $year;
        }

        return $years;
    }

    private function formatMonthLabel(string $yearMonth): string
    {
        return ucfirst(Carbon::createFromFormat('Y-m', $yearMonth)->locale('es')->translatedFormat('F Y'));
    }
}
