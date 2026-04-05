<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Services\TransactionImport\PersonalTransactionsJsonImporter;
use Illuminate\Database\Seeder;

class PersonalTransactionsJsonSeeder extends Seeder
{
    public function __construct(
        private readonly PersonalTransactionsJsonImporter $importer,
    ) {}

    /**
     * Imports {@see database/data/personal_transactions_import.json} for the resolved user.
     * Override email with TRANSACTION_IMPORT_USER_EMAIL; otherwise production uses production_default_email config.
     */
    public function run(): void
    {
        $email = config('transaction_import.user_email');

        if (! is_string($email) || trim($email) === '') {
            $email = app()->environment('production')
                ? (string) config('transaction_import.production_default_email')
                : TestUserSeeder::TEST_USER_EMAIL;
        } else {
            $email = trim($email);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->command?->warn('Transaction import skipped: no user found for email '.$email.'.');

            return;
        }

        $path = database_path('data/personal_transactions_import.json');

        $this->importer->import($user, $path);

        $this->command?->info('Personal transactions imported for '.$email.'.');
    }
}
