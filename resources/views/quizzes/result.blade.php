@extends('layouts.app')

@section('title', 'Quiz Results')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2 text-2xl"></i>
            <p class="font-semibold">{{ session('success') }}</p>
        </div>
    </div>
    @endif
    <div class="max-w-4xl mx-auto">
    <!-- Results Header -->
    <div class="text-center mb-8">
        <div class="inline-block bg-gradient-to-r from-green-500 to-teal-600 rounded-full p-6 mb-4">
            @if($attempt->passed)
            <i class="fas fa-trophy text-6xl text-white"></i>
            @else
            <i class="fas fa-clipboard-check text-6xl text-white"></i>
            @endif
        </div>
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
            @if($attempt->passed)
            Congratulations!
            @else
            Quiz Complete
            @endif
        </h1>
        <p class="text-xl text-gray-600">{{ $quiz->title }}</p>
    </div>

    <!-- Score Card -->
    <div class="bg-white rounded-lg shadow-xl p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <!-- Score -->
            <div class="bg-gradient-to-br {{ $attempt->passed ? 'from-green-50 to-green-100' : 'from-orange-50 to-orange-100' }} rounded-lg p-6">
                <p class="text-sm font-semibold {{ $attempt->passed ? 'text-green-700' : 'text-orange-700' }} uppercase tracking-wide mb-2">Your Score</p>
                <p class="text-5xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-orange-600' }}">{{ $attempt->percentage }}%</p>
                <p class="text-sm text-gray-600 mt-2">{{ $attempt->score }} / {{ $attempt->total_points }} points</p>
            </div>

            <!-- Correct Answers -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6">
                <p class="text-sm font-semibold text-blue-700 uppercase tracking-wide mb-2">Correct Answers</p>
                @php
                    $correctCount = 0;
                    foreach($quiz->questions as $question) {
                        $studentAnswer = $attempt->answers[$question->id] ?? null;
                        if($question->question_type === 'short_answer') {
                            if(strtolower(trim($studentAnswer)) == strtolower(trim($question->correct_answer))) {
                                $correctCount++;
                            }
                        } else {
                            if($studentAnswer == $question->correct_answer) {
                                $correctCount++;
                            }
                        }
                    }
                @endphp
                <p class="text-5xl font-bold text-blue-600">{{ $correctCount }}</p>
                <p class="text-sm text-gray-600 mt-2">out of {{ $quiz->questions()->count() }} questions</p>
            </div>

            <!-- Status -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6">
                <p class="text-sm font-semibold text-purple-700 uppercase tracking-wide mb-2">Result</p>
                <div class="text-5xl mb-2">
                    @if($attempt->passed)
                    <i class="fas fa-check-circle text-purple-600"></i>
                    @else
                    <i class="fas fa-times-circle text-purple-600"></i>
                    @endif
                </div>
                <p class="text-lg font-bold text-purple-600">
                    @if($attempt->passed)
                    PASSED
                    @else
                    NOT PASSED
                    @endif
                </p>
            </div>
        </div>

        <!-- Performance Message -->
        <div class="mt-8 text-center">
            @if($attempt->percentage >= 90)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <p class="text-lg font-bold text-yellow-800">
                    <i class="fas fa-star mr-2"></i>Excellent Work!
                </p>
                <p class="text-yellow-700 mt-1">Outstanding performance! You truly mastered this material!</p>
            </div>
            @elseif($attempt->percentage >= 70)
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                <p class="text-lg font-bold text-green-800">
                    <i class="fas fa-thumbs-up mr-2"></i>Great Job!
                </p>
                <p class="text-green-700 mt-1">You passed with a solid score! Keep up the good work!</p>
            </div>
            @elseif($attempt->percentage >= $quiz->passing_score)
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <p class="text-lg font-bold text-blue-800">
                    <i class="fas fa-check mr-2"></i>Well Done!
                </p>
                <p class="text-blue-700 mt-1">You passed! Consider reviewing the material to improve your understanding.</p>
            </div>
            @else
            <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded">
                <p class="text-lg font-bold text-orange-800">
                    <i class="fas fa-redo mr-2"></i>Keep Trying!
                </p>
                <p class="text-orange-700 mt-1">Don't give up! Review the material and try again. You'll do better next time!</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Detailed Results -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-list-check mr-2 text-green-600"></i>Detailed Results
        </h2>
        
        <div class="space-y-6">
            @foreach($quiz->questions as $index => $question)
            @php
                $studentAnswer = $attempt->answers[$question->id] ?? null;
                $isCorrect = false;
                
                if($question->question_type === 'short_answer') {
                    $isCorrect = strtolower(trim($studentAnswer)) == strtolower(trim($question->correct_answer));
                } else {
                    $isCorrect = $studentAnswer == $question->correct_answer;
                }
            @endphp
            
            <div class="border rounded-lg p-6 {{ $isCorrect ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="font-bold text-gray-800">Question {{ $index + 1 }}</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">{{ $question->points }} pts</span>
                        @if($isCorrect)
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">
                            <i class="fas fa-check mr-1"></i>CORRECT
                        </span>
                        @else
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">
                            <i class="fas fa-times mr-1"></i>INCORRECT
                        </span>
                        @endif
                    </div>
                </div>

                <p class="text-gray-700 mb-4">{{ $question->question_text }}</p>

                <div class="space-y-2">
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Your Answer:</p>
                        <p class="text-gray-800">
                            @if($question->question_type === 'multiple_choice')
                                {{ $question->options[$studentAnswer] ?? 'No answer' }}
                            @else
                                {{ $studentAnswer ?? 'No answer' }}
                            @endif
                        </p>
                    </div>
                    
                    @if(!$isCorrect)
                    <div class="pt-2 border-t">
                        <p class="text-sm font-semibold text-green-600">Correct Answer:</p>
                        <p class="text-green-800 font-semibold">
                            @if($question->question_type === 'multiple_choice')
                                {{ $question->options[$question->correct_answer] }}
                            @else
                                {{ $question->correct_answer }}
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4 justify-center">
        <a href="{{ route('quizzes.start', $quiz) }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-bold text-lg transition duration-200">
            <i class="fas fa-redo mr-2"></i>Take Again
        </a>
        <a href="{{ route('quizzes.show', $quiz) }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg font-bold text-lg transition duration-200">
            <i class="fas fa-info-circle mr-2"></i>Quiz Details
        </a>
        <a href="{{ route('quizzes.index') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold text-lg transition duration-200">
            <i class="fas fa-clipboard-list mr-2"></i>All Quizzes
        </a>
    </div>
</div>
</div>
@endsection