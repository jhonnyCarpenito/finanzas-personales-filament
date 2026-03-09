<?php

declare(strict_types=1);

namespace App\Filament\Resources\FundOriginResource\Pages;

use App\Filament\Resources\FundOriginResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

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
}
