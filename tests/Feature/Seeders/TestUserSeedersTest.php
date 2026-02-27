<?php

declare(strict_types=1);

namespace Tests\Feature\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\TestUserDataSeeder;
use Database\Seeders\TestUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestUserSeedersTest extends TestCase
{
    use RefreshDatabase;

    public function test_test_user_is_created_and_idempotent(): void
    {
        $seeder = new TestUserSeeder();

        $seeder->run();
        $first = User::where('email', TestUserSeeder::TEST_USER_EMAIL)->firstOrFail();
        $originalId = $first->id;

        $seeder->run();
        $second = User::where('email', TestUserSeeder::TEST_USER_EMAIL)->firstOrFail();

        $this->assertSame($originalId, $second->id);
    }

    public function test_test_user_transactions_are_seeded_only_once(): void
    {
        (new TestUserSeeder())->run();
        $user = User::where('email', TestUserSeeder::TEST_USER_EMAIL)->firstOrFail();

        $this->seed(TestUserDataSeeder::class);
        $firstCount = Transaction::where('user_id', $user->id)->count();
        $this->assertSame(60, $firstCount);

        $this->seed(TestUserDataSeeder::class);
        $secondCount = Transaction::where('user_id', $user->id)->count();
        $this->assertSame(
            $firstCount,
            $secondCount,
            'Running TestUserDataSeeder twice should not duplicate transactions'
        );
    }
}

