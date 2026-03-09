<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\FundOrigin;
use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use App\Policies\FundOriginPolicy;
use App\Policies\TagPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        FundOrigin::class => FundOriginPolicy::class,
        Tag::class => TagPolicy::class,
        Transaction::class => TransactionPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

