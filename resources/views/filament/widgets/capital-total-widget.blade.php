<x-filament-widgets::widget class="fi-wi-stats-overview">
    <div
        class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
    >
        <div class="grid gap-y-2">
            <div class="flex items-center justify-between gap-x-2">
                <div class="flex items-center gap-x-2">
                    <x-filament::icon
                        icon="heroicon-o-banknotes"
                        class="fi-wi-stats-overview-stat-icon h-5 w-5 text-gray-400 dark:text-gray-500"
                    />
                    <span
                        class="fi-wi-stats-overview-stat-label text-sm font-medium text-gray-500 dark:text-gray-400"
                    >
                        Capital Total
                    </span>
                </div>
                <button
                    type="button"
                    wire:click="toggleAmountVisibility"
                    class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                    title="{{ $this->isAmountVisible() ? 'Ocultar monto' : 'Mostrar monto' }}"
                >
                    @if ($this->isAmountVisible())
                        <x-filament::icon icon="heroicon-o-eye-slash" class="h-5 w-5" />
                    @else
                        <x-filament::icon icon="heroicon-o-eye" class="h-5 w-5" />
                    @endif
                </button>
            </div>

            <div
                @class([
                    'fi-wi-stats-overview-stat-value text-3xl font-semibold tracking-tight',
                    'text-green-600 dark:text-green-400' => $this->isAmountVisible(),
                    'text-gray-950 dark:text-white' => ! $this->isAmountVisible(),
                ])
            >
                {{ $this->getDisplayValue() }}
            </div>

            <span class="fi-wi-stats-overview-stat-description text-sm text-gray-500 dark:text-gray-400">
                Suma de todos los orígenes de fondos
            </span>
        </div>
    </div>
</x-filament-widgets::widget>
