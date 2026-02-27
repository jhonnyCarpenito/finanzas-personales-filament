<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return ! $user->is_admin;
    }

    public function view(User $user, Transaction $transaction): bool
    {
        return ! $user->is_admin && $transaction->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return ! $user->is_admin;
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return ! $user->is_admin && $transaction->user_id === $user->id;
    }

    public function delete(User $user, Transaction $transaction): bool
    {
        return ! $user->is_admin && $transaction->user_id === $user->id;
    }
}
