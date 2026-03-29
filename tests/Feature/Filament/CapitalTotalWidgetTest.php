<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Widgets\CapitalTotalWidget;
use App\Models\FundOrigin;
use App\Models\User;
use App\Support\CapitalAmountDisplay;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class CapitalTotalWidgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_toggle_updates_session_and_dispatches_event(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        FundOrigin::factory()->create(['user_id' => $user->id, 'amount' => 50]);

        $this->actingAs($user);

        Livewire::test(CapitalTotalWidget::class)
            ->call('toggleAmountVisibility')
            ->assertDispatched(CapitalTotalWidget::AMOUNT_VISIBILITY_CHANGED_EVENT);

        $this->assertFalse((bool) session()->get(CapitalAmountDisplay::SESSION_KEY));
    }

    public function test_total_updates_after_fund_origins_data_changed_event(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        FundOrigin::factory()->create(['user_id' => $user->id, 'amount' => 100]);

        $this->actingAs($user);

        $component = Livewire::test(CapitalTotalWidget::class);

        $component->assertSee('$100.00');

        FundOrigin::factory()->create(['user_id' => $user->id, 'amount' => 50]);

        $component->dispatch(CapitalTotalWidget::FUND_ORIGINS_DATA_CHANGED_EVENT);

        $component->assertSee('$150.00');
    }
}
