<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forum extends Model
{
    // Enable factory support and soft delete functionality
    use HasFactory, SoftDeletes;

    /**
     * Fields that are allowed for mass assignment
     */
    protected $fillable = [
        'teacher_id',   // ID of the teacher who created the forum
        'title',        // Forum title
        'description',  // Forum description/content
        'subject',      // Subject/category of the forum
        'is_active',    // Forum status (active/inactive)
    ];

    /**
     * Cast attributes to specific data types
     */
    protected $casts = [
        'is_active' => 'boolean', // Convert is_active to boolean automatically
    ];

    /**
     * Relationship: Forum belongs to a teacher (User)
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relationship: Forum has many posts
     */
    public function posts()
    {
        return $this->hasMany(ForumPost::class);
    }

    /**
     * Relationship: Get only top-level posts (no parent)
     */
    public function topLevelPosts()
    {
        return $this->hasMany(ForumPost::class)
                    ->whereNull('parent_id');
    }
}
