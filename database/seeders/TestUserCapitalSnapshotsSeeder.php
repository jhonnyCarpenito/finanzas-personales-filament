<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CapitalSnapshot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TestUserCapitalSnapshotsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()
            ->where('email', TestUserSeeder::TEST_USER_EMAIL)
            ->first();

        if (! $user) {
            $this->command?->warn('Usuario de prueba no encontrado. Ejecuta primero TestUserSeeder.');

            return;
        }

        $snapshotExists = CapitalSnapshot::query()
            ->where('user_id', $user->id)
            ->exists();

        if ($snapshotExists) {
            $this->command?->info('El usuario de prueba ya tiene snapshots. Se omite.');

            return;
        }

        $this->seedYearlySnapshots($user->id);
        $this->seedMonthlySnapshots($user->id);
        $this->seedDailySnapshots($user->id);

        $this->command?->info('Snapshots de capital creados para usuario de prueba.');
    }

    private function seedYearlySnapshots(int $userId): void
    {
        $currentYear = now()->year;
        $startingAmount = 5200.00;

        foreach (range(4, 0) as $yearOffset) {
            $year = $currentYear - $yearOffset;
            $amount = $startingAmount + ((4 - $yearOffset) * 850.00);

            CapitalSnapshot::query()->create([
                'user_id' => $userId,
                'total_amount' => $amount,
                'captured_at' => Carbon::create($year, 12, 31, 23, 0, 0),
            ]);
        }
    }

    private function seedMonthlySnapshots(int $userId): void
    {
        $base = now()->startOfMonth()->subMonths(12);
        $amount = 7800.00;

        foreach (range(0, 11) as $index) {
            $amount += $index % 3 === 0 ? 420.00 : -130.00;

            CapitalSnapshot::query()->create([
                'user_id' => $userId,
                'total_amount' => max($amount, 0.00),
                'captured_at' => $base->copy()->addMonths($index)->endOfMonth()->setTime(22, 0),
            ]);
        }
    }

    private function seedDailySnapshots(int $userId): void
    {
        $base = now()->startOfDay()->subDays(30);
        $amount = 8600.00;

        foreach (range(0, 29) as $index) {
            $amount += match ($index % 5) {
                0 => 180.00,
                1 => -95.00,
                2 => 60.00,
                3 => -40.00,
                default => 25.00,
            };

            CapitalSnapshot::query()->create([
                'user_id' => $userId,
                'total_amount' => max($amount, 0.00),
                'captured_at' => $base->copy()->addDays($index)->setTime(19, 30),
            ]);
        }
    }
}
