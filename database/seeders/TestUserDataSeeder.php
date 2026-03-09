<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\FundOrigin;
use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestUserDataSeeder extends Seeder
{
    /**
     * Crea transacciones de ejemplo para el usuario de prueba (solo si aún no tiene).
     * Usa la Factory con Faker (Faker está en require para producción).
     */
    public function run(): void
    {
        $user = User::where('email', TestUserSeeder::TEST_USER_EMAIL)->first();

        if (! $user) {
            $this->command->warn('Usuario de prueba no encontrado. Ejecuta primero TestUserSeeder.');

            return;
        }

        if ($user->fundOrigins()->count() === 0) {
            $this->seedFundOrigins($user);
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

    private function seedFundOrigins(User $user): void
    {
        $origins = [
            ['name' => 'Banesco Panamá', 'amount' => 5000.00, 'color' => 'success', 'order' => 1],
            ['name' => 'MetaMask', 'amount' => 1200.50, 'color' => 'info', 'order' => 2],
            ['name' => 'Efectivo', 'amount' => 350.00, 'color' => 'warning', 'order' => 3],
            ['name' => 'Binance', 'amount' => 800.00, 'color' => 'gray', 'order' => 4],
        ];

        foreach ($origins as $data) {
            FundOrigin::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'amount' => $data['amount'],
                'color' => $data['color'],
                'order' => $data['order'],
            ]);
        }

        $this->command->info('Orígenes de fondos de ejemplo creados para usuario de prueba.');
    }
}
