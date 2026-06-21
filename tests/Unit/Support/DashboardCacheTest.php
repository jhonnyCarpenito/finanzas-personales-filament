<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Models\FundOrigin;
use App\Models\Transaction;
use App\Models\User;
use App\Support\DashboardCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

final class DashboardCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_forget_transactions_for_user_clears_finance_stats_and_income_expense_keys(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        Cache::put("dashboard:{$user->id}:finance-stats", ['balance' => 100], 300);
        Cache::put("dashboard:{$user->id}:income-expense:".now()->year, ['datasets' => []], 300);
        Cache::put("dashboard:{$user->id}:capital-pie", ['datasets' => []], 300);

        DashboardCache::forgetTransactionsForUser($user->id);

        $this->assertFalse(Cache::has("dashboard:{$user->id}:finance-stats"));
        $this->assertFalse(Cache::has("dashboard:{$user->id}:income-expense:".now()->year));
        $this->assertTrue(Cache::has("dashboard:{$user->id}:capital-pie"));
    }

    public function test_forget_fund_origins_for_user_clears_finance_stats_and_capital_pie_keys(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        Cache::put("dashboard:{$user->id}:finance-stats", ['balance' => 100], 300);
        Cache::put("dashboard:{$user->id}:income-expense:".now()->year, ['datasets' => []], 300);
        Cache::put("dashboard:{$user->id}:capital-pie", ['datasets' => []], 300);

        DashboardCache::forgetFundOriginsForUser($user->id);

        $this->assertFalse(Cache::has("dashboard:{$user->id}:finance-stats"));
        $this->assertTrue(Cache::has("dashboard:{$user->id}:income-expense:".now()->year));
        $this->assertFalse(Cache::has("dashboard:{$user->id}:capital-pie"));
    }

    public function test_transaction_observer_invalidates_transaction_dashboard_cache(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        Cache::put("dashboard:{$user->id}:finance-stats", ['balance' => 100], 300);

        Transaction::factory()->create(['user_id' => $user->id]);

        $this->assertFalse(Cache::has("dashboard:{$user->id}:finance-stats"));
    }

    public function test_fund_origin_observer_invalidates_fund_origin_dashboard_cache(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        Cache::put("dashboard:{$user->id}:capital-pie", ['datasets' => []], 300);

        FundOrigin::factory()->create(['user_id' => $user->id]);

        $this->assertFalse(Cache::has("dashboard:{$user->id}:capital-pie"));
    }
}
