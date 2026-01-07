@extends('layouts.app')

@section('title', 'Edit Quiz')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('quizzes.index') }}" class="text-green-600 hover:text-green-800 mr-4">
                <i class="fas fa-arrow-left"></i> Back to Quizzes
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Quiz</h1>
        <p class="text-gray-600 mt-2">Update quiz settings and information</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8 max-w-3xl">
        <form action="{{ route('quizzes.update', $quiz) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-gray-700 font-semibold mb-2">
                    Quiz Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title', $quiz->title) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('title') border-red-500 @enderror"
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
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $quiz->description) }}</textarea>
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
                       value="{{ old('subject', $quiz->subject) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
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
                           value="{{ old('duration_minutes', $quiz->duration_minutes) }}"
                           min="1"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
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
                           value="{{ old('passing_score', $quiz->passing_score) }}"
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
                               value="{{ old('available_from', $quiz->available_from?->format('Y-m-d\TH:i')) }}"
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
                               value="{{ old('available_until', $quiz->available_until?->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('available_until')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Published Status -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" 
                           name="is_published" 
                           value="1" 
                           {{ old('is_published', $quiz->is_published) ? 'checked' : '' }}
                           class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <span class="ml-2 text-gray-700 font-semibold">
                        Active (students can take the quiz)
                    </span>
                </label>
            </div>

            <!-- Quiz Statistics -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quiz Statistics</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Questions</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $quiz->questions()->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Points</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $quiz->total_points }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Attempts</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $quiz->attempts()->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Completed</p>
                        <p class="text-2xl font-bold text-green-600">{{ $quiz->attempts()->where('is_completed', true)->count() }}</p>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                       class="text-green-600 hover:text-green-800 font-semibold">
                        <i class="fas fa-list mr-2"></i>Manage Questions
                    </a>
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
                    <i class="fas fa-save mr-2"></i>Update Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection