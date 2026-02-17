<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestUserDataSeeder extends Seeder
{
    /**
     * Crea transacciones de ejemplo para el usuario de prueba (solo si aún no tiene).
     * No usa Factory/Faker para funcionar en producción (composer --no-dev).
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

        $conceptsIncome = ['Nómina', 'Freelance', 'Venta', 'Reembolso', 'Inversión', 'Extra'];
        $conceptsExpense = ['Supermercado', 'Transporte', 'Servicios', 'Ocio', 'Salud', 'Comida'];

        $types = [
            'income', 'income', 'expense', 'expense', 'expense',
            'income', 'income', 'expense', 'expense', 'expense',
        ];

        $transactions = [];
        $now = Carbon::now();
        for ($i = 0; $i < 60; $i++) {
            $type = $types[$i % 10];
            $amount = $type === 'income'
                ? round(rand(10000, 500000) / 100, 2)
                : round(rand(1000, 80000) / 100, 2);
            $concepts = $type === 'income' ? $conceptsIncome : $conceptsExpense;
            $concept = $concepts[array_rand($concepts)].' '.$now->copy()->subDays(rand(0, 365))->format('Y-m');
            $date = $now->copy()->subDays(rand(0, 365))->format('Y-m-d');

            $transactions[] = [
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $amount,
                'concept' => $concept,
                'date' => $date,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($transactions, 20) as $chunk) {
            Transaction::insert($chunk);
        }

        $models = Transaction::where('user_id', $user->id)->orderBy('id')->get();
        foreach ($models as $transaction) {
            if (! empty($tags)) {
                $attachCount = min(rand(1, 3), count($tags));
                $tagIds = (array) array_rand(array_flip($tags), $attachCount);
                $transaction->tags()->attach($tagIds);
            }
        }

        $this->command->info('Transacciones de ejemplo creadas para usuario de prueba ('.TestUserSeeder::TEST_USER_EMAIL.').');
    }
}
