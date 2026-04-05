<?php

declare(strict_types=1);

namespace Tests\Feature\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\PersonalTransactionsJsonSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\TestUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

final class PersonalTransactionsJsonSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_imports_full_json_for_test_user(): void
    {
        $this->seed(TagSeeder::class);
        (new TestUserSeeder)->run();

        $user = User::where('email', TestUserSeeder::TEST_USER_EMAIL)->firstOrFail();

        $this->seed(PersonalTransactionsJsonSeeder::class);
        $this->seed(PersonalTransactionsJsonSeeder::class);

        $this->assertSame(172, Transaction::where('user_id', $user->id)->count());
    }

    public function test_seeder_respects_transaction_import_user_email_config(): void
    {
        $this->seed(TagSeeder::class);

        $user = User::factory()->create([
            'email' => 'custom-import@example.com',
            'is_admin' => false,
        ]);

        Config::set('transaction_import.user_email', 'custom-import@example.com');

        $this->seed(PersonalTransactionsJsonSeeder::class);

        $this->assertSame(172, Transaction::where('user_id', $user->id)->count());
        $this->assertSame(0, Transaction::where('user_id', '!=', $user->id)->count());
    }
}
