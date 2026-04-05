<?php

declare(strict_types=1);

namespace Tests\Unit\Services\TransactionImport;

use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionImport\PersonalTransactionsJsonImporter;
use Database\Seeders\TagSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

final class PersonalTransactionsJsonImporterTest extends TestCase
{
    use RefreshDatabase;

    private static function fixturePath(): string
    {
        return base_path('tests/Fixtures/personal_transactions_import_minimal.json');
    }

    public function test_import_reuses_global_tag_and_creates_user_tag_when_missing(): void
    {
        $this->seed(TagSeeder::class);

        $user = User::factory()->create(['is_admin' => false]);

        $importer = new PersonalTransactionsJsonImporter;
        $importer->import($user, self::fixturePath());

        $income = Transaction::where('user_id', $user->id)
            ->where('concept', 'Fixture ingreso único')
            ->firstOrFail();

        $freelance = Tag::where('name', 'Freelance')->whereNull('user_id')->firstOrFail();
        $this->assertTrue($income->tags()->whereKey($freelance->id)->exists());

        $expense = Transaction::where('user_id', $user->id)
            ->where('concept', 'Fixture gasto tag nuevo')
            ->firstOrFail();

        $personal = Tag::where('name', 'UniqueImportTagXYZ')->where('user_id', $user->id)->firstOrFail();
        $this->assertSame('gray', $personal->color);
        $this->assertTrue($expense->tags()->whereKey($personal->id)->exists());
    }

    public function test_import_is_idempotent(): void
    {
        $this->seed(TagSeeder::class);

        $user = User::factory()->create(['is_admin' => false]);

        $importer = new PersonalTransactionsJsonImporter;
        $path = self::fixturePath();

        $importer->import($user, $path);
        $importer->import($user, $path);

        $this->assertSame(2, Transaction::where('user_id', $user->id)->count());
        $this->assertSame(1, Tag::where('name', 'UniqueImportTagXYZ')->where('user_id', $user->id)->count());
    }

    public function test_throws_when_file_missing(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $importer = new PersonalTransactionsJsonImporter;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('not found');

        $importer->import($user, '/nonexistent/path/transactions.json');
    }

    public function test_throws_when_json_invalid(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $path = storage_path('framework/testing/invalid_import.json');

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, '{invalid');

        try {
            $importer = new PersonalTransactionsJsonImporter;
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('Invalid transaction import JSON');

            $importer->import($user, $path);
        } finally {
            @unlink($path);
        }
    }

    public function test_throws_when_transactions_key_missing(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $path = storage_path('framework/testing/bad_shape_import.json');

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode(['other' => []], JSON_THROW_ON_ERROR));

        try {
            $importer = new PersonalTransactionsJsonImporter;
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('"transactions" array');

            $importer->import($user, $path);
        } finally {
            @unlink($path);
        }
    }

    public function test_throws_when_row_missing_required_field(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $path = storage_path('framework/testing/incomplete_row_import.json');

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode([
            'transactions' => [
                ['date' => '2026-01-01', 'amount' => 1, 'concept' => 'x'],
            ],
        ], JSON_THROW_ON_ERROR));

        try {
            $importer = new PersonalTransactionsJsonImporter;
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('missing "type"');

            $importer->import($user, $path);
        } finally {
            @unlink($path);
        }
    }
}
