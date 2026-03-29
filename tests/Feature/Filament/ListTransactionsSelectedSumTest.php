<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\TransactionResource\Pages\ListTransactions;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class ListTransactionsSelectedSumTest extends TestCase
{
    use RefreshDatabase;

    public function test_sum_selected_transactions_returns_sum_for_owner_records_only(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $other = User::factory()->create(['is_admin' => false]);
        $ownedA = Transaction::factory()->create(['user_id' => $user->id, 'amount' => 17]);
        $ownedB = Transaction::factory()->create(['user_id' => $user->id, 'amount' => 103]);
        $foreign = Transaction::factory()->create(['user_id' => $other->id, 'amount' => 999]);

        $this->actingAs($user);

        Livewire::test(ListTransactions::class)
            ->call('sumSelectedTransactions', [
                (string) $ownedA->id,
                (string) $ownedB->id,
                (string) $foreign->id,
            ])
            ->assertReturned('120.00');
    }

    public function test_sum_selected_transactions_returns_zero_for_empty_ids(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        Livewire::test(ListTransactions::class)
            ->call('sumSelectedTransactions', [])
            ->assertReturned('0.00');
    }
}
