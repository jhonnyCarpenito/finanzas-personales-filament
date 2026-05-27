<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class MonthlyBalanceSummary
{
    public function __construct(
        public string $yearMonth,
        public string $monthLabel,
        public float $totalIncome,
        public float $totalExpense,
    ) {}

    public function netBalance(): float
    {
        return $this->totalIncome - $this->totalExpense;
    }
}
