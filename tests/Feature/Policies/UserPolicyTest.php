<?php

declare(strict_types=1);

namespace Tests\Feature\Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    private UserPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new UserPolicy();
    }

    public function test_admin_can_view_any_users(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->assertTrue($this->policy->viewAny($admin));
    }

    public function test_regular_user_cannot_view_any_users(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertFalse($this->policy->viewAny($user));
    }

    public function test_admin_can_create_users(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->assertTrue($this->policy->create($admin));
    }

    public function test_regular_user_cannot_create_users(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertFalse($this->policy->create($user));
    }

    public function test_admin_can_update_users(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create();

        $this->assertTrue($this->policy->update($admin, $target));
    }

    public function test_regular_user_cannot_update_users(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $target = User::factory()->create();

        $this->assertFalse($this->policy->update($user, $target));
    }

    public function test_admin_can_delete_users(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create();

        $this->assertTrue($this->policy->delete($admin, $target));
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->assertFalse($this->policy->delete($admin, $admin));
    }

    public function test_regular_user_cannot_delete_users(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $target = User::factory()->create();

        $this->assertFalse($this->policy->delete($user, $target));
    }
}
