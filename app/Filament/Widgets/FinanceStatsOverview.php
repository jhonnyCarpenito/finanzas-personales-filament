<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\FundOrigin;
use App\Models\Transaction;
use App\Support\CapitalAmountDisplay;
use App\Support\DashboardCache;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceStatsOverview extends BaseWidget
{
    /**
     * @var view-string
     */
    protected static string $view = 'filament.widgets.finance-stats-overview';

    public function mount(): void
    {
        CapitalAmountDisplay::ensureDefaultVisibility();
    }

    protected function getColumns(): int
    {
        return 4;
    }

    public static function canView(): bool
    {
        return Auth::check() && ! Auth::user()->is_admin;
    }

    public function toggleAmountVisibility(): void
    {
        CapitalAmountDisplay::toggle();
        $this->cachedStats = null;

        $this->dispatch(CapitalAmountDisplay::VISIBILITY_CHANGED_EVENT);
    }

    public function isAmountVisible(): bool
    {
        return CapitalAmountDisplay::isVisible();
    }

    protected function getStats(): array
    {
        $userId = (int) Auth::id();

        $metrics = DashboardCache::rememberFinanceStats($userId, fn (): array => $this->queryMetrics($userId));

        return $this->buildStatsFromMetrics($metrics);
    }

    /**
     * @return array{balance: float, monthlyIncome: float, monthlyExpense: float, capitalTotal: float, monthLabel: string}
     */
    private function queryMetrics(int $userId): array
    {
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
            'balance' => $balance,
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpense' => $monthlyExpense,
            'capitalTotal' => $capitalTotal,
            'monthLabel' => now()->format('F Y'),
        ];
    }

    /**
     * @param  array{balance: float, monthlyIncome: float, monthlyExpense: float, capitalTotal: float, monthLabel: string}  $metrics
     * @return array<int, Stat>
     */
    private function buildStatsFromMetrics(array $metrics): array
    {
        $balance = $metrics['balance'];
        $monthlyIncome = $metrics['monthlyIncome'];
        $monthlyExpense = $metrics['monthlyExpense'];
        $capitalTotal = $metrics['capitalTotal'];
        $monthLabel = $metrics['monthLabel'];

        return [
            Stat::make('Saldo Total', CapitalAmountDisplay::formatUsingSession($balance))
                ->description('Total de ingresos menos egresos')
                ->color($balance >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Ingresos del Mes', CapitalAmountDisplay::formatUsingSession($monthlyIncome))
                ->description($monthLabel)
                ->color('success')
                ->icon('heroicon-o-arrow-trending-up'),
            Stat::make('Gastos del Mes', CapitalAmountDisplay::formatUsingSession($monthlyExpense))
                ->description($monthLabel)
                ->color('danger')
                ->icon('heroicon-o-arrow-trending-down'),
            Stat::make('Capital Total', CapitalAmountDisplay::formatUsingSession($capitalTotal))
                ->description('Suma de orígenes de fondos')
                ->color($capitalTotal >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
