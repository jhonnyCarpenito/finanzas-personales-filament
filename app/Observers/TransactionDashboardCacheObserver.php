<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Transaction;
use App\Support\DashboardCache;

final class TransactionDashboardCacheObserver
{
    public function saved(Transaction $transaction): void
    {
        DashboardCache::forgetTransactionsForUser((int) $transaction->user_id);
    }

    public function deleted(Transaction $transaction): void
    {
        DashboardCache::forgetTransactionsForUser((int) $transaction->user_id);
    }
}
