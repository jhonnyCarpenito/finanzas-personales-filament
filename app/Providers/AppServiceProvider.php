<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Resources\TransactionResource\Pages\ListTransactions;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\CreateAction as TableCreateAction;
use Filament\Tables\View\TablesRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureFilamentIconButtonActionsWithTooltips();

        FilamentColor::register([
            'purple' => Color::Purple,
            'violet' => Color::Violet,
            'teal' => Color::Teal,
            'cyan' => Color::Cyan,
            'orange' => Color::Orange,
            'rose' => Color::Rose,
            'fuchsia' => Color::Fuchsia,
            'pink' => Color::Pink,
        ]);

        // Detrás de proxy HTTPS (ej. CapRover), forzar que todas las URLs generadas usen https
        // para que los assets (CSS/JS) carguen y no haya contenido mixto bloqueado.
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        FilamentView::registerRenderHook(
            TablesRenderHook::TOOLBAR_GROUPING_SELECTOR_BEFORE,
            function (): HtmlString {
                $livewire = Livewire::current();

                if (! $livewire instanceof ListTransactions) {
                    return new HtmlString('');
                }

                return new HtmlString(view('filament.hooks.transaction-table-selected-total', [
                    'livewireId' => $livewire->getId(),
                ])->render());
            },
        );
    }

    private function configureFilamentIconButtonActionsWithTooltips(): void
    {
        $configureIconButtonWithTooltip = function (TableAction|BulkAction $action): void {
            $action->iconButton();
            $action->tooltip(function (TableAction|BulkAction $action): ?string {
                $label = $action->getLabel();

                if ($label instanceof Htmlable) {
                    return strip_tags($label->toHtml());
                }

                return is_string($label) ? $label : null;
            });
        };

        TableAction::configureUsing(function (TableAction $action) use ($configureIconButtonWithTooltip): void {
            if ($action instanceof TableCreateAction) {
                return;
            }

            $configureIconButtonWithTooltip($action);
        });

        BulkAction::configureUsing($configureIconButtonWithTooltip);
    }
}
