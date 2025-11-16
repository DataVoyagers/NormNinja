@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-md p-6 text-white">
        <h1 class="text-3xl font-bold">Welcome back, {{ auth()->user()->name }}! 🎓</h1>
        <p class="mt-2">Let's continue your learning journey today!</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Completed Quizzes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Quizzes Done</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['completed_quizzes'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Average Score -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Avg Score</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['average_quiz_score'] }}%</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-chart-line text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Games Played -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Games Played</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['games_played'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-gamepad text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Materials Available -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Materials</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['materials_available'] }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class="fas fa-book text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>

        <!-- Active Forums -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-pink-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Forums</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['active_forums'] }}</p>
                </div>
                <div class="bg-pink-100 rounded-full p-3">
                    <i class="fas fa-comments text-2xl text-pink-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Quiz Attempts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Recent Quiz Results</h2>
                <a href="{{ route('quizzes.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @if($recentQuizAttempts->count() > 0)
                <div class="space-y-3">
                    @foreach($recentQuizAttempts as $attempt)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">{{ $attempt->quiz->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $attempt->completed_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $attempt->percentage }}%
                                </div>
                                <div class="text-xs {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $attempt->passed ? 'Passed' : 'Failed' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-clipboard-list text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">No quiz attempts yet</p>
                    <a href="{{ route('quizzes.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold mt-2 inline-block">
                        Take Your First Quiz
                    </a>
                </div>
            @endif
        </div>

        <!-- Recent Game Attempts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Recent Game Scores</h2>
                <a href="{{ route('games.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @if($recentGameAttempts->count() > 0)
                <div class="space-y-3">
                    @foreach($recentGameAttempts as $attempt)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">{{ $attempt->game->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ gmdate('i:s', $attempt->time_spent_seconds) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ $attempt->score }}
                                </div>
                                <div class="text-xs text-gray-500">points</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-gamepad text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">No games played yet</p>
                    <a href="{{ route('games.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold mt-2 inline-block">
                        Play Your First Game
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Available Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Learning Materials -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-book text-blue-600 mr-2"></i>
                Learning Materials
            </h2>
            @if($availableMaterials->count() > 0)
                <div class="space-y-2">
                    @foreach($availableMaterials as $material)
                    <a href="{{ route('learning-materials.show', $material) }}" class="block p-3 border rounded hover:bg-blue-50 transition duration-200">
                        <div class="font-semibold text-gray-800 text-sm">{{ Str::limit($material->title, 40) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $material->subject }}</div>
                    </a>
                    @endforeach
                </div>
                <a href="{{ route('learning-materials.index') }}" class="block text-center mt-4 text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All Materials
                </a>
            @else
                <p class="text-gray-500 text-center py-4">No materials available</p>
            @endif
        </div>

        <!-- Available Quizzes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-question-circle text-green-600 mr-2"></i>
                Available Quizzes
            </h2>
            @if($availableQuizzes->count() > 0)
                <div class="space-y-2">
                    @foreach($availableQuizzes as $quiz)
                    <a href="{{ route('quizzes.show', $quiz) }}" class="block p-3 border rounded hover:bg-green-50 transition duration-200">
                        <div class="font-semibold text-gray-800 text-sm">{{ Str::limit($quiz->title, 40) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $quiz->questions->count() }} questions</div>
                    </a>
                    @endforeach
                </div>
                <a href="{{ route('quizzes.index') }}" class="block text-center mt-4 text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All Quizzes
                </a>
            @else
                <p class="text-gray-500 text-center py-4">No quizzes available</p>
            @endif
        </div>

        <!-- Available Games -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-gamepad text-purple-600 mr-2"></i>
                Educational Games
            </h2>
            @if($availableGames->count() > 0)
                <div class="space-y-2">
                    @foreach($availableGames as $game)
                    <a href="{{ route('games.show', $game) }}" class="block p-3 border rounded hover:bg-purple-50 transition duration-200">
                        <div class="font-semibold text-gray-800 text-sm">{{ Str::limit($game->title, 40) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ ucfirst(str_replace('_', ' ', $game->game_type)) }}</div>
                    </a>
                    @endforeach
                </div>
                <a href="{{ route('games.index') }}" class="block text-center mt-4 text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All Games
                </a>
            @else
                <p class="text-gray-500 text-center py-4">No games available</p>
            @endif
        </div>
    </div>
</div>
@endsection
