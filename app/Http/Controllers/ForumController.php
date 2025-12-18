<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumPost;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    /**
     * Display a listing of forums.
     * Teachers see their own forums; students see only active forums.
     */
    public function index()
    {
        if (auth()->user()->isTeacher()) {
            $forums = auth()->user()->forums()->latest()->paginate(15);
        } else {
            $forums = Forum::where('is_active', true)->latest()->paginate(15);
        }
        
        return view('forums.index', compact('forums'));
    }

    /**
     * Show the form for creating a new forum.
     */
    public function create()
    {
        $this->authorize('create', Forum::class);
        return view('forums.create');
    }

    /**
     * Store a newly created forum in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Forum::class);

        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Create forum
        Forum::create([
            'teacher_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'subject' => $request->subject,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('forums.index')->with('success', 'Forum created successfully.');
    }

    /**
     * Display the specified forum and its top-level posts.
     */
    public function show(Forum $forum)
    {
        // Restrict access if forum is inactive and user is not a teacher
        if (!$forum->is_active && !auth()->user()->isTeacher()) {
            abort(403);
        }

        // Load top-level posts with user and replies
        $posts = $forum->topLevelPosts()->with(['user', 'replies.user'])->latest()->paginate(20);

        return view('forums.show', compact('forum', 'posts'));
    }

    /**
     * Show the form for editing the specified forum.
     */
    public function edit(Forum $forum)
    {
        $this->authorize('update', $forum);
        return view('forums.edit', compact('forum'));
    }

    /**
     * Update the specified forum in storage.
     */
    public function update(Request $request, Forum $forum)
    {
        $this->authorize('update', $forum);

        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Update forum
        $forum->update($request->all());

        return redirect()->route('forums.index')->with('success', 'Forum updated successfully.');
    }

    /**
     * Remove the specified forum from storage.
     */
    public function destroy(Forum $forum)
    {
        $this->authorize('delete', $forum);
        $forum->delete();

        return redirect()->route('forums.index')->with('success', 'Forum deleted successfully.');
    }

    /**
     * Store a new post in a forum.
     */
    public function storePost(Request $request, Forum $forum)
    {
        // Restrict posting if forum is inactive and user is not a teacher
        if (!$forum->is_active && !auth()->user()->isTeacher()) {
            abort(403);
        }

        // Validate post input
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:forum_posts,id',
        ]);

        // Create forum post
        ForumPost::create([
            'forum_id' => $forum->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return redirect()->route('forums.show', $forum)->with('success', 'Post created successfully.');
    }

    /**
     * Delete a post from a forum.
     */
    public function deletePost(Forum $forum, ForumPost $post)
    {
        // Only the post owner or a teacher can delete
        if ($post->user_id !== auth()->id() && !auth()->user()->isTeacher()) {
            abort(403);
        }

        // Ensure the post belongs to the forum
        if ($post->forum_id !== $forum->id) {
            abort(404);
        }

        $post->delete();

        return redirect()->route('forums.show', $forum)->with('success', 'Post deleted successfully.');
    }
}
