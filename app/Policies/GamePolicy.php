<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;

class GamePolicy
{
    public function create(User $user)
    {
        return $user->isTeacher();
    }

    public function update(User $user, Game $game)
    {
        return $user->id === $game->teacher_id;
    }

    public function delete(User $user, Game $game)
    {
        return $user->id === $game->teacher_id;
    }
}