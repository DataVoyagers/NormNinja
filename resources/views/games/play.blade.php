@extends('layouts.app')

@section('title', 'Play: ' . $game->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Game Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $game->title }}</h1>
                <p class="text-gray-600">{{ $game->subject }}</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-indigo-600" id="timer">00:00</div>
                <p class="text-sm text-gray-500">Time Elapsed</p>
            </div>
        </div>
    </div>

    @if($game->game_type === 'flashcard')
    <!-- Flashcard Game -->
    <div id="flashcard-game" class="max-w-2xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <span class="text-lg font-semibold text-gray-700">
                    Card <span id="current-card">1</span> of <span id="total-cards">{{ count($game->game_data['flashcards']) }}</span>
                </span>
            </div>
            <div>
                <button onclick="toggleFlip()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-sync-alt mr-2"></i>Flip Card
                </button>
            </div>
        </div>

        <!-- Flashcard Container -->
        <div class="flashcard-container perspective mb-6" onclick="toggleFlip()">
            <div class="flashcard" id="flashcard">
                <div class="flashcard-front">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-2xl p-12 text-white min-h-96 flex items-center justify-center cursor-pointer">
                        <div class="text-center">
                            <p class="text-sm uppercase tracking-wide mb-4">Question</p>
                            <h2 class="text-3xl font-bold" id="card-front"></h2>
                        </div>
                    </div>
                </div>
                <div class="flashcard-back">
                    <div class="bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl shadow-2xl p-12 text-white min-h-96 flex items-center justify-center cursor-pointer">
                        <div class="text-center">
                            <p class="text-sm uppercase tracking-wide mb-4">Answer</p>
                            <h2 class="text-3xl font-bold" id="card-back"></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between items-center">
            <button onclick="previousCard()" id="prev-btn" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Previous
            </button>
            <div class="flex gap-2">
                @foreach($game->game_data['flashcards'] as $index => $card)
                <div class="progress-dot w-3 h-3 rounded-full bg-gray-300" data-index="{{ $index }}"></div>
                @endforeach
            </div>
            <button onclick="nextCard()" id="next-btn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold">
                Next<i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>

        <!-- Finish Button -->
        <div class="text-center mt-8">
            <button onclick="finishFlashcards()" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-bold text-lg">
                <i class="fas fa-check mr-2"></i>Finish Review
            </button>
        </div>
    </div>

    <script>
    const flashcards = @json($game->game_data['flashcards']);
    let currentCardIndex = 0;
    let isFlipped = false;

    function loadCard(index) {
        document.getElementById('card-front').textContent = flashcards[index].front;
        document.getElementById('card-back').textContent = flashcards[index].back;
        document.getElementById('current-card').textContent = index + 1;
        
        // Reset flip
        if (isFlipped) {
            toggleFlip();
        }

        // Update progress dots
        document.querySelectorAll('.progress-dot').forEach((dot, i) => {
            if (i === index) {
                dot.classList.remove('bg-gray-300');
                dot.classList.add('bg-indigo-600');
            } else if (i < index) {
                dot.classList.remove('bg-gray-300');
                dot.classList.add('bg-green-600');
            } else {
                dot.classList.remove('bg-indigo-600', 'bg-green-600');
                dot.classList.add('bg-gray-300');
            }
        });

        // Update button states
        document.getElementById('prev-btn').disabled = index === 0;
        document.getElementById('next-btn').textContent = index === flashcards.length - 1 ? 'Finish' : 'Next';
    }

    function toggleFlip() {
        const card = document.getElementById('flashcard');
        isFlipped = !isFlipped;
        if (isFlipped) {
            card.style.transform = 'rotateY(180deg)';
        } else {
            card.style.transform = 'rotateY(0deg)';
        }
    }

    function previousCard() {
        if (currentCardIndex > 0) {
            currentCardIndex--;
            loadCard(currentCardIndex);
        }
    }

    function nextCard() {
        if (currentCardIndex < flashcards.length - 1) {
            currentCardIndex++;
            loadCard(currentCardIndex);
        } else {
            finishFlashcards();
        }
    }

    function finishFlashcards() {
        const timeSpent = parseInt(document.getElementById('timer').dataset.seconds);
        const score = flashcards.length * 10; // 10 points per card reviewed
        
        submitGame(score, timeSpent);
    }

    // Initialize
    loadCard(0);
    </script>

    <style>
    .perspective {
        perspective: 1000px;
    }
    .flashcard {
        position: relative;
        transform-style: preserve-3d;
        transition: transform 0.6s;
    }
    .flashcard-front, .flashcard-back {
        backface-visibility: hidden;
    }
    .flashcard-back {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        transform: rotateY(180deg);
    }
    </style>
    @endif

    @if($game->game_type === 'matching')
    <!-- Matching Game -->
    <div id="matching-game" class="max-w-4xl mx-auto">
        <div class="mb-6 text-center">
            <div class="text-2xl font-bold text-indigo-600" id="match-score">Score: 0</div>
            <p class="text-gray-600">Click a term, then click its matching definition</p>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <!-- Terms Column -->
            <div class="space-y-3">
                <h3 class="font-bold text-gray-700 mb-4">Terms</h3>
                @foreach($game->game_data['pairs'] as $index => $pair)
                <div class="matching-item term bg-blue-100 border-2 border-blue-300 p-4 rounded-lg cursor-pointer hover:bg-blue-200 transition"
                     data-index="{{ $index }}"
                     onclick="selectTerm({{ $index }})">
                    <p class="font-semibold text-gray-800">{{ $pair['term'] }}</p>
                </div>
                @endforeach
            </div>

            <!-- Definitions Column -->
            <div class="space-y-3">
                <h3 class="font-bold text-gray-700 mb-4">Definitions</h3>
                @php
                    $shuffled = collect($game->game_data['pairs'])->shuffle();
                @endphp
                @foreach($shuffled as $index => $pair)
                <div class="matching-item definition bg-green-100 border-2 border-green-300 p-4 rounded-lg cursor-pointer hover:bg-green-200 transition"
                     data-index="{{ array_search($pair, $game->game_data['pairs']) }}"
                     onclick="selectDefinition({{ array_search($pair, $game->game_data['pairs']) }})">
                    <p class="text-gray-800">{{ $pair['definition'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-8">
            <button onclick="finishMatching()" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-bold text-lg">
                <i class="fas fa-check mr-2"></i>Finish Game
            </button>
        </div>
    </div>

    <script>
    let selectedTerm = null;
    let matchScore = 0;
    let totalPairs = {{ count($game->game_data['pairs']) }};
    let matchedPairs = 0;

    function selectTerm(index) {
        // Remove previous selection
        document.querySelectorAll('.term').forEach(el => {
            el.classList.remove('ring-4', 'ring-blue-500');
        });
        
        // Select this term
        selectedTerm = index;
        event.currentTarget.classList.add('ring-4', 'ring-blue-500');
    }

    function selectDefinition(index) {
        if (selectedTerm === null) {
            alert('Please select a term first!');
            return;
        }

        if (selectedTerm === index) {
            // Correct match!
            matchScore += 10;
            matchedPairs++;
            
            // Hide matched items
            document.querySelector(`.term[data-index="${selectedTerm}"]`).style.opacity = '0.3';
            document.querySelector(`.definition[data-index="${index}"]`).style.opacity = '0.3';
            document.querySelector(`.term[data-index="${selectedTerm}"]`).style.pointerEvents = 'none';
            document.querySelector(`.definition[data-index="${index}"]`).style.pointerEvents = 'none';
            
            // Show success
            event.currentTarget.classList.add('bg-green-500', 'text-white');
            
            selectedTerm = null;
            
            // Update score
            document.getElementById('match-score').textContent = `Score: ${matchScore}`;
            
            // Check if all matched
            if (matchedPairs === totalPairs) {
                setTimeout(() => {
                    alert('Congratulations! You matched all pairs!');
                    finishMatching();
                }, 500);
            }
        } else {
            // Wrong match
            event.currentTarget.classList.add('bg-red-500', 'text-white');
            setTimeout(() => {
                event.currentTarget.classList.remove('bg-red-500', 'text-white');
                document.querySelectorAll('.term').forEach(el => {
                    el.classList.remove('ring-4', 'ring-blue-500');
                });
                selectedTerm = null;
            }, 500);
        }
    }

    function finishMatching() {
        const timeSpent = parseInt(document.getElementById('timer').dataset.seconds);
        submitGame(matchScore, timeSpent);
    }
    </script>
    @endif

    @if($game->game_type === 'gamified_quiz')
    <!-- Gamified Quiz -->
    <div id="quiz-game" class="max-w-3xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <span class="text-lg font-semibold text-gray-700">
                    Question <span id="current-question">1</span> of <span id="total-questions">{{ count($game->game_data['questions']) }}</span>
                </span>
            </div>
            <div class="text-2xl font-bold text-indigo-600" id="quiz-score">Score: 0</div>
        </div>

        <!-- Question Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6" id="question-text"></h2>
            
            <div class="space-y-3" id="options-container">
                <!-- Options will be inserted here -->
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex justify-center">
            <button onclick="nextQuestion()" id="next-question-btn" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-bold text-lg" 
                    disabled>
                Next Question
            </button>
        </div>
    </div>

    <script>
    const questions = @json($game->game_data['questions']);
    let currentQuestionIndex = 0;
    let quizScore = 0;
    let selectedAnswer = null;
    let userAnswers = [];

    function loadQuestion(index) {
        const question = questions[index];
        document.getElementById('question-text').textContent = question.question;
        document.getElementById('current-question').textContent = index + 1;
        
        // Clear previous selection
        selectedAnswer = null;
        document.getElementById('next-question-btn').disabled = true;
        
        // Load options
        const optionsContainer = document.getElementById('options-container');
        optionsContainer.innerHTML = '';
        
        question.options.forEach((option, i) => {
            const optionDiv = document.createElement('div');
            optionDiv.className = 'option-item p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition';
            optionDiv.onclick = () => selectAnswer(i, question.correct, question.points);
            optionDiv.innerHTML = `
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-700 mr-4">
                        ${String.fromCharCode(65 + i)}
                    </span>
                    <span class="text-gray-800">${option}</span>
                </div>
            `;
            optionsContainer.appendChild(optionDiv);
        });
    }

    function selectAnswer(selected, correct, points) {
        if (selectedAnswer !== null) return; // Already answered
        
        selectedAnswer = selected;
        const options = document.querySelectorAll('.option-item');
        
        // Show correct/incorrect
        options.forEach((option, i) => {
            option.classList.remove('cursor-pointer', 'hover:border-indigo-500', 'hover:bg-indigo-50');
            option.style.pointerEvents = 'none';
            
            if (i === correct) {
                option.classList.add('border-green-500', 'bg-green-50');
            }
            if (i === selected && i !== correct) {
                option.classList.add('border-red-500', 'bg-red-50');
            }
        });
        
        // Update score
        if (selected === correct) {
            quizScore += points;
            document.getElementById('quiz-score').textContent = `Score: ${quizScore}`;
        }
        
        // Store answer
        userAnswers.push({
            question: currentQuestionIndex,
            selected: selected,
            correct: correct,
            isCorrect: selected === correct
        });
        
        // Enable next button
        document.getElementById('next-question-btn').disabled = false;
    }

    function nextQuestion() {
        if (currentQuestionIndex < questions.length - 1) {
            currentQuestionIndex++;
            loadQuestion(currentQuestionIndex);
            
            // Update button text
            if (currentQuestionIndex === questions.length - 1) {
                document.getElementById('next-question-btn').textContent = 'Finish Quiz';
            }
        } else {
            finishQuiz();
        }
    }

    function finishQuiz() {
        const timeSpent = parseInt(document.getElementById('timer').dataset.seconds);
        submitGame(quizScore, timeSpent);
    }

    // Initialize
    loadQuestion(0);
    </script>
    @endif

    <!-- Universal Submit Form -->
    <form id="submit-form" action="{{ route('games.submit', $game) }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">
        <input type="hidden" name="score" id="final-score">
        <input type="hidden" name="time_spent" id="final-time">
    </form>

    <script>
    // Timer
    let seconds = 0;
    setInterval(() => {
        seconds++;
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        const timerEl = document.getElementById('timer');
        timerEl.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        timerEl.dataset.seconds = seconds;
    }, 1000);

    // Universal submit function
    function submitGame(score, timeSpent) {
        document.getElementById('final-score').value = score;
        document.getElementById('final-time').value = timeSpent;
        document.getElementById('submit-form').submit();
    }
    </script>
</div>
@endsection
