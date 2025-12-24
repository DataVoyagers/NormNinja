@extends('layouts.app')

@section('title', 'Add Question')

@section('content')

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('quizzes.questions.index', $quiz) }}" class="text-green-600 hover:text-green-800 mr-4">
                <i class="fas fa-arrow-left"></i> Back to Questions
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Add Question</h1>
        <p class="text-gray-600 mt-2">{{ $quiz->title }}</p>
    </div>
<!-- Form -->
<div class="bg-white rounded-lg shadow-md p-8 max-w-3xl">
    <form action="{{ route('quizzes.questions.store', $quiz) }}" method="POST" id="questionForm">
        @csrf

        <!-- Question Type -->
        <div class="mb-6">
            <label for="question_type" class="block text-gray-700 font-semibold mb-2">
                Question Type <span class="text-red-500">*</span>
            </label>
            <select name="question_type" 
                    id="question_type" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('question_type') border-red-500 @enderror"
                    required
                    onchange="updateQuestionType()">
                <option value="">Select Question Type</option>
                <option value="multiple_choice" {{ old('question_type') === 'multiple_choice' ? 'selected' : '' }}>
                    Multiple Choice
                </option>
                <option value="true_false" {{ old('question_type') === 'true_false' ? 'selected' : '' }}>
                    True/False
                </option>
                <option value="short_answer" {{ old('question_type') === 'short_answer' ? 'selected' : '' }}>
                    Short Answer
                </option>
            </select>
            @error('question_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
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
                      placeholder="Enter your question here..."
                      required>{{ old('question_text') }}</textarea>
            @error('question_text')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Multiple Choice Options -->
        <div id="multiple_choice_section" class="mb-6 hidden">
            <label class="block text-gray-700 font-semibold mb-2">
                Answer Options <span class="text-red-500">*</span>
            </label>
            <div class="space-y-3" id="options-container">
                @for($i = 0; $i < 4; $i++)
                <div class="flex gap-2">
                    <input type="radio" 
                           name="correct_answer" 
                           value="{{ $i }}" 
                           {{ old('correct_answer') == $i ? 'checked' : '' }}
                           class="mt-3">
                    <input type="text" 
                           name="options[]" 
                           value="{{ old('options.'.$i) }}"
                           class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="Option {{ chr(65 + $i) }}">
                </div>
                @endfor
            </div>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Select the radio button for the correct answer
            </p>
        </div>

        <!-- True/False Options -->
        <div id="true_false_section" class="mb-6 hidden">
            <label class="block text-gray-700 font-semibold mb-2">
                Correct Answer <span class="text-red-500">*</span>
            </label>
            <div class="space-y-3">
                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" 
                           name="correct_answer_tf" 
                           value="True" 
                           {{ old('correct_answer_tf') === 'True' ? 'checked' : '' }}
                           class="w-5 h-5 text-green-600 focus:ring-green-500">
                    <span class="ml-3 text-gray-800 font-semibold">True</span>
                </label>
                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" 
                           name="correct_answer_tf" 
                           value="False" 
                           {{ old('correct_answer_tf') === 'False' ? 'checked' : '' }}
                           class="w-5 h-5 text-green-600 focus:ring-green-500">
                    <span class="ml-3 text-gray-800 font-semibold">False</span>
                </label>
            </div>
        </div>

        <!-- Short Answer -->
        <div id="short_answer_section" class="mb-6 hidden">
            <label for="correct_answer_sa" class="block text-gray-700 font-semibold mb-2">
                Correct Answer <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="correct_answer_sa" 
                   id="correct_answer_sa" 
                   value="{{ old('correct_answer_sa') }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                   placeholder="Enter the correct answer">
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Answers are case-insensitive
            </p>
        </div>

        <!-- Points -->
        <div class="mb-6">
            <label for="points" class="block text-gray-700 font-semibold mb-2">
                Points <span class="text-red-500">*</span>
            </label>
            <input type="number" 
                   name="points" 
                   id="points" 
                   value="{{ old('points', 1) }}"
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
                <i class="fas fa-save mr-2"></i>Add Question
            </button>
        </div>
    </form>
</div>
</div>
<script>
function updateQuestionType() {
    const questionType = document.getElementById('question_type').value;
    
    // Hide all sections
    document.getElementById('multiple_choice_section').classList.add('hidden');
    document.getElementById('true_false_section').classList.add('hidden');
    document.getElementById('short_answer_section').classList.add('hidden');
    
    // Clear required attributes
    document.querySelectorAll('#options-container input').forEach(input => {
        input.removeAttribute('required');
    });
    document.querySelectorAll('input[name="correct_answer_tf"]').forEach(input => {
        input.removeAttribute('required');
    });
    document.getElementById('correct_answer_sa').removeAttribute('required');
    
    // Show relevant section and set required
    if (questionType === 'multiple_choice') {
        document.getElementById('multiple_choice_section').classList.remove('hidden');
        document.querySelectorAll('#options-container input[type="text"]').forEach(input => {
            input.setAttribute('required', 'required');
        });
    } else if (questionType === 'true_false') {
        document.getElementById('true_false_section').classList.remove('hidden');
        document.querySelectorAll('input[name="correct_answer_tf"]')[0].setAttribute('required', 'required');
    } else if (questionType === 'short_answer') {
        document.getElementById('short_answer_section').classList.remove('hidden');
        document.getElementById('correct_answer_sa').setAttribute('required', 'required');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const questionType = document.getElementById('question_type').value;
    if (questionType) {
        updateQuestionType();
    }
});
</script>
@endsection