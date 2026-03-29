<?php

declare(strict_types=1);

namespace App\Filament\Resources\TransactionResource\Pages;

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
     */
    public function sumSelectedTransactions(array $ids): string
    {
        $ids = array_values(array_unique(array_filter(
            array_map(strval(...), $ids),
            fn (string $id): bool => $id !== '',
        )));

        if ($ids === []) {
            return number_format(0.0, 2);
        }

        $sum = Transaction::query()
            ->where('user_id', auth()->id())
            ->whereKey($ids)
            ->sum('amount');

        return number_format((float) $sum, 2);
    }
}
