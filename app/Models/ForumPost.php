<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumPost extends Model
{
    // Enable factory support and soft delete functionality
    use HasFactory, SoftDeletes;

    /**
     * Fields that are allowed for mass assignment
     */
    protected $fillable = [
        'forum_id',   // ID of the forum this post belongs to
        'user_id',    // ID of the user who created the post
        'parent_id',  // ID of parent post (null if top-level post)
        'content',    // Post content/text
    ];

    /**
     * Relationship: Post belongs to a forum
     */
    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    /**
     * Relationship: Post belongs to a user (author)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Get the parent post (for replies)
     */
    public function parent()
    {
        return $this->belongsTo(ForumPost::class, 'parent_id');
    }

    /**
     * Relationship: Get all replies to this post
     */
    public function replies()
    {
        return $this->hasMany(ForumPost::class, 'parent_id');
    }
}
