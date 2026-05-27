<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionMonthlySummaryRepository;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class TransactionMonthlySummaryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TransactionMonthlySummaryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(TransactionMonthlySummaryRepository::class);
    }

    public function test_it_aggregates_income_and_expense_by_month_for_selected_year(): void
    {
        Carbon::setTestNow('2026-05-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 1000,
            'date' => '2026-03-10',
        ]);
        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 250,
            'date' => '2026-03-20',
        ]);
        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 500,
            'date' => '2026-04-05',
        ]);

        $rows = $this->repository->forUserAndYear($user->id, 2026);

        $this->assertCount(2, $rows);
        $this->assertSame('2026-04', $rows->first()->year_month);
        $this->assertSame(500.0, (float) $rows->first()->total_income);
        $this->assertSame(0.0, (float) $rows->first()->total_expense);

        $march = $rows->last();
        $this->assertSame('2026-03', $march->year_month);
        $this->assertSame(1000.0, (float) $march->total_income);
        $this->assertSame(250.0, (float) $march->total_expense);

        Carbon::setTestNow();
    }

    public function test_it_excludes_transactions_from_other_users(): void
    {
        Carbon::setTestNow('2026-05-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);
        /** @var User $other */
        $other = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 100,
            'date' => '2026-02-01',
        ]);
        Transaction::factory()->create([
            'user_id' => $other->id,
            'type' => 'income',
            'amount' => 9999,
            'date' => '2026-02-01',
        ]);

        $rows = $this->repository->forUserAndYear($user->id, 2026);

        $this->assertCount(1, $rows);
        $this->assertSame(100.0, (float) $rows->first()->total_income);

        Carbon::setTestNow();
    }

    public function test_it_excludes_months_outside_selected_year(): void
    {
        Carbon::setTestNow('2026-05-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 300,
            'date' => '2025-12-31',
        ]);
        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 400,
            'date' => '2026-01-01',
        ]);

        $rows = $this->repository->forUserAndYear($user->id, 2026);

        $this->assertCount(1, $rows);
        $this->assertSame('2026-01', $rows->first()->year_month);
        $this->assertSame(400.0, (float) $rows->first()->total_income);

        Carbon::setTestNow();
    }

    public function test_it_does_not_return_months_without_transactions(): void
    {
        Carbon::setTestNow('2026-05-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 75,
            'date' => '2026-01-15',
        ]);

        $rows = $this->repository->forUserAndYear($user->id, 2026);

        $this->assertCount(1, $rows);
        $this->assertSame('2026-01', $rows->first()->year_month);

        Carbon::setTestNow();
    }

    public function test_find_earliest_transaction_year_returns_null_when_user_has_no_transactions(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertNull($this->repository->findEarliestTransactionYear($user->id));
    }

    public function test_mysql_query_uses_quoted_date_range_and_backticked_date_column(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        $query = Transaction::query()
            ->where('user_id', $user->id)
            ->where('date', '>=', '2026-01-01')
            ->where('date', '<=', '2026-12-31')
            ->selectRaw("DATE_FORMAT(`date`, '%Y-%m') as year_month")
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense")
            ->groupByRaw("DATE_FORMAT(`date`, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(`date`, '%Y-%m') DESC");

        $query->getConnection()->setQueryGrammar(new MySqlGrammar($query->getConnection()));

        $sql = $query->toRawSql();

        $this->assertStringContainsString("'2026-01-01'", $sql);
        $this->assertStringContainsString("'2026-12-31'", $sql);
        $this->assertStringContainsString("DATE_FORMAT(`date`, '%Y-%m')", $sql);
        $this->assertStringNotContainsString('date(`date`) >= 2026-01-01', $sql);
    }

    public function test_find_earliest_transaction_year_returns_year_of_oldest_transaction(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 100,
            'date' => '2024-06-01',
        ]);
        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 200,
            'date' => '2026-01-01',
        ]);

        $this->assertSame(2024, $this->repository->findEarliestTransactionYear($user->id));
    }
}
