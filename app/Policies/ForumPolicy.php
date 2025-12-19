<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Forum;

class ForumPolicy
{
    /**
     * Determine whether the user can view any forums
     */
    public function viewAny(User $user): bool
    {
        // Allow all users to view the forum list
        return true;
    }

    /**
     * Determine whether the user can view a specific forum
     */
    public function view(User $user, Forum $forum): bool
    {
        // Teachers can view only forums they created
        if ($user->isTeacher()) {
            return $forum->teacher_id === $user->id;
        }
        
        // Students can view only active forums
        return $forum->is_active;
    }

    /**
     * Determine whether the user can create a forum
     */
    public function create(User $user): bool
    {
        // Only teachers are allowed to create forums
        return $user->isTeacher();
    }

    /**
     * Determine whether the user can update a forum
     */
    public function update(User $user, Forum $forum): bool
    {
        // Only the forum owner (teacher) can update it
        return $user->isTeacher() && $forum->teacher_id === $user->id;
    }

    /**
     * Determine whether the user can delete a forum
     */
    public function delete(User $user, Forum $forum): bool
    {
        // Only the forum owner (teacher) can delete it
        return $user->isTeacher() && $forum->teacher_id === $user->id;
    }
}
