<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumPost extends Model
{
    use HasFactory, SoftDeletes;

    // Mass assignable attributes
    protected $fillable = [
        'forum_id',   // ID of the forum this post belongs to
        'user_id',    // ID of the user who created the post
        'parent_id',  // ID of the parent post (for replies)
        'content',    // Content of the post
    ];

    /**
     * The forum this post belongs to.
     */
    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    /**
     * The user who created this post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The parent post (if this is a reply).
     */
    public function parent()
    {
        return $this->belongsTo(ForumPost::class, 'parent_id');
    }

    /**
     * The replies to this post.
     */
    public function replies()
    {
        return $this->hasMany(ForumPost::class, 'parent_id');
    }
}
