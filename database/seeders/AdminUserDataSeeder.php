<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserDataSeeder extends Seeder
{
    /**
     * Pobla la base de datos con transacciones de ejemplo para el usuario admin.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first();

        if (! $admin) {
            $this->command->warn('Usuario admin@admin.com no encontrado. Ejecuta primero AdminUserSeeder.');

            return;
        }

        $tags = Tag::global()->pluck('id')->toArray();

        if (empty($tags)) {
            $this->command->warn('No hay tags globales. Ejecuta primero TagSeeder.');
        }

        $transactions = Transaction::factory()
            ->count(60)
            ->for($admin)
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

        $this->command->info('Transacciones de ejemplo creadas para admin@admin.com.');
    }
}
