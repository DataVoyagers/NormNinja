<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumPost;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        if (auth()->user()->isTeacher()) {
            $forums = auth()->user()->forums()->latest()->paginate(15);
        } else {
            $forums = Forum::where('is_active', true)->latest()->paginate(15);
        }
        
        return view('forums.index', compact('forums'));
    }

    public function create()
    {
        $this->authorize('create', Forum::class);
        return view('forums.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Forum::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Forum::create([
            'teacher_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'subject' => $request->subject,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('forums.index')->with('success', 'Forum created successfully.');
    }

    public function show(Forum $forum)
    {
        if (!$forum->is_active && !auth()->user()->isTeacher()) {
            abort(403);
        }

        $posts = $forum->topLevelPosts()->with(['user', 'replies.user'])->latest()->paginate(20);

        return view('forums.show', compact('forum', 'posts'));
    }

    public function edit(Forum $forum)
    {
        $this->authorize('update', $forum);
        return view('forums.edit', compact('forum'));
    }

    public function update(Request $request, Forum $forum)
    {
        $this->authorize('update', $forum);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $forum->update($request->all());

        return redirect()->route('forums.index')->with('success', 'Forum updated successfully.');
    }

    public function destroy(Forum $forum)
    {
        $this->authorize('delete', $forum);
        $forum->delete();

        return redirect()->route('forums.index')->with('success', 'Forum deleted successfully.');
    }

    public function storePost(Request $request, Forum $forum)
    {
        if (!$forum->is_active && !auth()->user()->isTeacher()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:forum_posts,id',
        ]);

        ForumPost::create([
            'forum_id' => $forum->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return redirect()->route('forums.show', $forum)->with('success', 'Post created successfully.');
    }

    public function deletePost(Forum $forum, ForumPost $post)
    {
        if ($post->user_id !== auth()->id() && !auth()->user()->isTeacher()) {
            abort(403);
        }

        if ($post->forum_id !== $forum->id) {
            abort(404);
        }

        $post->delete();

        return redirect()->route('forums.show', $forum)->with('success', 'Post deleted successfully.');
    }
}
