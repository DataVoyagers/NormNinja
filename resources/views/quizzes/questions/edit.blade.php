@extends('layouts.app')

@section('title', 'Edit Question')

@section('content')

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('quizzes.questions.index', $quiz) }}" class="text-green-600 hover:text-green-800 mr-4">
                <i class="fas fa-arrow-left"></i> Back to Questions
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Question</h1>
        <p class="text-gray-600 mt-2">{{ $quiz->title }}</p>
    </div>
<!-- Form -->
<div class="bg-white rounded-lg shadow-md p-8 max-w-3xl">
    <form action="{{ route('quizzes.questions.update', [$quiz, $question]) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Question Type (Read-only) -->
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">
                Question Type
            </label>
            <input type="hidden" name="question_type" value="{{ $question->question_type }}">
            <div class="w-full px-4 py-2 bg-gray-100 border rounded-lg text-gray-600">
                @if($question->question_type === 'multiple_choice')
                    <i class="fas fa-list-ul mr-2"></i>Multiple Choice
                @elseif($question->question_type === 'true_false')
                    <i class="fas fa-check-double mr-2"></i>True/False
                @else
                    <i class="fas fa-keyboard mr-2"></i>Short Answer
                @endif
            </div>
            <p class="text-xs text-gray-500 mt-1">Question type cannot be changed after creation</p>
        </div>

        <!-- Question Text -->
        <div class="mb-6">
            <label for="question_text" class="block text-gray-700 font-semibold mb-2">
                Question <span class="text-red-500">*</span>
            </label>
            <textarea name="question_text" 
                      id="question_text" 
                      rows="4"
                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('question_text') border-red-500 @enderror"
                      required>{{ old('question_text', $question->question_text) }}</textarea>
            @error('question_text')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Multiple Choice Options -->
        @if($question->question_type === 'multiple_choice')
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">
                Answer Options <span class="text-red-500">*</span>
            </label>
            <div class="space-y-3">
                @foreach($question->options as $i => $option)
                <div class="flex gap-2">
                    <input type="radio" 
                           name="correct_answer" 
                           value="{{ $i }}" 
                           {{ old('correct_answer', $question->correct_answer) == $i ? 'checked' : '' }}
                           class="mt-3"
                           required>
                    <input type="text" 
                           name="options[]" 
                           value="{{ old('options.'.$i, $option) }}"
                           class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="Option {{ chr(65 + $i) }}"
                           required>
                </div>
                @endforeach
            </div>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Select the radio button for the correct answer
            </p>
        </div>
        @endif

        <!-- True/False Options -->
        @if($question->question_type === 'true_false')
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">
                Correct Answer <span class="text-red-500">*</span>
            </label>
            <div class="space-y-3">
                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" 
                           name="correct_answer" 
                           value="True" 
                           {{ old('correct_answer', $question->correct_answer) === 'True' ? 'checked' : '' }}
                           class="w-5 h-5 text-green-600 focus:ring-green-500"
                           required>
                    <span class="ml-3 text-gray-800 font-semibold">True</span>
                </label>
                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" 
                           name="correct_answer" 
                           value="False" 
                           {{ old('correct_answer', $question->correct_answer) === 'False' ? 'checked' : '' }}
                           class="w-5 h-5 text-green-600 focus:ring-green-500"
                           required>
                    <span class="ml-3 text-gray-800 font-semibold">False</span>
                </label>
            </div>
        </div>
        @endif

        <!-- Short Answer -->
        @if($question->question_type === 'short_answer')
        <div class="mb-6">
            <label for="correct_answer" class="block text-gray-700 font-semibold mb-2">
                Correct Answer <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="correct_answer" 
                   id="correct_answer" 
                   value="{{ old('correct_answer', $question->correct_answer) }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                   placeholder="Enter the correct answer"
                   required>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Answers are case-insensitive
            </p>
        </div>
        @endif

        <!-- Points -->
        <div class="mb-6">
            <label for="points" class="block text-gray-700 font-semibold mb-2">
                Points <span class="text-red-500">*</span>
            </label>
            <input type="number" 
                   name="points" 
                   id="points" 
                   value="{{ old('points', $question->points) }}"
                   min="1"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('points') border-red-500 @enderror"
                   required>
            @error('points')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4 pt-6 border-t">
            <a href="{{ route('quizzes.questions.index', $quiz) }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition duration-200">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-save mr-2"></i>Update Question
            </button>
        </div>
    </form>
</div>
</div>
@endsection