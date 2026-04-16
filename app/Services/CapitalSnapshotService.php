<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CapitalSnapshot;
use App\Models\FundOrigin;

final class CapitalSnapshotService
{
    public function captureForUser(int $userId): void
    {
        $totalAmount = (float) FundOrigin::query()
            ->where('user_id', $userId)
            ->sum('amount');

        CapitalSnapshot::query()->create([
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'captured_at' => now(),
        ]);
    }
}
