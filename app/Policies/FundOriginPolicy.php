<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\FundOrigin;
use App\Models\User;

class FundOriginPolicy
{
    public function viewAny(User $user): bool
    {
        return ! $user->is_admin;
    }

    public function view(User $user, FundOrigin $fundOrigin): bool
    {
        return ! $user->is_admin && $fundOrigin->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return ! $user->is_admin;
    }

    public function update(User $user, FundOrigin $fundOrigin): bool
    {
        return ! $user->is_admin && $fundOrigin->user_id === $user->id;
    }

    public function delete(User $user, FundOrigin $fundOrigin): bool
    {
        return ! $user->is_admin && $fundOrigin->user_id === $user->id;
    }
}
