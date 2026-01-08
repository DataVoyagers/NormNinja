@extends('layouts.app')

@section('title', 'Discussion Forums')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Discussion Forums</h1>
            <!-- <p class="text-gray-600 mt-2">
                @if(auth()->user()->isTeacher())
                    Create and manage discussion forums for your students
                @else
                    Join discussions and collaborate with your classmates
                @endif
            </p> -->
        </div>
        @if(auth()->user()->isTeacher())
        <a href="{{ route('forums.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md transition duration-200">
            <i class="fas fa-plus mr-2"></i>Create Forum
        </a>
        @endif
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

    <!-- Forums List -->
    <div class="space-y-4">
        @forelse($forums as $forum)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <!-- Forum Info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-pink-100 rounded-full p-3">
                                <i class="fas fa-comments text-2xl text-pink-600"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">
                                    <a href="{{ route('forums.show', $forum) }}" class="hover:text-pink-600 transition">
                                        {{ $forum->title }}
                                    </a>
                                </h3>
                                @if($forum->subject)
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-book mr-1"></i>{{ $forum->subject }}
                                </p>
                                @endif
                            </div>
                        </div>

                        @if($forum->description)
                        <p class="text-gray-600 mb-3 ml-16">{{ $forum->description }}</p>
                        @endif

                        <!-- Forum Meta -->
                        <div class="flex items-center gap-6 ml-16 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-gray-400"></i>
                                <span>{{ $forum->teacher->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                <span>{{ $forum->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-comment-dots mr-2 text-gray-400"></i>
                                <span>{{ $forum->posts()->count() }} posts</span>
                            </div>
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
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 ml-4">
                        @if(auth()->user()->isTeacher() && auth()->id() === $forum->teacher_id)
                        <a href="{{ route('forums.edit', $forum) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 text-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('forums.destroy', $forum) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this forum? All posts will be deleted.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                        
                        @if($forum->is_active || auth()->user()->isTeacher())
                        <a href="{{ route('forums.show', $forum) }}" 
                           class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 text-sm">
                            <i class="fas fa-arrow-right mr-1"></i>View
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Latest Post Preview -->
                @php
                    $latestPost = $forum->posts()->with('user')->latest()->first();
                @endphp
                @if($latestPost)
                <div class="mt-4 pt-4 border-t ml-16">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold text-sm">
                            {{ strtoupper(substr($latestPost->user->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-gray-800 text-sm">{{ $latestPost->user->name }}</span>
                                <span class="text-xs text-gray-500">{{ $latestPost->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $latestPost->content }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <i class="fas fa-comments text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No forums available yet</p>
            @if(auth()->user()->isTeacher())
            <a href="{{ route('forums.create') }}" class="text-pink-600 hover:text-pink-800 font-semibold mt-2 inline-block">
                Create your first forum
            </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($forums->hasPages())
    <div class="flex justify-center mt-8">
        {{ $forums->links() }}
    </div>
    @endif
</div>
@endsection