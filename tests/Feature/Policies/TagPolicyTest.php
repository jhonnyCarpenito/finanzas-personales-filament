<?php

declare(strict_types=1);

namespace Tests\Feature\Policies;

use App\Models\Tag;
use App\Models\User;
use App\Policies\TagPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagPolicyTest extends TestCase
{
    use RefreshDatabase;

    private TagPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new TagPolicy();
    }

    public function test_anyone_can_view_any_tags(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $admin = User::factory()->create(['is_admin' => true]);

        $this->assertTrue($this->policy->viewAny($user));
        $this->assertTrue($this->policy->viewAny($admin));
    }

    public function test_admin_can_view_any_tag(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $tag = Tag::create(['name' => 'Test', 'user_id' => null]);

        $this->assertTrue($this->policy->view($admin, $tag));
    }

    public function test_user_can_view_global_tag(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $tag = Tag::create(['name' => 'Global', 'user_id' => null]);

        $this->assertTrue($this->policy->view($user, $tag));
    }

    public function test_user_can_view_own_tag(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $tag = Tag::create(['name' => 'Personal', 'user_id' => $user->id]);

        $this->assertTrue($this->policy->view($user, $tag));
    }

    public function test_user_cannot_view_other_users_tag(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create(['is_admin' => false]);
        $tag = Tag::create(['name' => 'Other', 'user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->view($user, $tag));
    }

    public function test_user_cannot_update_global_tag(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $tag = Tag::create(['name' => 'Global', 'user_id' => null]);

        $this->assertFalse($this->policy->update($user, $tag));
    }

    public function test_user_can_update_own_tag(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $tag = Tag::create(['name' => 'Mine', 'user_id' => $user->id]);

        $this->assertTrue($this->policy->update($user, $tag));
    }

    public function test_admin_can_update_any_tag(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $tag = Tag::create(['name' => 'Any', 'user_id' => null]);

        $this->assertTrue($this->policy->update($admin, $tag));
    }

    public function test_user_cannot_delete_global_tag(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $tag = Tag::create(['name' => 'Global', 'user_id' => null]);

        $this->assertFalse($this->policy->delete($user, $tag));
    }

    public function test_user_can_delete_own_tag(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $tag = Tag::create(['name' => 'Mine', 'user_id' => $user->id]);

        $this->assertTrue($this->policy->delete($user, $tag));
    }

    public function test_admin_can_delete_any_tag(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $tag = Tag::create(['name' => 'Any', 'user_id' => null]);

        $this->assertTrue($this->policy->delete($admin, $tag));
    }
}
