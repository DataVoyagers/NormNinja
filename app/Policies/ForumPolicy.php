<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Forum;

class ForumPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Forum $forum): bool
    {
        if ($user->isTeacher()) {
            return $forum->teacher_id === $user->id;
        }
        
        return $forum->is_active;
    }

    public function create(User $user): bool
    {
        return $user->isTeacher();
    }

    public function update(User $user, Forum $forum): bool
    {
        return $user->isTeacher() && $forum->teacher_id === $user->id;
    }

    public function delete(User $user, Forum $forum): bool
    {
        return $user->isTeacher() && $forum->teacher_id === $user->id;
    }
}
