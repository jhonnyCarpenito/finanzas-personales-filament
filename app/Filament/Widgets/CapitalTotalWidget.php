<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\FundOrigin;
use App\Support\CapitalAmountDisplay;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CapitalTotalWidget extends Widget
{
    public const AMOUNT_VISIBILITY_CHANGED_EVENT = 'capital-total-visibility-changed';

    protected static ?int $sort = 11;

    protected int|string|array $columnSpan = 1;

    protected static string $view = 'filament.widgets.capital-total-widget';

    public function mount(): void
    {
        if (! Session::has(CapitalAmountDisplay::SESSION_KEY)) {
            Session::put(CapitalAmountDisplay::SESSION_KEY, true);
        }
    }

    public static function canView(): bool
    {
        return Auth::check() && ! Auth::user()->is_admin;
    }

    public function toggleAmountVisibility(): void
    {
        $visible = Session::get(CapitalAmountDisplay::SESSION_KEY, true);
        Session::put(CapitalAmountDisplay::SESSION_KEY, ! $visible);

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
        return (bool) Session::get(CapitalAmountDisplay::SESSION_KEY, true);
    }

    public function getDisplayValue(): string
    {
        return CapitalAmountDisplay::format($this->getTotal(), $this->isAmountVisible());
    }
}
