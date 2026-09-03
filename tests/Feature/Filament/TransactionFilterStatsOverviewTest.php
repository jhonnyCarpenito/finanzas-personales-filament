<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\TransactionResource\Pages\ListTransactions;
use App\Filament\Resources\TransactionResource\Widgets\TransactionFilterStatsOverview;
use App\Models\Transaction;
use App\Models\User;
use App\Support\CapitalAmountDisplay;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

final class TransactionFilterStatsOverviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setFilamentPanel('app');
    }

    public function test_admin_cannot_view_widget_and_regular_user_can(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin);
        $this->assertFalse(TransactionFilterStatsOverview::canView());

        $this->actingAs($user);
        $this->assertTrue(TransactionFilterStatsOverview::canView());
    }

    public function test_list_page_registers_filter_stats_widget(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        Livewire::test(ListTransactions::class)
            ->assertSeeLivewire(TransactionFilterStatsOverview::class);
    }

    public function test_stats_reflect_default_month_filter_totals(): void
    {
        Carbon::setTestNow('2026-06-15 12:00:00');

        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        Transaction::factory()->income()->create([
            'user_id' => $user->id,
            'amount' => 200.50,
            'date' => '2026-06-10',
        ]);
        Transaction::factory()->expense()->create([
            'user_id' => $user->id,
            'amount' => 75.25,
            'date' => '2026-06-11',
        ]);
        Transaction::factory()->income()->create([
            'user_id' => $user->id,
            'amount' => 999.00,
            'date' => '2026-05-10',
        ]);

        Livewire::test(TransactionFilterStatsOverview::class, [
            'tableFilters' => [
                'month' => [
                    'month' => '2026-06',
                ],
            ],
        ])
            ->assertSee('Total Ingreso')
            ->assertSee('Total Egreso')
            ->assertSee('Balance')
            ->assertSee('$200.50')
            ->assertSee('$75.25')
            ->assertSee('$125.25')
            ->assertDontSee('$999.00');

        Carbon::setTestNow();
    }

    public function test_stats_update_when_month_filter_changes(): void
    {
        Carbon::setTestNow('2026-06-15 12:00:00');

        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        Transaction::factory()->income()->create([
            'user_id' => $user->id,
            'amount' => 150.00,
            'date' => '2026-06-10',
        ]);
        Transaction::factory()->expense()->create([
            'user_id' => $user->id,
            'amount' => 40.00,
            'date' => '2026-05-10',
        ]);

        Livewire::test(TransactionFilterStatsOverview::class, [
            'tableFilters' => [
                'month' => [
                    'month' => '2026-05',
                ],
            ],
        ])
            ->assertSee('$0.00')
            ->assertSee('$40.00')
            ->assertSee('$-40.00')
            ->assertDontSee('$150.00');

        Carbon::setTestNow();
    }

    public function test_toggle_hides_amounts_on_filter_stats(): void
    {
        Carbon::setTestNow('2026-06-15 12:00:00');

        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        Transaction::factory()->income()->create([
            'user_id' => $user->id,
            'amount' => 320.00,
            'date' => '2026-06-10',
        ]);

        Livewire::test(TransactionFilterStatsOverview::class, [
            'tableFilters' => [
                'month' => [
                    'month' => '2026-06',
                ],
            ],
        ])
            ->assertSee('$320.00')
            ->call('toggleAmountVisibility')
            ->assertDispatched(CapitalAmountDisplay::VISIBILITY_CHANGED_EVENT)
            ->assertDontSee('$320.00');

        $this->assertFalse(CapitalAmountDisplay::isVisible());

        Carbon::setTestNow();
    }

    public function test_balance_stat_is_income_minus_expense_for_current_filters(): void
    {
        Carbon::setTestNow('2026-06-15 12:00:00');

        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        Transaction::factory()->income()->create([
            'user_id' => $user->id,
            'amount' => 80.00,
            'date' => '2026-06-10',
        ]);
        Transaction::factory()->expense()->create([
            'user_id' => $user->id,
            'amount' => 120.00,
            'date' => '2026-06-12',
        ]);

        Livewire::test(TransactionFilterStatsOverview::class, [
            'tableFilters' => [
                'month' => [
                    'month' => '2026-06',
                ],
            ],
        ])
            ->assertSee('Balance')
            ->assertSee('Ingresos menos egresos')
            ->assertSee('$80.00')
            ->assertSee('$120.00')
            ->assertSee('$-40.00');

        Carbon::setTestNow();
    }
}
