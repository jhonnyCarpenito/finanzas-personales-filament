<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IncomeExpenseChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos vs Egresos (Año Actual)';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $userId = auth()->id();
        $isAdmin = auth()->user()->is_admin;
        $currentYear = now()->year;

        // Base query
        $query = Transaction::query()
            ->whereYear('date', $currentYear);

        if (! $isAdmin) {
            $query->where('user_id', $userId);
        }

        // Agrupar por mes - Compatible con SQLite
        $monthlyIncome = (clone $query)
            ->where('type', 'income')
            ->select(
                DB::raw("CAST(strftime('%m', date) AS INTEGER) as month"),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyExpense = (clone $query)
            ->where('type', 'expense')
            ->select(
                DB::raw("CAST(strftime('%m', date) AS INTEGER) as month"),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Inicializar arrays con 12 meses
        $months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $incomeData = [];
        $expenseData = [];

        for ($i = 1; $i <= 12; $i++) {
            $incomeData[] = $monthlyIncome[$i] ?? 0;
            $expenseData[] = $monthlyExpense[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => $incomeData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgb(34, 197, 94)',
                ],
                [
                    'label' => 'Egresos',
                    'data' => $expenseData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.5)',
                    'borderColor' => 'rgb(239, 68, 68)',
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
