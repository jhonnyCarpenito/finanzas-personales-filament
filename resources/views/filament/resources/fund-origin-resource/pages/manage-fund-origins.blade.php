<x-filament-panels::page
    @class([
        'fi-resource-list-records-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>
    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes()) }}

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-start">
            <div class="sm:col-span-2 min-w-0">
                {{ $this->table }}
            </div>
            <div class="flex flex-col gap-6 min-w-0">
                @livewire(\App\Filament\Widgets\CapitalTotalWidget::class, $this->getWidgetData(), key('capital-total'))
                @livewire(\App\Filament\Widgets\CapitalPieChartWidget::class, $this->getWidgetData(), key('capital-pie-chart'))
            </div>
        </div>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>
</x-filament-panels::page>
