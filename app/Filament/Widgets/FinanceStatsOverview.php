<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();
        $isAdmin = auth()->user()->is_admin;

        // Base query
        $query = Transaction::query();
        if (! $isAdmin) {
            $query->where('user_id', $userId);
        }

        // Saldo Total
        $totalIncome = (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $query)->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Ingresos del Mes
        $monthlyIncome = (clone $query)
            ->where('type', 'income')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        // Gastos del Mes
        $monthlyExpense = (clone $query)
            ->where('type', 'expense')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        return [
            Stat::make('Saldo Total', '$'.number_format((float) $balance, 2))
                ->description('Total de ingresos menos egresos')
                ->color($balance >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Ingresos del Mes', '$'.number_format((float) $monthlyIncome, 2))
                ->description(now()->format('F Y'))
                ->color('success')
                ->icon('heroicon-o-arrow-trending-up'),
            Stat::make('Gastos del Mes', '$'.number_format((float) $monthlyExpense, 2))
                ->description(now()->format('F Y'))
                ->color('danger')
                ->icon('heroicon-o-arrow-trending-down'),
        ];
    }
}
