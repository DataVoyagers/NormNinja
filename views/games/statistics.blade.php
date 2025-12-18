@extends('layouts.app')

@section('title', 'Game Statistics - ' . $game->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('games.index') }}" class="text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Games
            </a>
            <div class="flex gap-2">
                <a href="{{ route('games.show', $game) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-info-circle mr-2"></i>View Game
                </a>
                <button onclick="exportData()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-download mr-2"></i>Export Data
                </button>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $game->title }} - Statistics</h1>
        <p class="text-gray-600 mt-2">Analyze student performance and engagement</p>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Attempts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Attempts</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_attempts'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-play text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Completed Attempts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Completed</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['completed_attempts'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['completion_rate'] }}% completion rate</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Average Score -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Average Score</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">
                        {{ $stats['average_score'] ? round($stats['average_score'], 1) : 'N/A' }}
                    </p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <i class="fas fa-star text-2xl text-indigo-600"></i>
                </div>
            </div>
        </div>

        <!-- Average Time -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Avg. Time</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">
                        @if($stats['average_time'])
                            {{ sprintf('%d:%02d', floor($stats['average_time'] / 60), $stats['average_time'] % 60) }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-clock text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Score Distribution Chart -->
    @if($stats['completed_attempts'] > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>Score Distribution
        </h2>
        <div class="space-y-3">
            @php
                $scoreRanges = [
                    '90-100' => ['min' => 90, 'max' => 100, 'color' => 'green'],
                    '70-89' => ['min' => 70, 'max' => 89, 'color' => 'blue'],
                    '50-69' => ['min' => 50, 'max' => 69, 'color' => 'yellow'],
                    '0-49' => ['min' => 0, 'max' => 49, 'color' => 'red'],
                ];
            @endphp

            @foreach($scoreRanges as $range => $config)
                @php
                    $count = $attempts->where('is_completed', true)
                        ->filter(function($attempt) use ($config) {
                            return $attempt->score >= $config['min'] && $attempt->score <= $config['max'];
                        })->count();
                    $percentage = $stats['completed_attempts'] > 0 ? ($count / $stats['completed_attempts']) * 100 : 0;
                @endphp
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-semibold text-gray-700">{{ $range }}</span>
                        <span class="text-sm font-semibold text-gray-700">{{ $count }} students ({{ round($percentage, 1) }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-{{ $config['color'] }}-600 h-4 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Student Attempts Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-users mr-2 text-indigo-600"></i>Student Attempts
            </h2>
        </div>

        @if($attempts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Student
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Date & Time
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Score
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Time Spent
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Performance
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($attempts as $attempt)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mr-3">
                                    {{ strtoupper(substr($attempt->student->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $attempt->student->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $attempt->student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $attempt->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $attempt->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attempt->is_completed)
                            <div class="text-lg font-bold text-gray-900">{{ $attempt->score }}</div>
                            @else
                            <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attempt->is_completed)
                            <div class="text-sm text-gray-900">{{ $attempt->formatted_time }}</div>
                            @else
                            <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attempt->is_completed)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i> Completed
                            </span>
                            @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> In Progress
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attempt->is_completed)
                                @php
                                    $percentage = 0;
                                    if ($game->game_type === 'gamified_quiz') {
                                        $total = collect($game->game_data['questions'])->sum('points');
                                        $percentage = $total > 0 ? ($attempt->score / $total) * 100 : 0;
                                    } elseif ($game->game_type === 'matching') {
                                        $total = count($game->game_data['pairs']) * 10;
                                        $percentage = $total > 0 ? ($attempt->score / $total) * 100 : 0;
                                    } else {
                                        $percentage = 100;
                                    }
                                @endphp
                                
                                @if($percentage >= 90)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i> Excellent
                                </span>
                                @elseif($percentage >= 70)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-thumbs-up mr-1"></i> Good
                                </span>
                                @elseif($percentage >= 50)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-check mr-1"></i> Average
                                </span>
                                @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-redo mr-1"></i> Needs Review
                                </span>
                                @endif
                            @else
                            <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($attempts->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $attempts->links() }}
        </div>
        @endif
        @else
        <div class="p-12 text-center">
            <i class="fas fa-chart-bar text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No attempts yet</p>
            <p class="text-gray-400 mt-2">Students haven't played this game yet</p>
        </div>
        @endif
    </div>

    <!-- Top Performers -->
    @if($stats['completed_attempts'] > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-trophy mr-2 text-yellow-500"></i>Top Performers
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $topScores = $attempts->where('is_completed', true)
                    ->sortByDesc('score')
                    ->take(3);
            @endphp
            
            @foreach($topScores as $index => $topAttempt)
            <div class="border rounded-lg p-4 {{ $index === 0 ? 'bg-yellow-50 border-yellow-300' : ($index === 1 ? 'bg-gray-50 border-gray-300' : 'bg-orange-50 border-orange-300') }}">
                <div class="flex items-center mb-2">
                    <div class="text-3xl mr-3">
                        @if($index === 0)
                            🥇
                        @elseif($index === 1)
                            🥈
                        @else
                            🥉
                        @endif
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $topAttempt->student->name }}</p>
                        <p class="text-sm text-gray-600">{{ $topAttempt->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-3 pt-3 border-t">
                    <div>
                        <p class="text-xs text-gray-500">Score</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $topAttempt->score }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Time</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $topAttempt->formatted_time }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Export Script -->
<script>
function exportData() {
    // Create CSV content
    let csv = 'Student Name,Email,Date,Time,Score,Time Spent,Status,Performance\n';
    
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const name = cells[0].querySelector('.text-sm.font-medium').textContent;
            const email = cells[0].querySelector('.text-xs.text-gray-500').textContent;
            const date = cells[1].querySelector('.text-sm').textContent;
            const time = cells[1].querySelector('.text-xs').textContent;
            const score = cells[2].textContent.trim();
            const timeSpent = cells[3].textContent.trim();
            const status = cells[4].textContent.trim();
            const performance = cells[5].textContent.trim();
            
            csv += `"${name}","${email}","${date}","${time}","${score}","${timeSpent}","${status}","${performance}"\n`;
        }
    });
    
    // Download CSV
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', 'game_statistics_{{ $game->id }}_{{ date("Y-m-d") }}.csv');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}
</script>
@endsection
