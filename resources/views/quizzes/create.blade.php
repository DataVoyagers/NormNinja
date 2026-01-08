@extends('layouts.app')

@section('title', 'Create Quiz')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('quizzes.index') }}" class="text-green-600 hover:text-green-800 mr-4">
                <i class="fas fa-arrow-left"></i> Back to Quizzes
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Create New Quiz</h1>
        <p class="text-gray-600 mt-2">Set up your quiz details. You'll add questions in the next step.</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8 max-w-3xl">
        <form action="{{ route('quizzes.store') }}" method="POST">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-gray-700 font-semibold mb-2">
                    Quiz Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('title') border-red-500 @enderror"
                       placeholder="e.g., Chapter 5 Review, Final Exam"
                       required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-gray-700 font-semibold mb-2">
                    Description
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Describe what this quiz covers...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subject -->
            <div class="mb-6">
                <label for="subject" class="block text-gray-700 font-semibold mb-2">
                    Subject
                </label>
                <input type="text" 
                       name="subject" 
                       id="subject" 
                       value="{{ old('subject') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       placeholder="e.g., Mathematics, Science, History">
                @error('subject')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quiz Settings Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Duration -->
                <div>
                    <label for="duration_minutes" class="block text-gray-700 font-semibold mb-2">
                        Time Limit (minutes)
                    </label>
                    <input type="number" 
                           name="duration_minutes" 
                           id="duration_minutes" 
                           value="{{ old('duration_minutes') }}"
                           min="1"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="Leave empty for no limit">
                    @error('duration_minutes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Passing Score -->
                <div>
                    <label for="passing_score" class="block text-gray-700 font-semibold mb-2">
                        Passing Score (%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="passing_score" 
                           id="passing_score" 
                           value="{{ old('passing_score', 60) }}"
                           min="0"
                           max="100"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('passing_score') border-red-500 @enderror"
                           required>
                    @error('passing_score')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Availability Period -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Availability Period (Optional)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Available From -->
                    <div>
                        <label for="available_from" class="block text-gray-700 font-semibold mb-2">
                            Available From
                        </label>
                        <input type="datetime-local" 
                               name="available_from" 
                               id="available_from" 
                               value="{{ old('available_from') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('available_from')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Available Until -->
                    <div>
                        <label for="available_until" class="block text-gray-700 font-semibold mb-2">
                            Available Until
                        </label>
                        <input type="datetime-local" 
                               name="available_until" 
                               id="available_until" 
                               value="{{ old('available_until') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('available_until')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Leave empty to make the quiz available indefinitely
                </p>
            </div>

            <!-- Published Status -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_published" 
                           value="1" 
                           {{ old('is_published') ? 'checked' : '' }}
                           class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <span class="ml-2 text-gray-700 font-semibold">
                        Active immediately (students can take the quiz)
                    </span>
                </label>
                <p class="text-sm text-gray-500 mt-1 ml-7">
                    You can activate quiz later after adding questions
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-blue-800">Next Steps</h3>
                        <p class="mt-1 text-sm text-blue-700">
                            After creating the quiz, you'll be able to add questions. You can add multiple choice, true/false, and short answer questions.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('quizzes.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-save mr-2"></i>Create Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection