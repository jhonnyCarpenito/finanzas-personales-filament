<?php

declare(strict_types=1);

namespace App\Filament\Resources\FundOriginResource\Pages;

use App\Filament\Resources\FundOriginResource;
use App\Filament\Widgets\CapitalTotalWidget;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\On;

class ManageFundOrigins extends ManageRecords
{
    protected static string $resource = FundOriginResource::class;

    protected static string $view = 'filament.resources.fund-origin-resource.pages.manage-fund-origins';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    #[On(CapitalTotalWidget::AMOUNT_VISIBILITY_CHANGED_EVENT)]
    public function refreshTableAfterCapitalVisibilityToggle(): void {}
}
