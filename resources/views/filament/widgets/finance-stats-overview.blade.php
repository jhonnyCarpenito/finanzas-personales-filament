@php
    $columns = $this->getColumns();
    $heading = $this->getHeading();
    $description = $this->getDescription();
    $hasHeading = filled($heading);
    $hasDescription = filled($description);
@endphp

<x-filament-widgets::widget class="fi-wi-stats-overview grid gap-y-4">
    <div
        @class([
            'fi-wi-stats-overview-header flex items-start gap-x-2',
            'justify-between' => $hasHeading || $hasDescription,
            'justify-end' => ! $hasHeading && ! $hasDescription,
        ])
    >
        @if ($hasHeading || $hasDescription)
            <div class="grid gap-y-1">
                @if ($hasHeading)
                    <h3
                        class="fi-wi-stats-overview-header-heading col-span-full text-base font-semibold leading-6 text-gray-950 dark:text-white"
                    >
                        {{ $heading }}
                    </h3>
                @endif

                @if ($hasDescription)
                    <p
                        class="fi-wi-stats-overview-header-description overflow-hidden break-words text-sm text-gray-500 dark:text-gray-400"
                    >
                        {{ $description }}
                    </p>
                @endif
            </div>
        @endif

        <button
            type="button"
            wire:click="toggleAmountVisibility"
            class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-300"
            title="{{ $this->isAmountVisible() ? 'Ocultar saldos' : 'Mostrar saldos' }}"
        >
            @if ($this->isAmountVisible())
                <x-filament::icon icon="heroicon-o-eye-slash" class="h-5 w-5" />
            @else
                <x-filament::icon icon="heroicon-o-eye" class="h-5 w-5" />
            @endif
        </button>
    </div>

    <div
        @if ($pollingInterval = $this->getPollingInterval())
            wire:poll.{{ $pollingInterval }}
        @endif
        @class([
            'fi-wi-stats-overview-stats-ctn grid gap-6',
            'md:grid-cols-1' => $columns === 1,
            'md:grid-cols-2' => $columns === 2,
            'md:grid-cols-3' => $columns === 3,
            'md:grid-cols-2 xl:grid-cols-4' => $columns === 4,
        ])
    >
        @foreach ($this->getCachedStats() as $stat)
            {{ $stat }}
        @endforeach
    </div>
</x-filament-widgets::widget>
