<?php

declare(strict_types=1);

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
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
}
