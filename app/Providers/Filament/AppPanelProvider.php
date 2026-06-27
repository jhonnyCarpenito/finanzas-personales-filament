<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Pages\MonthlyBalanceHistoryPage;
use App\Filament\Resources\FundOriginResource;
use App\Filament\Resources\TagResource;
use App\Filament\Resources\TransactionResource;
use App\Filament\Widgets\CapitalPieChartWidget;
use App\Filament\Widgets\CapitalTotalWidget;
use App\Filament\Widgets\CapitalTrendChartWidget;
use App\Filament\Widgets\FinanceStatsOverview;
use App\Filament\Widgets\IncomeExpenseChart;
use App\Filament\Widgets\TransactionTagAmountsChartWidget;
use App\Providers\Filament\Concerns\ConfiguresFilamentPanels;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;

final class AppPanelProvider extends PanelProvider
{
    use ConfiguresFilamentPanels;

    public function panel(Panel $panel): Panel
    {
        return $this->configureShared($panel)
            ->default()
            ->id('app')
            ->path('app')
            ->brandName('Finanzas personales')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([
                TransactionResource::class,
                FundOriginResource::class,
                TagResource::class,
            ])
            ->pages([
                Pages\Dashboard::class,
                MonthlyBalanceHistoryPage::class,
            ])
            ->widgets([
                FinanceStatsOverview::class,
                IncomeExpenseChart::class,
                CapitalTotalWidget::class,
                CapitalPieChartWidget::class,
                CapitalTrendChartWidget::class,
                Widgets\AccountWidget::class,
            ])
            ->livewireComponents([
                TransactionTagAmountsChartWidget::class,
            ]);
    }
}
