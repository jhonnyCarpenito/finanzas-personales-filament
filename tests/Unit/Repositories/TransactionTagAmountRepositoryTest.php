<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\DTOs\TagAmountSummary;
use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionTagAmountRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class TransactionTagAmountRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TransactionTagAmountRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(TransactionTagAmountRepository::class);
    }

    public function test_it_aggregates_income_and_expense_amounts_by_tag(): void
    {
        Carbon::setTestNow('2026-06-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        $foodTag = Tag::query()->create([
            'name' => 'Comida',
            'color' => 'danger',
            'user_id' => null,
        ]);
        $homeTag = Tag::query()->create([
            'name' => 'Hogar',
            'color' => 'blue',
            'user_id' => $user->id,
        ]);

        $foodExpense = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 120,
            'date' => '2026-06-10',
        ]);
        $foodExpense->tags()->sync([$foodTag->id]);

        $homeIncome = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 500,
            'date' => '2026-06-12',
        ]);
        $homeIncome->tags()->sync([$homeTag->id]);

        $sharedTags = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 80,
            'date' => '2026-06-14',
        ]);
        $sharedTags->tags()->sync([$foodTag->id, $homeTag->id]);

        $summaries = $this->repository->amountsByTagForUser($user->id);

        $this->assertCount(2, $summaries);

        /** @var TagAmountSummary $foodSummary */
        $foodSummary = $summaries->firstWhere('tagId', $foodTag->id);
        $this->assertNotNull($foodSummary);
        $this->assertSame('Comida', $foodSummary->tagName);
        $this->assertSame(0.0, $foodSummary->incomeTotal);
        $this->assertSame(200.0, $foodSummary->expenseTotal);

        /** @var TagAmountSummary $homeSummary */
        $homeSummary = $summaries->firstWhere('tagId', $homeTag->id);
        $this->assertNotNull($homeSummary);
        $this->assertSame('Hogar', $homeSummary->tagName);
        $this->assertSame(500.0, $homeSummary->incomeTotal);
        $this->assertSame(80.0, $homeSummary->expenseTotal);

        Carbon::setTestNow();
    }

    public function test_it_includes_untagged_transactions(): void
    {
        Carbon::setTestNow('2026-06-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 45.50,
            'date' => '2026-06-05',
        ]);

        $summaries = $this->repository->amountsByTagForUser($user->id);

        $this->assertCount(1, $summaries);
        $this->assertSame('untagged', $summaries->first()->tagId);
        $this->assertSame('Sin etiqueta', $summaries->first()->tagName);
        $this->assertSame(0.0, $summaries->first()->incomeTotal);
        $this->assertSame(45.50, $summaries->first()->expenseTotal);

        Carbon::setTestNow();
    }

    public function test_it_excludes_transactions_from_other_users(): void
    {
        Carbon::setTestNow('2026-06-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);
        /** @var User $other */
        $other = User::factory()->create(['is_admin' => false]);

        $tag = Tag::query()->create([
            'name' => 'Personal',
            'color' => 'success',
            'user_id' => $user->id,
        ]);

        $ownTransaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 100,
            'date' => '2026-06-01',
        ]);
        $ownTransaction->tags()->sync([$tag->id]);

        $otherTransaction = Transaction::factory()->create([
            'user_id' => $other->id,
            'type' => 'income',
            'amount' => 9999,
            'date' => '2026-06-01',
        ]);
        $otherTransaction->tags()->sync([$tag->id]);

        $summaries = $this->repository->amountsByTagForUser($user->id);

        $this->assertCount(1, $summaries);
        $this->assertSame(100.0, $summaries->first()->incomeTotal);

        Carbon::setTestNow();
    }

    public function test_it_applies_month_table_filter(): void
    {
        Carbon::setTestNow('2026-06-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        $tag = Tag::query()->create([
            'name' => 'Servicios',
            'color' => 'info',
            'user_id' => null,
        ]);

        $included = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 60,
            'date' => '2026-06-20',
        ]);
        $included->tags()->sync([$tag->id]);

        $excluded = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 300,
            'date' => '2026-05-20',
        ]);
        $excluded->tags()->sync([$tag->id]);

        $summaries = $this->repository->amountsByTagForUser($user->id, [
            'month' => [
                'month' => '2026-06',
            ],
        ]);

        $this->assertCount(1, $summaries);
        $this->assertSame(60.0, $summaries->first()->expenseTotal);

        Carbon::setTestNow();
    }
}
