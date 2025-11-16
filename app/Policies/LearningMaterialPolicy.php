<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LearningMaterial;

class LearningMaterialPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view the list
    }

    public function view(User $user, LearningMaterial $learningMaterial): bool
    {
        // Teachers can view their own materials, students can view published materials
        if ($user->isTeacher()) {
            return $learningMaterial->teacher_id === $user->id;
        }
        
        return $learningMaterial->is_published;
    }

    public function create(User $user): bool
    {
        return $user->isTeacher();
    }

    public function update(User $user, LearningMaterial $learningMaterial): bool
    {
        return $user->isTeacher() && $learningMaterial->teacher_id === $user->id;
    }

    public function delete(User $user, LearningMaterial $learningMaterial): bool
    {
        return $user->isTeacher() && $learningMaterial->teacher_id === $user->id;
    }
}
