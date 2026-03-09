<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\FundOrigin;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CapitalTotalWidget extends Widget
{
    protected static ?int $sort = 11;

    protected int|string|array $columnSpan = 1;

    protected static string $view = 'filament.widgets.capital-total-widget';

    public function mount(): void
    {
        if (! Session::has('capital_total_amount_visible')) {
            Session::put('capital_total_amount_visible', true);
        }
    }

    public static function canView(): bool
    {
        return Auth::check() && ! Auth::user()->is_admin;
    }

    public function toggleAmountVisibility(): void
    {
        $visible = Session::get('capital_total_amount_visible', true);
        Session::put('capital_total_amount_visible', ! $visible);
    }

    public function getTotal(): float
    {
        return (float) FundOrigin::query()
            ->where('user_id', Auth::id())
            ->sum('amount');
    }

    public function isAmountVisible(): bool
    {
        return Session::get('capital_total_amount_visible', true);
    }

    public function getDisplayValue(): string
    {
        $total = $this->getTotal();

        if ($this->isAmountVisible()) {
            return '$' . number_format($total, 2);
        }

        $visibleLength = strlen('$' . number_format($total, 2));
        return str_repeat('*', max(8, $visibleLength));
    }
}
