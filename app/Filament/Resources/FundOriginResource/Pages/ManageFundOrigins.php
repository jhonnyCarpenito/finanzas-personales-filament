<?php

declare(strict_types=1);

namespace App\Filament\Resources\FundOriginResource\Pages;

use App\Filament\Resources\FundOriginResource;
use App\Filament\Widgets\CapitalPieChartWidget;
use App\Filament\Widgets\CapitalTotalWidget;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
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

    public function reorderTable(array $order): void
    {
        parent::reorderTable($order);

        $this->notifyFundOriginWidgetsDataChanged();
    }

    protected function configureCreateAction(CreateAction|Tables\Actions\CreateAction $action): void
    {
        parent::configureCreateAction($action);

        $action->after(function (): void {
            $this->notifyFundOriginWidgetsDataChanged();
        });
    }

    protected function configureEditAction(EditAction $action): void
    {
        parent::configureEditAction($action);

        $action->after(function (): void {
            $this->notifyFundOriginWidgetsDataChanged();
        });
    }

    protected function configureDeleteAction(DeleteAction $action): void
    {
        parent::configureDeleteAction($action);

        $action->after(function (): void {
            $this->notifyFundOriginWidgetsDataChanged();
        });
    }

    protected function configureDeleteBulkAction(DeleteBulkAction $action): void
    {
        parent::configureDeleteBulkAction($action);

        $action->after(function (): void {
            $this->notifyFundOriginWidgetsDataChanged();
        });
    }

    private function notifyFundOriginWidgetsDataChanged(): void
    {
        $event = CapitalTotalWidget::FUND_ORIGINS_DATA_CHANGED_EVENT;

        $this->dispatch($event)->to(CapitalTotalWidget::class);
        $this->dispatch($event)->to(CapitalPieChartWidget::class);
    }
}
