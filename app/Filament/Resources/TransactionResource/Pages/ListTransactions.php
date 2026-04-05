<?php

declare(strict_types=1);

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Enums\TransactionType;
use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    public function mount(): void
    {
        parent::mount();

        // Por defecto, mostrar transacciones del mes actual.
        // Solo se aplica si el usuario entra sin filtros en la URL.
        if ($this->tableFilters === null) {
            $this->tableFilters = [
                'month' => [
                    'month' => now()->format('Y-m'),
                ],
            ];
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * @param  array<int|string>  $ids
     * @return array{total: string, income: string, expense: string}
     */
    public function sumSelectedTransactions(array $ids): array
    {
        $ids = array_values(array_unique(array_filter(
            array_map(strval(...), $ids),
            fn (string $id): bool => $id !== '',
        )));

        if ($ids === []) {
            return $this->formatSelectedSumRow(0.0, 0.0, 0.0);
        }

        /** @var Transaction|null $row */
        $row = Transaction::query()
            ->where('user_id', auth()->id())
            ->whereKey($ids)
            ->selectRaw(
                'COALESCE(SUM(amount), 0) as total_sum, '
                .'COALESCE(SUM(CASE WHEN type = ? THEN amount ELSE 0 END), 0) as income_sum, '
                .'COALESCE(SUM(CASE WHEN type = ? THEN amount ELSE 0 END), 0) as expense_sum',
                [TransactionType::Income->value, TransactionType::Expense->value],
            )
            ->first();

        if ($row === null) {
            return $this->formatSelectedSumRow(0.0, 0.0, 0.0);
        }

        return $this->formatSelectedSumRow(
            (float) $row->total_sum,
            (float) $row->income_sum,
            (float) $row->expense_sum,
        );
    }

    /**
     * @return array{total: string, income: string, expense: string}
     */
    private function formatSelectedSumRow(float $total, float $income, float $expense): array
    {
        return [
            'total' => number_format($total, 2),
            'income' => number_format($income, 2),
            'expense' => number_format($expense, 2),
        ];
    }
}
