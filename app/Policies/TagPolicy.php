<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;

class TagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Todos pueden ver tags (filtradas por scope)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tag $tag): bool
    {
        // Admin puede ver todas, usuario normal solo globales o propias
        if ($user->is_admin) {
            return true;
        }

        return $tag->user_id === null || $tag->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Todos pueden crear tags
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tag $tag): bool
    {
        // Admin puede editar todas las tags
        if ($user->is_admin) {
            return true;
        }

        // Usuario normal solo puede editar sus propias tags (no globales)
        return $tag->user_id !== null && $tag->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tag $tag): bool
    {
        // Admin puede eliminar todas las tags
        if ($user->is_admin) {
            return true;
        }

        // Usuario normal solo puede eliminar sus propias tags (no globales)
        return $tag->user_id !== null && $tag->user_id === $user->id;
    }
}
