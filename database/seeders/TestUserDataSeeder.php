<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestUserDataSeeder extends Seeder
{
    /**
     * Crea transacciones de ejemplo para el usuario de prueba (solo si aún no tiene).
     */
    public function run(): void
    {
        $user = User::where('email', TestUserSeeder::TEST_USER_EMAIL)->first();

        if (! $user) {
            $this->command->warn('Usuario de prueba no encontrado. Ejecuta primero TestUserSeeder.');

            return;
        }

        if ($user->transactions()->count() > 0) {
            $this->command->info('El usuario de prueba ya tiene transacciones. Se omiten datos de ejemplo.');

            return;
        }

        $tags = Tag::global()->pluck('id')->toArray();

        if (empty($tags)) {
            $this->command->warn('No hay tags globales. Ejecuta primero TagSeeder.');
        }

        $transactions = Transaction::factory()
            ->count(60)
            ->for($user)
            ->sequence(
                ['type' => 'income'],
                ['type' => 'income'],
                ['type' => 'expense'],
                ['type' => 'expense'],
                ['type' => 'expense'],
            )
            ->create();

        foreach ($transactions as $transaction) {
            if (! empty($tags)) {
                $attachCount = rand(1, min(3, count($tags)));
                $selected = array_rand(array_flip($tags), $attachCount);
                $tagIds = is_array($selected) ? $selected : [$selected];
                $transaction->tags()->attach($tagIds);
            }
        }

        $this->command->info('Transacciones de ejemplo creadas para usuario de prueba ('.TestUserSeeder::TEST_USER_EMAIL.').');
    }
}
