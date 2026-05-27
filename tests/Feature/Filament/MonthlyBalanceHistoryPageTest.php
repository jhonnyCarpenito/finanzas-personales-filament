<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\DTOs\MonthlyBalanceSummary;
use App\Filament\Pages\MonthlyBalanceHistoryPage;
use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use App\Services\MonthlyBalanceHistoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

final class MonthlyBalanceHistoryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_access_page_and_regular_user_can(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['is_admin' => true]);
        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin);
        $this->assertFalse(MonthlyBalanceHistoryPage::canAccess());

        $this->actingAs($user);
        $this->assertTrue(MonthlyBalanceHistoryPage::canAccess());
    }

    public function test_page_shows_only_current_year_months_by_default(): void
    {
        Carbon::setTestNow('2026-05-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 1000,
            'date' => '2026-03-10',
        ]);
        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 200,
            'date' => '2026-03-15',
        ]);
        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 500,
            'date' => '2025-12-20',
        ]);

        Livewire::test(MonthlyBalanceHistoryPage::class)
            ->assertCanSeeTableRecords(
                collect(app(MonthlyBalanceHistoryService::class)->getSummariesForUser($user->id, 2026))
                    ->map(fn (MonthlyBalanceSummary $summary) => \App\Models\MonthlyBalanceSummaryRecord::fromSummary($summary))
                    ->all(),
            )
            ->assertCountTableRecords(1);

        Carbon::setTestNow();
    }

    public function test_changing_year_filter_updates_table_rows(): void
    {
        Carbon::setTestNow('2026-05-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 100,
            'date' => '2025-08-01',
        ]);
        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 300,
            'date' => '2026-02-01',
        ]);

        Livewire::test(MonthlyBalanceHistoryPage::class)
            ->assertCountTableRecords(1)
            ->set('filtersData.year', 2025)
            ->assertSet('year', 2025)
            ->assertCountTableRecords(1);

        Carbon::setTestNow();
    }

    public function test_view_transactions_action_builds_url_with_month_filter(): void
    {
        Carbon::setTestNow('2026-05-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 150,
            'date' => '2026-04-12',
        ]);

        $summary = app(MonthlyBalanceHistoryService::class)
            ->getSummariesForUser($user->id, 2026)
            ->first();

        $this->assertNotNull($summary);

        $record = \App\Models\MonthlyBalanceSummaryRecord::fromSummary($summary);

        Livewire::test(MonthlyBalanceHistoryPage::class)
            ->assertTableActionHasUrl(
                'viewTransactions',
                TransactionResource::getUrl('index', [
                    'tableFilters' => [
                        'month' => [
                            'month' => '2026-04',
                        ],
                    ],
                ]),
                $record,
            );

        Carbon::setTestNow();
    }

    public function test_service_calculates_net_balance_on_summary_dto(): void
    {
        Carbon::setTestNow('2026-05-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 1000,
            'date' => '2026-01-10',
        ]);
        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 350,
            'date' => '2026-01-20',
        ]);

        $summary = app(MonthlyBalanceHistoryService::class)
            ->getSummariesForUser($user->id, 2026)
            ->first();

        $this->assertNotNull($summary);
        $this->assertSame(650.0, $summary->netBalance());

        Carbon::setTestNow();
    }

    public function test_available_years_include_current_year_when_user_has_no_transactions(): void
    {
        Carbon::setTestNow('2026-05-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        $years = app(MonthlyBalanceHistoryService::class)->getAvailableYearsForUser($user->id);

        $this->assertSame(['2026' => '2026'], $years);

        Carbon::setTestNow();
    }
}
