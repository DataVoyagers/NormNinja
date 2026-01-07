@extends('layouts.app')

@section('title', $forum->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('forums.index') }}" class="text-pink-600 hover:text-pink-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Forums
            </a>
            
            @if(auth()->user()->isTeacher() && auth()->id() === $forum->teacher_id)
            <div class="flex gap-2">
                <a href="{{ route('forums.edit', $forum) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit Forum
                </a>
            </div>
            @endif
        </div>

        <!-- Forum Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-4 flex-1">
                    <div class="bg-pink-100 rounded-full p-4">
                        <i class="fas fa-comments text-3xl text-pink-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-3xl font-bold text-gray-800">{{ $forum->title }}</h1>
                            @if(!$forum->is_active)
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                <i class="fas fa-lock mr-1"></i>Closed
                            </span>
                            @else
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>Active
                            </span>
                            @endif
                        </div>
                        
                        @if($forum->description)
                        <p class="text-gray-600 mb-3">{{ $forum->description }}</p>
                        @endif
                        
                        <div class="flex items-center gap-6 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-gray-400"></i>
                                <span>{{ $forum->teacher->name }}</span>
                            </div>
                            @if($forum->subject)
                            <div class="flex items-center">
                                <i class="fas fa-book mr-2 text-gray-400"></i>
                                <span>{{ $forum->subject }}</span>
                            </div>
                            @endif
                            <div class="flex items-center">
                                <i class="fas fa-comment-dots mr-2 text-gray-400"></i>
                                <span>{{ $forum->posts()->count() }} posts</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <p>{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- New Post Form -->
            @if($forum->is_active || auth()->user()->isTeacher())
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-pen mr-2 text-pink-600"></i>Start a Discussion
                </h2>
                <form action="{{ route('forums.posts.store', $forum) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <textarea name="content" 
                                  rows="4" 
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('content') border-red-500 @enderror"
                                  placeholder="Share your thoughts, ask a question, or start a discussion..."
                                  required></textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                            <i class="fas fa-paper-plane mr-2"></i>Post
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded">
                <div class="flex">
                    <i class="fas fa-lock text-yellow-500 text-xl mr-3"></i>
                    <div>
                        <h3 class="font-semibold text-yellow-800">Forum is Closed</h3>
                        <p class="text-yellow-700 text-sm mt-1">This forum is currently closed for new posts.</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Posts List -->
            <div class="space-y-6">
                @forelse($posts as $post)
                <div class="bg-white rounded-lg shadow-md overflow-hidden" id="post-{{ $post->id }}">
                    <!-- Post Content -->
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr($post->user->name, 0, 2)) }}
                                </div>
                            </div>

                            <!-- Post Body -->
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $post->user->name }}</h3>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>{{ $post->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    
                                    @if($post->user_id === auth()->id() || auth()->user()->isTeacher())
                                    <div class="flex gap-2">
                                        @if($post->user_id === auth()->id())
                                        <button onclick="toggleEditForm({{ $post->id }})" 
                                                class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endif
                                        
                                        <form action="{{ route('forums.posts.destroy', [$forum, $post]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>

                                <p class="text-gray-700 whitespace-pre-wrap">{{ $post->content }}</p>

                                <!-- Edit Form (Hidden by default) -->
                                <div id="edit-form-{{ $post->id }}" class="hidden mt-4 bg-blue-50 rounded-lg p-4">
                                    <form action="{{ route('forums.posts.update', [$forum, $post]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <textarea name="content" 
                                                      rows="4" 
                                                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                      required>{{ $post->content }}</textarea>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" 
                                                    onclick="toggleEditForm({{ $post->id }})"
                                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold">
                                                Cancel
                                            </button>
                                            <button type="submit" 
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                                <i class="fas fa-save mr-1"></i>Update
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Reply Button -->
                                @if($forum->is_active || auth()->user()->isTeacher())
                                <button onclick="toggleReplyForm({{ $post->id }})" 
                                        class="mt-3 text-pink-600 hover:text-pink-800 text-sm font-semibold">
                                    <i class="fas fa-reply mr-1"></i>Reply
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- Reply Form (Hidden by default) -->
                        @if($forum->is_active || auth()->user()->isTeacher())
                        <div id="reply-form-{{ $post->id }}" class="hidden mt-4 ml-16 bg-gray-50 rounded-lg p-4">
                            <form action="{{ route('forums.posts.store', $forum) }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $post->id }}">
                                <div class="mb-3">
                                    <textarea name="content" 
                                              rows="3" 
                                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                              placeholder="Write your reply..."
                                              required></textarea>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" 
                                            onclick="toggleReplyForm({{ $post->id }})"
                                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                        <i class="fas fa-paper-plane mr-1"></i>Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>

                    <!-- Replies -->
                    @if($post->replies && $post->replies->count() > 0)
                    <div class="bg-gray-50 px-6 py-4 border-t">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-comments mr-2"></i>{{ $post->replies->count() }} 
                            {{ $post->replies->count() === 1 ? 'Reply' : 'Replies' }}
                        </h4>
                        <div class="space-y-4">
                            @foreach($post->replies as $reply)
                            <div class="flex items-start gap-3 bg-white rounded-lg p-4">
                                <!-- Reply Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                                    </div>
                                </div>

                                <!-- Reply Body -->
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <div>
                                            <span class="font-semibold text-gray-800 text-sm">{{ $reply->user->name }}</span>
                                            <span class="text-xs text-gray-500 ml-2">
                                                {{ $reply->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        
                                        @if($reply->user_id === auth()->id() || auth()->user()->isTeacher())
                                        <div class="flex gap-2">
                                            @if($reply->user_id === auth()->id())
                                            <button onclick="toggleEditReply({{ $reply->id }})" 
                                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif
                                            
                                            <form action="{{ route('forums.posts.destroy', [$forum, $reply]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this reply?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ $reply->content }}</p>

                                    <!-- Edit Reply Form (Hidden by default) -->
                                    <div id="edit-reply-{{ $reply->id }}" class="hidden mt-3 bg-blue-50 rounded-lg p-3">
                                        <form action="{{ route('forums.posts.update', [$forum, $reply]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-2">
                                                <textarea name="content" 
                                                          rows="3" 
                                                          class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
                                                          required>{{ $reply->content }}</textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" 
                                                        onclick="toggleEditReply({{ $reply->id }})"
                                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded text-sm">
                                                    Cancel
                                                </button>
                                                <button type="submit" 
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                                    <i class="fas fa-save mr-1"></i>Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-12 bg-white rounded-lg shadow">
                    <i class="fas fa-comment-slash text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">No discussions yet</p>
                    <p class="text-gray-400 mt-2">Be the first to start a discussion!</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
            <div class="flex justify-center mt-8">
                {{ $posts->links() }}
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Forum Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 top-4">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle mr-2 text-pink-600"></i>Forum Info
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Created By</p>
                        <p class="font-semibold text-gray-800">{{ $forum->teacher->name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Created On</p>
                        <p class="font-semibold text-gray-800">{{ $forum->created_at->format('M d, Y') }}</p>
                    </div>
                    
                    <div class="pt-4 border-t">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="bg-pink-50 rounded-lg p-3">
                                <p class="text-2xl font-bold text-pink-600">{{ $forum->topLevelPosts()->count() }}</p>
                                <p class="text-xs text-gray-600">Discussions</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3">
                                <p class="text-2xl font-bold text-blue-600">{{ $forum->posts()->count() }}</p>
                                <p class="text-xs text-gray-600">Total Posts</p>
                            </div>
                        </div>
                    </div>

                    @if($forum->is_active)
                    <div class="pt-4 border-t">
                        <div class="bg-green-50 rounded-lg p-3 text-center">
                            <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                            <p class="text-sm font-semibold text-green-800">Forum is Active</p>
                            <p class="text-xs text-green-600 mt-1">Students can post and reply</p>
                        </div>
                    </div>
                    @else
                    <div class="pt-4 border-t">
                        <div class="bg-red-50 rounded-lg p-3 text-center">
                            <i class="fas fa-lock text-red-600 text-2xl mb-2"></i>
                            <p class="text-sm font-semibold text-red-800">Forum is Closed</p>
                            <p class="text-xs text-red-600 mt-1">No new posts allowed</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Guidelines -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>Guidelines
                </h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                        <span>Be respectful and courteous</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                        <span>Stay on topic</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                        <span>Use clear language</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                        <span>Help others learn</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-times text-red-600 mr-2 mt-1"></i>
                        <span>No spam or inappropriate content</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function toggleReplyForm(postId) {
    const form = document.getElementById('reply-form-' + postId);
    if (form.classList.contains('hidden')) {
        // Hide all other forms first
        document.querySelectorAll('[id^="reply-form-"], [id^="edit-form-"], [id^="edit-reply-"]').forEach(f => {
            f.classList.add('hidden');
        });
        form.classList.remove('hidden');
        form.querySelector('textarea').focus();
    } else {
        form.classList.add('hidden');
    }
}

function toggleEditForm(postId) {
    const form = document.getElementById('edit-form-' + postId);
    if (form.classList.contains('hidden')) {
        // Hide all other forms first
        document.querySelectorAll('[id^="reply-form-"], [id^="edit-form-"], [id^="edit-reply-"]').forEach(f => {
            f.classList.add('hidden');
        });
        form.classList.remove('hidden');
        form.querySelector('textarea').focus();
    } else {
        form.classList.add('hidden');
    }
}

function toggleEditReply(replyId) {
    const form = document.getElementById('edit-reply-' + replyId);
    if (form.classList.contains('hidden')) {
        // Hide all other forms first
        document.querySelectorAll('[id^="reply-form-"], [id^="edit-form-"], [id^="edit-reply-"]').forEach(f => {
            f.classList.add('hidden');
        });
        form.classList.remove('hidden');
        form.querySelector('textarea').focus();
    } else {
        form.classList.add('hidden');
    }
}
</script>
@endsection