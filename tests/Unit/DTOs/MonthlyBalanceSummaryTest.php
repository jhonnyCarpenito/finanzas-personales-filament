<?php

declare(strict_types=1);

namespace Tests\Unit\DTOs;

use App\DTOs\MonthlyBalanceSummary;
use Tests\TestCase;

final class MonthlyBalanceSummaryTest extends TestCase
{
    public function test_net_balance_is_income_minus_expense(): void
    {
        $summary = new MonthlyBalanceSummary(
            yearMonth: '2026-03',
            monthLabel: 'Marzo 2026',
            totalIncome: 1000.0,
            totalExpense: 350.0,
        );

        $this->assertSame(650.0, $summary->netBalance());
    }
}
