<?php

declare(strict_types=1);

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Enums\TransactionType;
use App\Filament\Resources\TransactionResource\Pages\ListTransactions;
use App\Support\CapitalAmountDisplay;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

final class TransactionFilterStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    /**
     * @var view-string
     */
    protected static string $view = 'filament.widgets.finance-stats-overview';

    protected static bool $isDiscovered = false;

    protected static ?string $pollingInterval = null;

    public function mount(): void
    {
        CapitalAmountDisplay::ensureDefaultVisibility();
    }

    protected function getColumns(): int
    {
        return 2;
    }

    public static function canView(): bool
    {
        return Auth::check() && ! Auth::user()->is_admin;
    }

    protected function getTablePage(): string
    {
        return ListTransactions::class;
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
        $totals = $this->queryFilteredTotals();

        return [
            Stat::make('Total Ingreso', CapitalAmountDisplay::formatUsingSession($totals['income']))
                ->description('Según filtros actuales')
                ->color('success')
                ->icon('heroicon-o-arrow-trending-up'),
            Stat::make('Total Egreso', CapitalAmountDisplay::formatUsingSession($totals['expense']))
                ->description('Según filtros actuales')
                ->color('danger')
                ->icon('heroicon-o-arrow-trending-down'),
        ];
    }

    /**
     * @return array{income: float, expense: float}
     */
    private function queryFilteredTotals(): array
    {
        $row = $this->getPageTableQuery()
            ->clone()
            ->reorder()
            ->toBase()
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN type = ? THEN amount ELSE 0 END), 0) as income_sum, '
                .'COALESCE(SUM(CASE WHEN type = ? THEN amount ELSE 0 END), 0) as expense_sum',
                [TransactionType::Income->value, TransactionType::Expense->value],
            )
            ->first();

        return [
            'income' => (float) ($row->income_sum ?? 0),
            'expense' => (float) ($row->expense_sum ?? 0),
        ];
    }
}
