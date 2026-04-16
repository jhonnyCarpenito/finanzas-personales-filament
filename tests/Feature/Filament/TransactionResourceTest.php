<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\TransactionResource;
use App\Filament\Resources\TransactionResource\Pages\ListTransactions;
use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class TransactionResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_sees_only_their_transactions_in_resource_query(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $other = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->count(2)->create(['user_id' => $user->id]);
        Transaction::factory()->count(3)->create(['user_id' => $other->id]);

        $this->actingAs($user);

        $results = TransactionResource::getEloquentQuery()->pluck('user_id')->all();

        $this->assertSame([$user->id, $user->id], $results);
    }

    public function test_admin_sees_no_transactions_in_resource_query(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        Transaction::factory()->count(3)->create();

        $this->actingAs($admin);

        $count = TransactionResource::getEloquentQuery()->count();

        $this->assertSame(0, $count);
    }

    public function test_navigation_hidden_for_admin_and_visible_for_regular_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin);
        $this->assertFalse(TransactionResource::shouldRegisterNavigation());

        $this->actingAs($user);
        $this->assertTrue(TransactionResource::shouldRegisterNavigation());
    }

    public function test_month_filter_includes_last_day_when_date_column_has_time_suffix(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'date' => '2026-01-31',
            'concept' => 'End of month check',
            'amount' => 100.00,
        ]);

        DB::table('transactions')->where('id', $transaction->id)->update([
            'date' => '2026-01-31 00:00:00',
        ]);

        $betweenCount = TransactionResource::getEloquentQuery()
            ->whereBetween('date', ['2026-01-01', '2026-01-31'])
            ->count();

        $whereDateCount = TransactionResource::getEloquentQuery()
            ->whereDate('date', '>=', '2026-01-01')
            ->whereDate('date', '<=', '2026-01-31')
            ->count();

        $this->assertSame(0, $betweenCount);
        $this->assertSame(1, $whereDateCount);
    }

    public function test_duplicate_table_action_creates_a_new_transaction_with_same_data_and_tags(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        $tagA = Tag::query()->create([
            'name' => 'Hogar',
            'color' => 'blue',
            'user_id' => $user->id,
        ]);
        $tagB = Tag::query()->create([
            'name' => 'Global',
            'color' => 'green',
            'user_id' => null,
        ]);

        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 98.50,
            'concept' => 'Servicio de internet',
            'date' => '2026-04-12',
        ]);
        $transaction->tags()->sync([$tagA->id, $tagB->id]);

        Livewire::test(ListTransactions::class)
            ->callTableAction('duplicate', $transaction);

        $this->assertDatabaseCount('transactions', 2);

        $duplicate = Transaction::query()
            ->where('user_id', $user->id)
            ->where('concept', 'Servicio de internet')
            ->whereKeyNot($transaction->id)
            ->first();

        $this->assertNotNull($duplicate);
        $this->assertSame('expense', $duplicate->type->value);
        $this->assertSame('98.50', $duplicate->amount);
        $this->assertSame('2026-04-12', $duplicate->date->toDateString());
        $this->assertEqualsCanonicalizing([$tagA->id, $tagB->id], $duplicate->tags()->pluck('tags.id')->all());
    }
}
