<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\FundOrigin;
use App\Support\CapitalAmountDisplay;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class CapitalTotalWidget extends Widget
{
    public const AMOUNT_VISIBILITY_CHANGED_EVENT = CapitalAmountDisplay::VISIBILITY_CHANGED_EVENT;

    public const FUND_ORIGINS_DATA_CHANGED_EVENT = 'fund-origins-data-changed';

    protected static ?int $sort = 11;

    protected int|string|array $columnSpan = 1;

    protected static string $view = 'filament.widgets.capital-total-widget';

    public function mount(): void
    {
        CapitalAmountDisplay::ensureDefaultVisibility();
    }

    #[On(self::FUND_ORIGINS_DATA_CHANGED_EVENT)]
    public function refreshAfterFundOriginsDataChanged(): void
    {
        // Livewire listener intentionally empty to trigger widget refresh.
    }

    public static function canView(): bool
    {
        return false;
    }

    public function toggleAmountVisibility(): void
    {
        CapitalAmountDisplay::toggle();

        $this->dispatch(self::AMOUNT_VISIBILITY_CHANGED_EVENT);
    }

    public function getTotal(): float
    {
        return (float) FundOrigin::query()
            ->where('user_id', Auth::id())
            ->sum('amount');
    }

    public function isAmountVisible(): bool
    {
        return CapitalAmountDisplay::isVisible();
    }

    public function getDisplayValue(): string
    {
        return CapitalAmountDisplay::format($this->getTotal(), $this->isAmountVisible());
    }
}
