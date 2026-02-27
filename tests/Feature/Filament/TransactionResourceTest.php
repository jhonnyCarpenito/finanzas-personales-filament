<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}

