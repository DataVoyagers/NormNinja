@extends('layouts.app')

@section('title', 'Quizzes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Quizzes</h1>
            <p class="text-gray-600 mt-2">
                @if(auth()->user()->isTeacher())
                    Create and manage quizzes for your students
                @else
                    Take quizzes to test your knowledge
                @endif
            </p>
        </div>
        @if(auth()->user()->isTeacher())
        <a href="{{ route('quizzes.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md transition duration-200">
            <i class="fas fa-plus mr-2"></i>Create Quiz
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

    <!-- Quizzes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($quizzes as $quiz)
        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
            <!-- Quiz Header -->
            <div class="bg-gradient-to-r from-green-500 to-teal-600 p-4 text-white">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold uppercase tracking-wide">
                        <i class="fas fa-clipboard-list mr-1"></i>Quiz
                    </span>
                    @if(!$quiz->is_published)
                    <span class="bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs font-semibold">
                        Draft
                    </span>
                    @endif
                </div>
                <h3 class="font-bold text-lg">{{ $quiz->title }}</h3>
                @if($quiz->subject)
                <p class="text-sm text-green-100 mt-1">
                    <i class="fas fa-book mr-1"></i>{{ $quiz->subject }}
                </p>
                @endif
            </div>

            <!-- Quiz Body -->
            <div class="p-4">
                <!-- Description -->
                @if($quiz->description)
                <p class="text-gray-600 mb-4 text-sm line-clamp-2">{{ $quiz->description }}</p>
                @endif

                <!-- Quiz Info -->
                <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                    <div class="bg-gray-50 rounded p-2">
                        <p class="text-gray-500 text-xs">Questions</p>
                        <p class="font-semibold text-gray-800">{{ $quiz->questions()->count() }}</p>
                    </div>
                    <div class="bg-gray-50 rounded p-2">
                        <p class="text-gray-500 text-xs">Points</p>
                        <p class="font-semibold text-gray-800">{{ $quiz->total_points }}</p>
                    </div>
                    @if($quiz->duration_minutes)
                    <div class="bg-gray-50 rounded p-2">
                        <p class="text-gray-500 text-xs">Duration</p>
                        <p class="font-semibold text-gray-800">{{ $quiz->duration_minutes }} min</p>
                    </div>
                    @endif
                    <div class="bg-gray-50 rounded p-2">
                        <p class="text-gray-500 text-xs">Passing Score</p>
                        <p class="font-semibold text-gray-800">{{ $quiz->passing_score }}%</p>
                    </div>
                </div>

                <!-- Teacher Info -->
                <div class="border-t pt-3 mb-3 text-xs text-gray-500">
                    <p><i class="fas fa-user mr-1"></i>{{ $quiz->teacher->name }}</p>
                </div>

                <!-- Student's Best Score -->
                @if(auth()->user()->isStudent())
                    @php
                        $bestAttempt = $quiz->attempts()
                            ->where('student_id', auth()->id())
                            ->where('is_completed', true)
                            ->orderBy('score', 'desc')
                            ->first();
                    @endphp
                    @if($bestAttempt)
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-2 mb-3">
                        <p class="text-xs text-blue-700">Your Best Score</p>
                        <p class="text-lg font-bold text-blue-800">{{ $bestAttempt->percentage }}%</p>
                    </div>
                    @endif
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-2">
                        <a href="{{ route('quizzes.show', $quiz) }}" 
                           class="flex-1 text-center text-blue-600 hover:text-blue-800 px-3 py-2 rounded text-sm font-semibold border border-blue-600 hover:bg-blue-50 transition duration-200">
                            <i class="fas fa-info-circle mr-1"></i>Details
                        </a>
                        @if($quiz->is_published)
                        <a href="{{ route('quizzes.start', $quiz) }}" 
                           class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm font-semibold transition duration-200">
                            <i class="fas fa-play mr-1"></i>Take Quiz
                        </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
            <i class="fas fa-clipboard-list text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No quizzes available yet</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($quizzes->hasPages())
    <div class="flex justify-center mt-8">
        {{ $quizzes->links() }}
    </div>
    @endif
</div>
@endsection
