@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md p-8 text-white">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-2">{{ $quiz->title }}</h1>
                @if($quiz->subject)
                <p class="text-green-100 text-lg">{{ $quiz->subject }}</p>
                @endif
            </div>
            @if($quiz->is_published)
                <span class="bg-white text-green-600 px-4 py-2 rounded-full text-sm font-semibold">
                    <i class="fas fa-check-circle mr-1"></i>Published
                </span>
            @endif
        </div>
    </div>

    <!-- Quiz Information -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Quiz Information</h2>

        @if($quiz->description)
        <div class="mb-6">
            <h3 class="font-semibold text-gray-700 mb-2">Description</h3>
            <p class="text-gray-600">{{ $quiz->description }}</p>
        </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <i class="fas fa-question-circle text-blue-600 text-3xl mb-2"></i>
                <div class="text-2xl font-bold text-gray-800">{{ $quiz->questions->count() }}</div>
                <div class="text-sm text-gray-600">Questions</div>
            </div>

            @if($quiz->duration_minutes)
            <div class="bg-orange-50 rounded-lg p-4 text-center">
                <i class="fas fa-clock text-orange-600 text-3xl mb-2"></i>
                <div class="text-2xl font-bold text-gray-800">{{ $quiz->duration_minutes }}</div>
                <div class="text-sm text-gray-600">Minutes</div>
            </div>
            @endif

            <div class="bg-green-50 rounded-lg p-4 text-center">
                <i class="fas fa-check-circle text-green-600 text-3xl mb-2"></i>
                <div class="text-2xl font-bold text-gray-800">{{ $quiz->passing_score }}%</div>
                <div class="text-sm text-gray-600">Passing Score</div>
            </div>

            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <i class="fas fa-star text-purple-600 text-3xl mb-2"></i>
                <div class="text-2xl font-bold text-gray-800">{{ $quiz->total_points }}</div>
                <div class="text-sm text-gray-600">Total Points</div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isStudent())
        <!-- Previous Attempts -->
        @if($userAttempts && $userAttempts->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Previous Attempts</h2>
            <div class="space-y-3">
                @foreach($userAttempts as $attempt)
                <div class="border rounded-lg p-4 flex items-center justify-between hover:bg-gray-50">
                    <div>
                        <div class="font-semibold text-gray-800">
                            Attempt #{{ $loop->iteration }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $attempt->completed_at->format('M d, Y at h:i A') }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                            {{ $attempt->percentage }}%
                        </div>
                        <div class="text-sm {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                            {{ $attempt->passed ? 'Passed' : 'Failed' }}
                        </div>
                        <a href="{{ route('quizzes.result', ['quiz' => $quiz, 'attempt' => $attempt]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm mt-1 inline-block">
                            View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Start Quiz Button -->
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            @if($quiz->questions->count() > 0)
                <p class="text-gray-600 mb-4">Ready to test your knowledge?</p>
                <form action="{{ route('quizzes.start', $quiz) }}" method="GET">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-bold text-lg transition duration-200">
                        <i class="fas fa-play-circle mr-2"></i>Start Quiz
                    </button>
                </form>
            @else
                <p class="text-red-600">This quiz has no questions yet. Please check back later.</p>
            @endif
        </div>
    @else
        <!-- Teacher Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Quiz Management</h2>
            <div class="flex space-x-4">
                <a href="{{ route('quizzes.questions.index', $quiz) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold text-center transition duration-200">
                    <i class="fas fa-list mr-2"></i>Manage Questions
                </a>
                <a href="{{ route('quizzes.edit', $quiz) }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold text-center transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit Quiz
                </a>
            </div>
        </div>

        <!-- Quiz Statistics -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-1">Total Attempts</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $quiz->attempts->count() }}</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-1">Average Score</div>
                    <div class="text-2xl font-bold text-gray-800">
                        {{ $quiz->attempts->where('is_completed', true)->count() > 0 ? round($quiz->attempts->where('is_completed', true)->avg('percentage'), 1) : 0 }}%
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-1">Pass Rate</div>
                    <div class="text-2xl font-bold text-gray-800">
                        @php
                            $completed = $quiz->attempts->where('is_completed', true);
                            $passRate = $completed->count() > 0 ? round(($completed->where('percentage', '>=', $quiz->passing_score)->count() / $completed->count()) * 100, 1) : 0;
                        @endphp
                        {{ $passRate }}%
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <div class="text-center">
        <a href="{{ route('quizzes.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>Back to Quizzes
        </a>
    </div>
</div>
@endsection
