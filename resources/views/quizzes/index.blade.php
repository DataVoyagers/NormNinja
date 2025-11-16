@extends('layouts.app')

@section('title', 'Quizzes')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Quizzes</h1>
            <p class="text-gray-600 mt-2">
                @if(auth()->user()->isTeacher())
                    Manage your quizzes and track student performance
                @else
                    Take quizzes and test your knowledge
                @endif
            </p>
        </div>
        @if(auth()->user()->isTeacher())
        <a href="{{ route('quizzes.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
            <i class="fas fa-plus mr-2"></i>Create Quiz
        </a>
        @endif
    </div>

    <!-- Quizzes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($quizzes as $quiz)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
            <!-- Quiz Header -->
            <div class="bg-gradient-to-r from-green-500 to-teal-600 p-4 text-white">
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
                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($quiz->description, 100) }}</p>
                @endif

                <!-- Quiz Info -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-question-circle w-5"></i>
                        <span>{{ $quiz->questions->count() }} Questions</span>
                    </div>
                    @if($quiz->duration_minutes)
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock w-5"></i>
                        <span>{{ $quiz->duration_minutes }} Minutes</span>
                    </div>
                    @endif
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-star w-5"></i>
                        <span>Passing Score: {{ $quiz->passing_score }}%</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-trophy w-5"></i>
                        <span>Total Points: {{ $quiz->total_points }}</span>
                    </div>
                </div>

                <!-- Status Badge -->
                @if($quiz->is_published)
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 mb-4">
                        <i class="fas fa-check-circle mr-1"></i>Published
                    </span>
                @else
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 mb-4">
                        <i class="fas fa-clock mr-1"></i>Draft
                    </span>
                @endif

                <!-- Student: Attempts Info -->
                @if(auth()->user()->isStudent())
                    @php
                        $userAttempts = $quiz->attempts()->where('student_id', auth()->id())->get();
                        $bestAttempt = $userAttempts->sortByDesc('percentage')->first();
                    @endphp
                    @if($userAttempts->count() > 0)
                    <div class="bg-blue-50 border border-blue-200 rounded p-2 mb-4">
                        <div class="text-xs text-blue-800 font-semibold">Your Best: {{ $bestAttempt->percentage }}%</div>
                        <div class="text-xs text-blue-600">Attempts: {{ $userAttempts->count() }}</div>
                    </div>
                    @endif
                @endif

                <!-- Teacher: Attempt Stats -->
                @if(auth()->user()->isTeacher())
                <div class="bg-gray-50 border border-gray-200 rounded p-2 mb-4">
                    <div class="text-xs text-gray-600">
                        <i class="fas fa-users mr-1"></i>{{ $quiz->attempts()->distinct('student_id')->count() }} students attempted
                    </div>
                    <div class="text-xs text-gray-600">
                        <i class="fas fa-clipboard-check mr-1"></i>{{ $quiz->attempts()->where('is_completed', true)->count() }} completed
                    </div>
                </div>
                @endif
            </div>

            <!-- Quiz Footer -->
            <div class="bg-gray-50 px-4 py-3 border-t flex items-center justify-between">
                @if(auth()->user()->isTeacher())
                <div class="space-x-2">
                    <a href="{{ route('quizzes.show', $quiz) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('quizzes.edit', $quiz) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
                <a href="{{ route('quizzes.questions.index', $quiz) }}" class="text-green-600 hover:text-green-800 text-sm font-semibold">
                    <i class="fas fa-list"></i> Questions
                </a>
                @else
                <a href="{{ route('quizzes.show', $quiz) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                    <i class="fas fa-info-circle"></i> View Details
                </a>
                @if($quiz->is_published)
                <a href="{{ route('quizzes.start', $quiz) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold transition duration-200">
                    <i class="fas fa-play mr-1"></i>Take Quiz
                </a>
                @endif
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
            <i class="fas fa-clipboard-list text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No quizzes available yet</p>
            @if(auth()->user()->isTeacher())
            <a href="{{ route('quizzes.create') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold mt-2 inline-block">
                Create your first quiz
            </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($quizzes->hasPages())
    <div class="flex justify-center">
        {{ $quizzes->links() }}
    </div>
    @endif
</div>
@endsection
