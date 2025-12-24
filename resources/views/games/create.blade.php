@extends('layouts.app')

@section('title', 'Create Game')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('games.index') }}" class="text-indigo-600 hover:text-indigo-800 mr-4">
                <i class="fas fa-arrow-left"></i> Back to Games
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Create New Game</h1>
        <p class="text-gray-600 mt-2">Create an interactive learning game for your students</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8 max-w-4xl">
        <form action="{{ route('games.store') }}" method="POST" id="gameForm">
            @csrf

            <!-- Basic Information -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Basic Information</h2>

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-semibold mb-2">
                        Game Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('title') border-red-500 @enderror"
                           required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-semibold mb-2">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject -->
                <div class="mb-4">
                    <label for="subject" class="block text-gray-700 font-semibold mb-2">
                        Subject
                    </label>
                    <input type="text" 
                           name="subject" 
                           id="subject" 
                           value="{{ old('subject') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="e.g., Mathematics, Science, History">
                    @error('subject')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Game Type -->
                <div class="mb-4">
                    <label for="game_type" class="block text-gray-700 font-semibold mb-2">
                        Game Type <span class="text-red-500">*</span>
                    </label>
                    <select name="game_type" 
                            id="game_type" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('game_type') border-red-500 @enderror"
                            required
                            onchange="toggleGameTypeForm()">
                        <option value="">Select Game Type</option>
                        <option value="flashcard" {{ old('game_type') == 'flashcard' ? 'selected' : '' }}>
                            Flashcards - Learn with front/back cards
                        </option>
                        <option value="matching" {{ old('game_type') == 'matching' ? 'selected' : '' }}>
                            Matching - Match terms with definitions
                        </option>
                        <option value="gamified_quiz" {{ old('game_type') == 'gamified_quiz' ? 'selected' : '' }}>
                            Gamified Quiz - Interactive quiz with points
                        </option>
                    </select>
                    @error('game_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Published Status -->
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_published" 
                               value="1" 
                               {{ old('is_published') ? 'checked' : '' }}
                               class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="ml-2 text-gray-700 font-semibold">Publish immediately (students can play)</span>
                    </label>
                </div>
            </div>

            <!-- Game Content (Dynamic based on game type) -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Game Content</h2>

                <!-- Flashcard Content -->
                <div id="flashcard-form" class="game-type-form hidden">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                        <p class="text-blue-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            Create flashcards with a front (question/term) and back (answer/definition)
                        </p>
                    </div>
                    
                    <div id="flashcard-list" class="space-y-4">
                        <!-- Flashcards will be added here dynamically -->
                    </div>
                    
                    <button type="button" 
                            onclick="addFlashcard()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg mt-4">
                        <i class="fas fa-plus mr-2"></i>Add Flashcard
                    </button>
                </div>

                <!-- Matching Content -->
                <div id="matching-form" class="game-type-form hidden">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                        <p class="text-green-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            Create pairs that students will match (term → definition, question → answer, etc.)
                        </p>
                    </div>
                    
                    <div id="matching-list" class="space-y-4">
                        <!-- Matching pairs will be added here dynamically -->
                    </div>
                    
                    <button type="button" 
                            onclick="addMatchingPair()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg mt-4">
                        <i class="fas fa-plus mr-2"></i>Add Pair
                    </button>
                </div>

                <!-- Gamified Quiz Content -->
                <div id="quiz-form" class="game-type-form hidden">
                    <div class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-4">
                        <p class="text-purple-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            Create multiple-choice questions with points for correct answers
                        </p>
                    </div>
                    
                    <div id="quiz-list" class="space-y-4">
                        <!-- Quiz questions will be added here dynamically -->
                    </div>
                    
                    <button type="button" 
                            onclick="addQuizQuestion()" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg mt-4">
                        <i class="fas fa-plus mr-2"></i>Add Question
                    </button>
                </div>

                <!-- Placeholder when no game type selected -->
                <div id="no-type-selected" class="text-center py-8 bg-gray-50 rounded-lg">
                    <i class="fas fa-gamepad text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500">Please select a game type to continue</p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('games.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-save mr-2"></i>Create Game
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let flashcardCount = 0;
let matchingCount = 0;
let quizCount = 0;

// Toggle game type forms
function toggleGameTypeForm() {
    const gameType = document.getElementById('game_type').value;
    
    // Hide all forms
    document.querySelectorAll('.game-type-form').forEach(form => {
        form.classList.add('hidden');
    });
    
    // Hide placeholder
    document.getElementById('no-type-selected').classList.add('hidden');
    
    // Show selected form
    if (gameType === 'flashcard') {
        document.getElementById('flashcard-form').classList.remove('hidden');
        if (flashcardCount === 0) addFlashcard();
    } else if (gameType === 'matching') {
        document.getElementById('matching-form').classList.remove('hidden');
        if (matchingCount === 0) addMatchingPair();
    } else if (gameType === 'gamified_quiz') {
        document.getElementById('quiz-form').classList.remove('hidden');
        if (quizCount === 0) addQuizQuestion();
    } else {
        document.getElementById('no-type-selected').classList.remove('hidden');
    }
}

// Add Flashcard
function addFlashcard() {
    const container = document.getElementById('flashcard-list');
    const index = flashcardCount++;
    
    const html = `
        <div class="border rounded-lg p-4 bg-white" id="flashcard-${index}">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold text-gray-700">Flashcard ${index + 1}</h4>
                <button type="button" onclick="removeFlashcard(${index})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Front (Question/Term)</label>
                    <textarea name="game_data[flashcards][${index}][front]" 
                              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" 
                              rows="3" 
                              required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Back (Answer/Definition)</label>
                    <textarea name="game_data[flashcards][${index}][back]" 
                              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" 
                              rows="3" 
                              required></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
}

function removeFlashcard(index) {
    document.getElementById(`flashcard-${index}`).remove();
}

// Add Matching Pair
function addMatchingPair() {
    const container = document.getElementById('matching-list');
    const index = matchingCount++;
    
    const html = `
        <div class="border rounded-lg p-4 bg-white" id="matching-${index}">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold text-gray-700">Pair ${index + 1}</h4>
                <button type="button" onclick="removeMatchingPair(${index})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Term</label>
                    <input type="text" 
                           name="game_data[pairs][${index}][term]" 
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500" 
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Definition</label>
                    <input type="text" 
                           name="game_data[pairs][${index}][definition]" 
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500" 
                           required>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
}

function removeMatchingPair(index) {
    document.getElementById(`matching-${index}`).remove();
}

// Add Quiz Question
function addQuizQuestion() {
    const container = document.getElementById('quiz-list');
    const index = quizCount++;
    
    const html = `
        <div class="border rounded-lg p-4 bg-white" id="quiz-${index}">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold text-gray-700">Question ${index + 1}</h4>
                <button type="button" onclick="removeQuizQuestion(${index})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                <textarea name="game_data[questions][${index}][question]" 
                          class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" 
                          rows="2" 
                          required></textarea>
            </div>
            
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                <div class="space-y-2">
                    <div class="flex gap-2">
                        <input type="radio" name="game_data[questions][${index}][correct]" value="0" required class="mt-1">
                        <input type="text" 
                               name="game_data[questions][${index}][options][0]" 
                               class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" 
                               placeholder="Option A" 
                               required>
                    </div>
                    <div class="flex gap-2">
                        <input type="radio" name="game_data[questions][${index}][correct]" value="1" required class="mt-1">
                        <input type="text" 
                               name="game_data[questions][${index}][options][1]" 
                               class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" 
                               placeholder="Option B" 
                               required>
                    </div>
                    <div class="flex gap-2">
                        <input type="radio" name="game_data[questions][${index}][correct]" value="2" required class="mt-1">
                        <input type="text" 
                               name="game_data[questions][${index}][options][2]" 
                               class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" 
                               placeholder="Option C" 
                               required>
                    </div>
                    <div class="flex gap-2">
                        <input type="radio" name="game_data[questions][${index}][correct]" value="3" required class="mt-1">
                        <input type="text" 
                               name="game_data[questions][${index}][options][3]" 
                               class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" 
                               placeholder="Option D" 
                               required>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Select the radio button for the correct answer</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                <input type="number" 
                       name="game_data[questions][${index}][points]" 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" 
                       value="10" 
                       min="1" 
                       required>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
}

function removeQuizQuestion(index) {
    document.getElementById(`quiz-${index}`).remove();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const gameType = document.getElementById('game_type').value;
    if (gameType) {
        toggleGameTypeForm();
    }
});
</script>
@endsection