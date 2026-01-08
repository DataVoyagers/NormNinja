@extends('layouts.app')

@section('title', 'Games')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Educational Games</h1>
            <!-- <p class="text-gray-600 mt-2">
                @if(auth()->user()->isTeacher())
                    Manage and create interactive learning games
                @else
                    Play games to learn and practice
                @endif
            </p> -->
        </div>
        <div class="flex gap-3">
            @if(auth()->user()->isStudent())
                <a href="{{ route('games.leaderboard') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold shadow-md transition duration-200">
                    <i class="fas fa-trophy mr-2"></i>View Leaderboard
                </a>
            @endif
            @if(auth()->user()->isTeacher())
                <a href="{{ route('games.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Create Game
                </a>
            @endif
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

    <!-- Games Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($games as $game)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
            <!-- Game Type Badge -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4">
                <div class="flex items-center justify-between">
                    <span class="text-white font-semibold text-lg">
                        @switch($game->game_type)
                            @case('flashcard')
                                <i class="fas fa-layer-group mr-2"></i>Flashcards
                                @break
                            @case('matching')
                                <i class="fas fa-puzzle-piece mr-2"></i>Matching
                                @break
                            @case('gamified_quiz')
                                <i class="fas fa-gamepad mr-2"></i>Gamified Quiz
                                @break
                        @endswitch
                    </span>
                    @if(!$game->is_published)
                    <span class="bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs font-semibold">
                        Draft
                    </span>
                    @endif
                </div>
            </div>

            <!-- Game Content -->
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $game->title }}</h3>
                
                @if($game->subject)
                <p class="text-sm text-gray-500 mb-3">
                    <i class="fas fa-book mr-1"></i>{{ $game->subject }}
                </p>
                @endif

                <p class="text-gray-600 mb-4 line-clamp-2">
                    {{ $game->description ?? 'No description provided' }}
                </p>

                <!-- Game Stats -->
                <div class="border-t pt-4 mb-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Created By</p>
                            <p class="font-semibold text-gray-800">{{ $game->teacher->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Attempts</p>
                            <p class="font-semibold text-gray-800">{{ $game->attempts()->count() }}</p>
                        </div>
                    </div>
                    
                    @if(auth()->user()->isStudent() && $game->attempts()->where('student_id', auth()->id())->exists())
                    <div class="mt-3 pt-3 border-t">
                        <p class="text-sm text-gray-500">Your Best Score</p>
                        <p class="text-lg font-bold text-green-600">
                            {{ $game->attempts()->where('student_id', auth()->id())->where('is_completed', true)->max('score') ?? 'N/A' }}
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    @if(auth()->user()->isTeacher())
                        <a href="{{ route('games.edit', $game) }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-semibold transition duration-200">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <a href="{{ route('games.statistics', $game) }}" class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold transition duration-200">
                            <i class="fas fa-chart-bar mr-1"></i>Stats
                        </a>
                        <form action="{{ route('games.destroy', $game) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this game?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-semibold transition duration-200">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('games.show', $game) }}" class="flex-1 text-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded text-sm font-semibold border border-blue-600 hover:bg-blue-50 transition duration-200">
                            <i class="fas fa-info-circle mr-1"></i>Details
                        </a>
                        @if($game->is_published)
                        <a href="{{ route('games.play', $game) }}" class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold transition duration-200">
                            <i class="fas fa-play mr-1"></i>Play Now
                        </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
            <i class="fas fa-gamepad text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No games available yet</p>
            @if(auth()->user()->isTeacher())
            <a href="{{ route('games.create') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold mt-2 inline-block">
                Create your first game
            </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($games->hasPages())
    <div class="flex justify-center mt-8">
        {{ $games->links() }}
    </div>
    @endif
</div>
@endsection