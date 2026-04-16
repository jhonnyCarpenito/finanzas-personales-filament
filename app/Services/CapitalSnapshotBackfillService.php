<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;

final class CapitalSnapshotBackfillService
{
    public function backfill(): void
    {
        $snapshotTimestamp = now();

        DB::table('users')
            ->select('id')
            ->where('is_admin', false)
            ->orderBy('id')
            ->get()
            ->each(function (object $user) use ($snapshotTimestamp): void {
                $hasSnapshot = DB::table('capital_snapshots')
                    ->where('user_id', $user->id)
                    ->exists();

                if ($hasSnapshot) {
                    return;
                }

                $totalAmount = (float) DB::table('fund_origins')
                    ->where('user_id', $user->id)
                    ->sum('amount');

                DB::table('capital_snapshots')->insert([
                    'user_id' => $user->id,
                    'total_amount' => $totalAmount,
                    'captured_at' => $snapshotTimestamp,
                    'created_at' => $snapshotTimestamp,
                    'updated_at' => $snapshotTimestamp,
                ]);
            });
    }
}
