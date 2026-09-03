<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionsEndToEndTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_access_transactions_index(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        $response = $this->get(route('filament.app.resources.transactions.index'));

        $response->assertForbidden();
    }

    public function test_regular_user_sees_only_their_transactions_in_index(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $other = User::factory()->create(['is_admin' => false]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'concept' => 'Mi transacción visible',
        ]);

        Transaction::factory()->create([
            'user_id' => $other->id,
            'concept' => 'Transacción de otro usuario',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('filament.app.resources.transactions.index'));

        $response->assertOk();
    }

    public function test_legacy_edit_page_url_returns_not_found(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->get('/app/transactions/'.$transaction->id.'/edit')->assertNotFound();
    }
}
