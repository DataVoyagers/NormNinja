@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('quizzes.index') }}" class="text-green-600 hover:text-green-800 mr-4">
                <i class="fas fa-arrow-left"></i> Back to Quizzes
            </a>
            @if(auth()->user()->isTeacher() && auth()->id() === $quiz->teacher_id)
        <div class="flex gap-2">
            <a href="{{ route('quizzes.questions.index', $quiz) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-list mr-2"></i>Manage Questions
            </a>
            <a href="{{ route('quizzes.edit', $quiz) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-edit mr-2"></i>Edit Quiz
            </a>
        </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2">
        <!-- Quiz Info Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-teal-600 p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold uppercase tracking-wide">
                        <i class="fas fa-clipboard-list mr-2"></i>Quiz
                    </span>
                    @if(!$quiz->is_published)
                    <span class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold">
                        DRAFT
                    </span>
                    @else
                    <span class="bg-white text-green-600 px-3 py-1 rounded-full text-xs font-bold">
                        PUBLISHED
                    </span>
                    @endif
                </div>
                <h1 class="text-3xl font-bold">{{ $quiz->title }}</h1>
                @if($quiz->subject)
                <p class="text-green-100 mt-2">
                    <i class="fas fa-book mr-2"></i>{{ $quiz->subject }}
                </p>
                @endif
            </div>

            <!-- Details -->
            <div class="p-6">
                @if($quiz->description)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                    <p class="text-gray-600">{{ $quiz->description }}</p>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500 mb-1">Created By</p>
                        <p class="font-semibold text-gray-800">{{ $quiz->teacher->name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500 mb-1">Created</p>
                        <p class="font-semibold text-gray-800">{{ $quiz->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Availability -->
                @if($quiz->available_from || $quiz->available_until)
                <div class="border-t pt-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Availability</h3>
                    <div class="space-y-2">
                        @if($quiz->available_from)
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Available from: {{ $quiz->available_from->format('M d, Y h:i A') }}
                        </p>
                        @endif
                        @if($quiz->available_until)
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-calendar-times mr-2"></i>
                            Available until: {{ $quiz->available_until->format('M d, Y h:i A') }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Student Attempts History -->
        @if(auth()->user()->isStudent() && $userAttempts && $userAttempts->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-history mr-2 text-green-600"></i>Your Attempts
            </h2>
            <div class="space-y-3">
                @foreach($userAttempts->sortByDesc('created_at') as $attempt)
                <div class="border rounded-lg p-4 {{ $attempt->passed ? 'bg-green-50 border-green-200' : 'bg-gray-50' }}">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $attempt->created_at->format('M d, Y h:i A') }}
                            </p>
                            @if($attempt->is_completed)
                            <p class="text-2xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-orange-600' }}">
                                {{ $attempt->percentage }}%
                            </p>
                            @else
                            <p class="text-sm text-yellow-600">In Progress</p>
                            @endif
                        </div>
                        @if($attempt->is_completed)
                        <div class="text-right">
                            @if($attempt->passed)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">
                                <i class="fas fa-check mr-1"></i>PASSED
                            </span>
                            @else
                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-bold">
                                NEEDS IMPROVEMENT
                            </span>
                            @endif
                            <a href="{{ route('quizzes.result', [$quiz, $attempt]) }}" 
                               class="block mt-2 text-green-600 hover:text-green-800 text-sm font-semibold">
                                View Results <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Take Quiz Card (Students) -->
        @if(auth()->user()->isStudent() && $quiz->is_published)
        <div class="bg-gradient-to-br from-green-500 to-teal-600 rounded-lg shadow-lg p-6 text-white mb-6">
            <h3 class="text-xl font-bold mb-4">Ready to Start?</h3>
            <div class="space-y-3 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-question-circle w-6"></i>
                    <span class="ml-2">{{ $quiz->questions()->count() }} Questions</span>
                </div>
                @if($quiz->duration_minutes)
                <div class="flex items-center">
                    <i class="fas fa-clock w-6"></i>
                    <span class="ml-2">{{ $quiz->duration_minutes }} Minutes</span>
                </div>
                @endif
                <div class="flex items-center">
                    <i class="fas fa-star w-6"></i>
                    <span class="ml-2">{{ $quiz->total_points }} Points</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-trophy w-6"></i>
                    <span class="ml-2">{{ $quiz->passing_score }}% to Pass</span>
                </div>
            </div>
            <a href="{{ route('quizzes.start', $quiz) }}" 
               class="block w-full bg-white text-green-600 text-center px-6 py-3 rounded-lg font-bold hover:bg-green-50 transition duration-200">
                <i class="fas fa-play mr-2"></i>Start Quiz
            </a>
        </div>
        @endif

        <!-- Quiz Stats -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Quiz Information</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-gray-600">Questions</span>
                        <span class="font-bold text-gray-800">{{ $quiz->questions()->count() }}</span>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-gray-600">Total Points</span>
                        <span class="font-bold text-gray-800">{{ $quiz->total_points }}</span>
                    </div>
                </div>
                @if($quiz->duration_minutes)
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-gray-600">Time Limit</span>
                        <span class="font-bold text-gray-800">{{ $quiz->duration_minutes }} min</span>
                    </div>
                </div>
                @endif
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-gray-600">Passing Score</span>
                        <span class="font-bold text-green-600">{{ $quiz->passing_score }}%</span>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-gray-600">Total Attempts</span>
                        <span class="font-bold text-gray-800">{{ $quiz->attempts()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Score (Students) -->
        @if(auth()->user()->isStudent() && $userAttempts && $userAttempts->where('is_completed', true)->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Best Score</h3>
            @php
                $bestAttempt = $userAttempts->where('is_completed', true)->sortByDesc('score')->first();
            @endphp
            <div class="text-center">
                <div class="text-5xl font-bold text-green-600 mb-2">{{ $bestAttempt->percentage }}%</div>
                <p class="text-gray-600">{{ $bestAttempt->score }} / {{ $bestAttempt->total_points }} points</p>
                <p class="text-sm text-gray-500 mt-2">{{ $bestAttempt->created_at->diffForHumans() }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
@endsection
