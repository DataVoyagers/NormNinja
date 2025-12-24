@extends('layouts.app')

@section('title', 'Take Quiz: ' . $quiz->title)

@section('content')

<div class="container mx-auto px-4 py-8">
    <!-- Quiz Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $quiz->title }}</h1>
                <p class="text-gray-600">{{ $quiz->questions()->count() }} Questions · {{ $quiz->total_points }} Points</p>
            </div>
            @if($quiz->duration_minutes)
            <div class="text-right">
                <div class="text-3xl font-bold text-green-600" id="timer">{{ $quiz->duration_minutes }}:00</div>
                <p class="text-sm text-gray-500">Time Remaining</p>
            </div>
            @endif
        </div>
    </div>
<!-- Quiz Form -->
<form action="{{ route('quizzes.submit', [$quiz, $attempt]) }}" method="POST" id="quizForm">
    @csrf

    <div class="space-y-6">
        @foreach($questions as $index => $question)
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Question Number and Points -->
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-lg font-bold text-gray-800">
                    Question {{ $index + 1 }}
                </h2>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $question->points }} {{ $question->points === 1 ? 'point' : 'points' }}
                </span>
            </div>

            <!-- Question Text -->
            <p class="text-gray-700 mb-4 text-lg">{{ $question->question_text }}</p>

            <!-- Answer Options -->
            @if($question->question_type === 'multiple_choice')
            <div class="space-y-3">
                @foreach($question->options as $optionIndex => $option)
                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                    <input type="radio" 
                           name="answers[{{ $question->id }}]" 
                           value="{{ $optionIndex }}" 
                           class="w-5 h-5 text-green-600 focus:ring-green-500"
                           required>
                    <span class="ml-3 text-gray-800">{{ $option }}</span>
                </label>
                @endforeach
            </div>
            @elseif($question->question_type === 'true_false')
            <div class="space-y-3">
                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                    <input type="radio" 
                           name="answers[{{ $question->id }}]" 
                           value="True" 
                           class="w-5 h-5 text-green-600 focus:ring-green-500"
                           required>
                    <span class="ml-3 text-gray-800">True</span>
                </label>
                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                    <input type="radio" 
                           name="answers[{{ $question->id }}]" 
                           value="False" 
                           class="w-5 h-5 text-green-600 focus:ring-green-500"
                           required>
                    <span class="ml-3 text-gray-800">False</span>
                </label>
            </div>
            @elseif($question->question_type === 'short_answer')
            <input type="text" 
                   name="answers[{{ $question->id }}]" 
                   class="w-full px-4 py-3 border-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                   placeholder="Type your answer here..."
                   required>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Submit Button -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <div class="flex justify-between items-center">
            <p class="text-gray-600">
                <i class="fas fa-info-circle mr-2"></i>
                Make sure you've answered all questions before submitting
            </p>
            <button type="submit" 
                    onclick="return confirm('Are you sure you want to submit your quiz? You cannot change your answers after submission.')"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-bold text-lg transition duration-200">
                <i class="fas fa-check mr-2"></i>Submit Quiz
            </button>
        </div>
    </div>
</form>
</div>
@if($quiz->duration_minutes)
<script>
let timeLeft = {{ $quiz->duration_minutes * 60 }};
const timerElement = document.getElementById('timer');
const quizForm = document.getElementById('quizForm');

const countdown = setInterval(() => {
    timeLeft--;
    
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    
    timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    
    // Change color when time is running out
    if (timeLeft <= 60) {
        timerElement.classList.remove('text-green-600');
        timerElement.classList.add('text-red-600');
    } else if (timeLeft <= 300) {
        timerElement.classList.remove('text-green-600');
        timerElement.classList.add('text-yellow-600');
    }
    
    // Auto-submit when time runs out
    if (timeLeft <= 0) {
        clearInterval(countdown);
        alert('Time is up! Your quiz will be submitted automatically.');
        quizForm.submit();
    }
}, 1000);
</script>
@endif
@endsection