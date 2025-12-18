@extends('layouts.app')

@section('title', 'Game Results')

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
            <div class="inline-block bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full p-6 mb-4">
                <i class="fas fa-trophy text-6xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Game Complete!</h1>
            <p class="text-xl text-gray-600">{{ $game->title }}</p>
        </div>

        <!-- Score Card -->
        <div class="bg-white rounded-lg shadow-xl p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <!-- Score -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6">
                    <p class="text-sm font-semibold text-green-700 uppercase tracking-wide mb-2">Your Score</p>
                    <p class="text-5xl font-bold text-green-600">{{ $attempt->score }}</p>
                    @if($game->game_type === 'gamified_quiz')
                    <p class="text-sm text-gray-600 mt-2">
                        out of {{ collect($game->game_data['questions'])->sum('points') }} possible
                    </p>
                    @endif
                </div>

                <!-- Time Taken -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6">
                    <p class="text-sm font-semibold text-blue-700 uppercase tracking-wide mb-2">Time Taken</p>
                    <p class="text-5xl font-bold text-blue-600">{{ $attempt->formatted_time }}</p>
                    <p class="text-sm text-gray-600 mt-2">minutes:seconds</p>
                </div>

                <!-- Completion -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6">
                    <p class="text-sm font-semibold text-purple-700 uppercase tracking-wide mb-2">Status</p>
                    <div class="text-5xl mb-2">
                        <i class="fas fa-check-circle text-purple-600"></i>
                    </div>
                    <p class="text-lg font-bold text-purple-600">Completed</p>
                </div>
            </div>

            <!-- Performance Message -->
            <div class="mt-8 text-center">
                @php
                    $percentage = 0;
                    if ($game->game_type === 'gamified_quiz') {
                        $total = collect($game->game_data['questions'])->sum('points');
                        $percentage = $total > 0 ? ($attempt->score / $total) * 100 : 0;
                    } elseif ($game->game_type === 'matching') {
                        $total = count($game->game_data['pairs']) * 10;
                        $percentage = $total > 0 ? ($attempt->score / $total) * 100 : 0;
                    } else {
                        $percentage = 100; // Flashcards
                    }
                @endphp

                @if($percentage >= 90)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <p class="text-lg font-bold text-yellow-800">
                        <i class="fas fa-star mr-2"></i>Outstanding Performance!
                    </p>
                    <p class="text-yellow-700 mt-1">You've mastered this topic! Keep up the excellent work!</p>
                </div>
                @elseif($percentage >= 70)
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <p class="text-lg font-bold text-green-800">
                        <i class="fas fa-thumbs-up mr-2"></i>Great Job!
                    </p>
                    <p class="text-green-700 mt-1">You're doing really well! A bit more practice and you'll be perfect!</p>
                </div>
                @elseif($percentage >= 50)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <p class="text-lg font-bold text-blue-800">
                        <i class="fas fa-check mr-2"></i>Good Effort!
                    </p>
                    <p class="text-blue-700 mt-1">You're on the right track! Review the material and try again for a better score.</p>
                </div>
                @else
                <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded">
                    <p class="text-lg font-bold text-orange-800">
                        <i class="fas fa-redo mr-2"></i>Keep Practicing!
                    </p>
                    <p class="text-orange-700 mt-1">Don't give up! Review the material and try again. You'll improve with practice!</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 justify-center mb-8">
            <a href="{{ route('games.play', $game) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-bold text-lg transition duration-200">
                <i class="fas fa-redo mr-2"></i>Play Again
            </a>
            <a href="{{ route('games.show', $game) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg font-bold text-lg transition duration-200">
                <i class="fas fa-info-circle mr-2"></i>Game Details
            </a>
            <a href="{{ route('games.index') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-bold text-lg transition duration-200">
                <i class="fas fa-gamepad mr-2"></i>More Games
            </a>
        </div>

        <!-- Attempt History -->
        @if($allAttempts->count() > 1)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-history mr-2 text-indigo-600"></i>Your Attempt History
            </h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Attempt
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Score
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Time
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($allAttempts as $index => $historyAttempt)
                        <tr class="{{ $historyAttempt->id === $attempt->id ? 'bg-indigo-50' : 'hover:bg-gray-50' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($historyAttempt->id === $attempt->id)
                                    <span class="bg-indigo-600 text-white text-xs px-2 py-1 rounded mr-2">Current</span>
                                    @endif
                                    <span class="text-sm font-medium text-gray-900">
                                        Attempt #{{ $allAttempts->count() - $index }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $historyAttempt->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $historyAttempt->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($historyAttempt->is_completed)
                                <div class="text-lg font-bold text-gray-900">
                                    {{ $historyAttempt->score }}
                                </div>
                                @else
                                <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($historyAttempt->is_completed)
                                <div class="text-sm text-gray-900">
                                    {{ $historyAttempt->formatted_time }}
                                </div>
                                @else
                                <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($historyAttempt->is_completed)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i> Completed
                                </span>
                                @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Incomplete
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Best Score Highlight -->
            @php
                $bestScore = $allAttempts->where('is_completed', true)->max('score');
                $bestTime = $allAttempts->where('is_completed', true)->min('time_spent_seconds');
            @endphp
            
            @if($bestScore !== null)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-4 border-l-4 border-yellow-400">
                    <p class="text-sm font-semibold text-yellow-800 uppercase">Your Best Score</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $bestScore }}</p>
                </div>
                @if($bestTime !== null)
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border-l-4 border-blue-400">
                    <p class="text-sm font-semibold text-blue-800 uppercase">Your Best Time</p>
                    <p class="text-3xl font-bold text-blue-600">
                        {{ sprintf('%d:%02d', floor($bestTime / 60), $bestTime % 60) }}
                    </p>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- Progress Comparison (if multiple attempts) -->
        @if($allAttempts->where('is_completed', true)->count() > 1)
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-line mr-2 text-indigo-600"></i>Your Progress
            </h3>
            <div class="space-y-3">
                @php
                    $completedAttempts = $allAttempts->where('is_completed', true)->reverse();
                    $scores = $completedAttempts->pluck('score')->toArray();
                    $trend = count($scores) > 1 && end($scores) > $scores[0] ? 'up' : (count($scores) > 1 && end($scores) < $scores[0] ? 'down' : 'stable');
                @endphp
                
                @if($trend === 'up')
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <p class="font-bold text-green-800">
                        <i class="fas fa-arrow-up mr-2"></i>Improving!
                    </p>
                    <p class="text-green-700">Your scores are getting better. Keep up the great work!</p>
                </div>
                @elseif($trend === 'down')
                <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded">
                    <p class="font-bold text-orange-800">
                        <i class="fas fa-arrow-down mr-2"></i>Review Needed
                    </p>
                    <p class="text-orange-700">Your recent scores are lower. Consider reviewing the material again.</p>
                </div>
                @else
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <p class="font-bold text-blue-800">
                        <i class="fas fa-minus mr-2"></i>Consistent Performance
                    </p>
                    <p class="text-blue-700">You're performing consistently. Try to improve your score!</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
