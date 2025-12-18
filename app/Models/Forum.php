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
        'teacher_id',   // ID of the teacher who created the forum
        'title',        // Title of the forum
        'description',  // Description of the forum
        'subject',      // Subject or category of the forum
        'is_active',    // Whether the forum is active
    ];

    // Attribute casting
    protected $casts = [
        'is_active' => 'boolean', // Cast is_active to boolean
    ];

    /**
     * Get the teacher (user) that owns the forum.
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
     * Get only top-level posts (posts without a parent).
     */
    public function topLevelPosts()
    {
        return $this->hasMany(ForumPost::class)
                    ->whereNull('parent_id');
    }
}
