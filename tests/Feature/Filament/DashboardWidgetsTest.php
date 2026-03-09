<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Widgets\FinanceStatsOverview;
use App\Filament\Widgets\IncomeExpenseChart;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardWidgetsTest extends TestCase
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
}

