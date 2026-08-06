<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Widgets\FinanceStatsOverview;
use App\Filament\Widgets\IncomeExpenseChart;
use App\Models\FundOrigin;
use App\Models\Transaction;
use App\Models\User;
use App\Support\CapitalAmountDisplay;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class DashboardWidgetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_view_transaction_widgets(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $this->assertFalse(IncomeExpenseChart::canView());
        $this->assertFalse(FinanceStatsOverview::canView());
    }

    public function test_regular_user_can_view_transaction_widgets(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        $this->assertTrue(IncomeExpenseChart::canView());
        $this->assertTrue(FinanceStatsOverview::canView());
    }

    public function test_widgets_use_only_authenticated_users_transactions(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $other = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 200,
            'date' => now()->startOfYear(),
        ]);

        Transaction::factory()->create([
            'user_id' => $other->id,
            'type' => 'income',
            'amount' => 9999,
            'date' => now()->startOfYear(),
        ]);

        $this->actingAs($user);

        $this->assertSame(
            1,
            Transaction::where('user_id', $user->id)->where('type', 'income')->count()
        );

        $this->assertSame(
            1,
            Transaction::where('user_id', $other->id)->where('type', 'income')->count()
        );
    }

    public function test_finance_stats_show_amounts_by_default(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 1500.5,
            'date' => now(),
        ]);

        FundOrigin::factory()->create([
            'user_id' => $user->id,
            'amount' => 2500.75,
        ]);

        $this->actingAs($user);
        $this->setFilamentPanel('app');

        Livewire::test(FinanceStatsOverview::class)
            ->assertSee('$1,500.50')
            ->assertSee('$2,500.75')
            ->assertSee('Ocultar saldos');
    }

    public function test_finance_stats_toggle_hides_amounts_and_updates_session(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 1500.5,
            'date' => now(),
        ]);

        FundOrigin::factory()->create([
            'user_id' => $user->id,
            'amount' => 2500.75,
        ]);

        $this->actingAs($user);
        $this->setFilamentPanel('app');

        Livewire::test(FinanceStatsOverview::class)
            ->call('toggleAmountVisibility')
            ->assertDispatched(CapitalAmountDisplay::VISIBILITY_CHANGED_EVENT)
            ->assertDontSee('$1,500.50')
            ->assertDontSee('$2,500.75')
            ->assertSee('Mostrar saldos');

        $this->assertFalse(CapitalAmountDisplay::isVisible());
    }
}
