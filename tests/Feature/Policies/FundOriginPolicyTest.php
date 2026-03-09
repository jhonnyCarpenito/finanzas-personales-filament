<?php

declare(strict_types=1);

namespace Tests\Feature\Policies;

use App\Models\FundOrigin;
use App\Models\User;
use App\Policies\FundOriginPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundOriginPolicyTest extends TestCase
{
    use RefreshDatabase;

    private FundOriginPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new FundOriginPolicy();
    }

    public function test_regular_user_can_view_any_fund_origins(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_admin_cannot_view_any_fund_origins(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->assertFalse($this->policy->viewAny($admin));
    }

    public function test_owner_can_view_own_fund_origin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $fundOrigin = FundOrigin::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->view($user, $fundOrigin));
    }

    public function test_user_cannot_view_other_users_fund_origin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create(['is_admin' => false]);
        $fundOrigin = FundOrigin::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->view($user, $fundOrigin));
    }

    public function test_regular_user_can_create_fund_origins(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_admin_cannot_create_fund_origins(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->assertFalse($this->policy->create($admin));
    }

    public function test_owner_can_update_own_fund_origin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $fundOrigin = FundOrigin::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->update($user, $fundOrigin));
    }

    public function test_user_cannot_update_other_users_fund_origin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create(['is_admin' => false]);
        $fundOrigin = FundOrigin::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->update($user, $fundOrigin));
    }

    public function test_owner_can_delete_own_fund_origin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $fundOrigin = FundOrigin::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->delete($user, $fundOrigin));
    }

    public function test_user_cannot_delete_other_users_fund_origin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create(['is_admin' => false]);
        $fundOrigin = FundOrigin::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->delete($user, $fundOrigin));
    }
}
