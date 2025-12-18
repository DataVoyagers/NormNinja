<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Forum;

class ForumPolicy
{
    /**
     * Determine whether the user can view any forums.
     * All users can view the forum list.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view a specific forum.
     * Teachers can view their own forums; students can view only active forums.
     */
    public function view(User $user, Forum $forum): bool
    {
        if ($user->isTeacher()) {
            return $forum->teacher_id === $user->id;
        }
        
        return $forum->is_active;
    }

    /**
     * Determine whether the user can create forums.
     * Only teachers can create forums.
     */
    public function create(User $user): bool
    {
        return $user->isTeacher();
    }

    /**
     * Determine whether the user can update a forum.
     * Only the teacher who owns the forum can update it.
     */
    public function update(User $user, Forum $forum): bool
    {
        return $user->isTeacher() && $forum->teacher_id === $user->id;
    }

    /**
     * Determine whether the user can delete a forum.
     * Only the teacher who owns the forum can delete it.
     */
    public function delete(User $user, Forum $forum): bool
    {
        return $user->isTeacher() && $forum->teacher_id === $user->id;
    }
}
