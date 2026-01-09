@extends('layouts.app')

@section('title', 'Manage Questions - ' . $quiz->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('quizzes.index') }}" class="text-green-600 hover:text-green-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Quizzes
            </a>
            <div class="flex gap-2">
                <a href="{{ route('quizzes.show', $quiz) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-info-circle mr-2"></i>Quiz Details
                </a>
                <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Add Question
                </a>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Manage Questions</h1>
    <p class="text-gray-600 mt-2">{{ $quiz->title }}</p>
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

<!-- Quiz Summary -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center">
            <p class="text-sm text-gray-600">Total Questions</p>
            <p class="text-3xl font-bold text-gray-800">{{ $questions->count() }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-gray-600">Total Points</p>
            <p class="text-3xl font-bold text-green-600">{{ $questions->sum('points') }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-gray-600">Multiple Choice</p>
            <p class="text-3xl font-bold text-blue-600">{{ $questions->where('question_type', 'multiple_choice')->count() }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-gray-600">True/False</p>
            <p class="text-3xl font-bold text-purple-600">{{ $questions->where('question_type', 'true_false')->count() }}</p>
        </div>
    </div>
</div>

<!-- Questions List -->
@if($questions->count() > 0)
<div class="space-y-4">
    @foreach($questions as $index => $question)
    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-start gap-4 flex-1">
                    <!-- Question Number -->
                    <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0">
                        <span class="text-green-600 font-bold text-lg">{{ $index + 1 }}</span>
                    </div>

                    <!-- Question Content -->
                    <div class="flex-1">
                        <!-- Question Type Badge -->
                        <div class="flex items-center gap-2 mb-2">
                            @if($question->question_type === 'multiple_choice')
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                <i class="fas fa-list-ul mr-1"></i>Multiple Choice
                            </span>
                            @elseif($question->question_type === 'true_false')
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">
                                <i class="fas fa-check-double mr-1"></i>True/False
                            </span>
                            @else
                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-semibold">
                                <i class="fas fa-keyboard mr-1"></i>Short Answer
                            </span>
                            @endif
                            
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $question->points }} {{ $question->points === 1 ? 'point' : 'points' }}
                            </span>
                        </div>

                        <!-- Question Text -->
                        <p class="text-gray-800 font-semibold mb-3">{{ $question->question_text }}</p>

                        <!-- Answer Options/Correct Answer -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            @if($question->question_type === 'multiple_choice')
                            <div class="space-y-2">
                                @foreach($question->options as $optIndex => $option)
                                <div class="flex items-center">
                                    @if($optIndex == $question->correct_answer)
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    <span class="font-semibold text-green-600">{{ $option }}</span>
                                    @else
                                    <i class="fas fa-circle text-gray-400 mr-2 text-xs"></i>
                                    <span class="text-gray-600">{{ $option }}</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Correct Answer:</p>
                                <p class="font-semibold text-green-600">{{ $question->correct_answer }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 ml-4">
                    <a href="{{ route('quizzes.questions.edit', [$quiz, $question]) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 text-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('quizzes.questions.destroy', [$quiz, $question]) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this question?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 text-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-12 bg-white rounded-lg shadow">
    <i class="fas fa-question-circle text-gray-300 text-6xl mb-4"></i>
    <p class="text-gray-500 text-lg mb-4">No questions yet</p>
    <a href="{{ route('quizzes.questions.create', $quiz) }}" 
       class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
        <i class="fas fa-plus mr-2"></i>Add Your First Question
    </a>
</div>
@endif
</div>
@endsection