<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\FundOrigin;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceStatsOverview extends BaseWidget
{
    protected function getColumns(): int
    {
        return 4;
    }

    public static function canView(): bool
    {
        return Auth::check() && ! Auth::user()->is_admin;
    }

    protected function getStats(): array
    {
        $userId = Auth::id();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $isSqlite = DB::getDriverName() === 'sqlite';
        $monthFn = $isSqlite ? "CAST(strftime('%m', date) AS INTEGER)" : 'MONTH(date)';
        $yearFn = $isSqlite ? "CAST(strftime('%Y', date) AS INTEGER)" : 'YEAR(date)';

        $result = Transaction::query()
            ->where('user_id', $userId)
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense")
            ->selectRaw("SUM(CASE WHEN type = 'income' AND {$monthFn} = ? AND {$yearFn} = ? THEN amount ELSE 0 END) as monthly_income", [$currentMonth, $currentYear])
            ->selectRaw("SUM(CASE WHEN type = 'expense' AND {$monthFn} = ? AND {$yearFn} = ? THEN amount ELSE 0 END) as monthly_expense", [$currentMonth, $currentYear])
            ->first();

        $balance = (float) ($result->total_income ?? 0) - (float) ($result->total_expense ?? 0);
        $monthlyIncome = (float) ($result->monthly_income ?? 0);
        $monthlyExpense = (float) ($result->monthly_expense ?? 0);
        $capitalTotal = (float) FundOrigin::query()
            ->where('user_id', $userId)
            ->sum('amount');

        return [
            Stat::make('Saldo Total', '$' . number_format($balance, 2))
                ->description('Total de ingresos menos egresos')
                ->color($balance >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Ingresos del Mes', '$' . number_format($monthlyIncome, 2))
                ->description(now()->format('F Y'))
                ->color('success')
                ->icon('heroicon-o-arrow-trending-up'),
            Stat::make('Gastos del Mes', '$' . number_format($monthlyExpense, 2))
                ->description(now()->format('F Y'))
                ->color('danger')
                ->icon('heroicon-o-arrow-trending-down'),
            Stat::make('Capital Total', '$' . number_format($capitalTotal, 2))
                ->description('Suma de orígenes de fondos')
                ->color($capitalTotal >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
