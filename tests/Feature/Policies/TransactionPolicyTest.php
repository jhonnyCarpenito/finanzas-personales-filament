<?php

declare(strict_types=1);

namespace Tests\Feature\Policies;

use App\Models\Transaction;
use App\Models\User;
use App\Policies\TransactionPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionPolicyTest extends TestCase
{
    use RefreshDatabase;

    private TransactionPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new TransactionPolicy();
    }

    public function test_regular_user_can_view_any_transactions(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_admin_cannot_view_any_transactions(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->assertFalse($this->policy->viewAny($admin));
    }

    public function test_owner_can_view_own_transaction(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->view($user, $transaction));
    }

    public function test_user_cannot_view_other_users_transaction(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create(['is_admin' => false]);
        $transaction = Transaction::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->view($user, $transaction));
    }

    public function test_admin_cannot_view_any_transaction(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $transaction = Transaction::factory()->create();

        $this->assertFalse($this->policy->view($admin, $transaction));
    }

    public function test_regular_user_can_create_transactions(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_admin_cannot_create_transactions(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->assertFalse($this->policy->create($admin));
    }

    public function test_owner_can_update_own_transaction(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->update($user, $transaction));
    }

    public function test_user_cannot_update_other_users_transaction(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create(['is_admin' => false]);
        $transaction = Transaction::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->update($user, $transaction));
    }

    public function test_owner_can_delete_own_transaction(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->delete($user, $transaction));
    }

    public function test_user_cannot_delete_other_users_transaction(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create(['is_admin' => false]);
        $transaction = Transaction::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->delete($user, $transaction));
    }
}
