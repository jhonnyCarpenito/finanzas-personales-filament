<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncomeExpenseChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos vs Egresos (Año Actual)';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        // Los admins no deben ver métricas basadas en transacciones de usuarios.
        return Auth::check() && ! Auth::user()->is_admin;
    }

    protected function getData(): array
    {
        $userId = Auth::id();
        $currentYear = now()->year;

        $isSqlite = DB::getDriverName() === 'sqlite';
        $monthFn = $isSqlite ? "CAST(strftime('%m', date) AS INTEGER)" : 'MONTH(date)';

        $rows = Transaction::query()
            ->where('user_id', $userId)
            ->whereYear('date', $currentYear)
            ->selectRaw("{$monthFn} as month")
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense")
            ->groupByRaw("{$monthFn}")
            ->get()
            ->keyBy('month');

        $months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $incomeData = [];
        $expenseData = [];

        for ($i = 1; $i <= 12; $i++) {
            $row = $rows->get($i);
            $incomeData[] = (float) ($row->income ?? 0);
            $expenseData[] = (float) ($row->expense ?? 0);
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
