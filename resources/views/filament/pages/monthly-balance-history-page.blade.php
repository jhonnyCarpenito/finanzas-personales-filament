<x-filament-panels::page>
    <div class="flex flex-col gap-y-6">
        <form wire:submit.prevent="">
            {{ $this->filtersForm }}
        </form>

        {{ $this->table }}
    </div>
</x-filament-panels::page>
