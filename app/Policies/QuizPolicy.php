<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Quiz;

class QuizPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Quiz $quiz): bool
    {
        if ($user->isTeacher()) {
            return $quiz->teacher_id === $user->id;
        }
        
        return $quiz->is_published;
    }

    public function take(User $user, Quiz $quiz): bool
    {
        return $user->isStudent() && $quiz->is_published;
    }
}
