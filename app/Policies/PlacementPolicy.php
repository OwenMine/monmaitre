<?php

namespace App\Policies;

use App\Models\Placement;
use App\Models\User;

class PlacementPolicy
{
    public function view(User $user, Placement $placement): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isParent() && $placement->parent_id === $user->id) {
            return true;
        }

        if ($user->isRepetiteur() && $placement->repetiteur->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function update(User $user, Placement $placement): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Placement $placement): bool
    {
        return $user->isAdmin();
    }
}