<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forum extends Model
{
    use HasFactory, SoftDeletes;

    // Mass assignable attributes
    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'subject',
        'is_active',
    ];

    // Casts
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the teacher that owns the forum.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get all posts for the forum.
     */
    public function posts()
    {
        return $this->hasMany(ForumPost::class);
    }

    /**
     * Get only top-level posts (no parent).
     */
    public function topLevelPosts()
    {
        return $this->hasMany(ForumPost::class)
                    ->whereNull('parent_id');
    }
}
