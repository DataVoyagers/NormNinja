<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Game;

class GamePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Game $game): bool
    {
        if ($user->isTeacher()) {
            return $game->teacher_id === $user->id;
        }
        
        return $game->is_published;
    }

    public function create(User $user): bool
    {
        return $user->isTeacher();
    }

    public function update(User $user, Game $game): bool
    {
        return $user->isTeacher() && $game->teacher_id === $user->id;
    }

    public function delete(User $user, Game $game): bool
    {
        return $user->isTeacher() && $game->teacher_id === $user->id;
    }
}
