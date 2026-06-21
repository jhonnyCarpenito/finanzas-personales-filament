<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\FundOrigin;
use App\Support\DashboardCache;

final class FundOriginDashboardCacheObserver
{
    public function saved(FundOrigin $fundOrigin): void
    {
        DashboardCache::forgetFundOriginsForUser((int) $fundOrigin->user_id);
    }

    public function deleted(FundOrigin $fundOrigin): void
    {
        DashboardCache::forgetFundOriginsForUser((int) $fundOrigin->user_id);
    }
}
