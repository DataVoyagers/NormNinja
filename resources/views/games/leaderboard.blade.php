@extends('layouts.app')

@section('title', 'Game Leaderboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>Game Leaderboard
                </h1>
                <p class="text-gray-600 mt-2">
                    @if(isset($game) && $game)
                        Top performers for {{ $game->title }}
                    @else
                        Top performing students across all games
                    @endif
                </p>
            </div>
            <a href="{{ route('games.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Games
            </a>
        </div>

        <!-- Game Filter (only shown on overall leaderboard) -->
        @if(!isset($game) || !$game)
        <div class="mt-6 bg-white rounded-lg shadow-md p-4">
            <form method="GET" action="{{ route('games.leaderboard') }}" class="flex items-center gap-4">
                <label for="game_filter" class="text-gray-700 font-semibold">Filter by Game:</label>
                <select name="game_id" id="game_filter" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" onchange="if(this.value) window.location.href='{{ route('games.leaderboard') }}/' + this.value; else this.form.submit();">
                    <option value="">All Games</option>
                    @foreach($games as $g)
                        <option value="{{ $g->id }}">{{ $g->title }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-filter mr-2"></i>Apply
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Leaderboard Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        @if($leaderboard->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-indigo-600 to-purple-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Student</th>
                        @if(isset($game) && $game)
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Best Score</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Time</th>
                        @else
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Total Score</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Games Played</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Avg Score</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($leaderboard as $entry)
                    <tr class="hover:bg-gray-50 transition duration-150 {{ $entry['rank'] <= 3 ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($entry['rank'] == 1)
                                    <span class="text-3xl">🥇</span>
                                @elseif($entry['rank'] == 2)
                                    <span class="text-3xl">🥈</span>
                                @elseif($entry['rank'] == 3)
                                    <span class="text-3xl">🥉</span>
                                @else
                                    <span class="text-xl font-bold text-gray-600">{{ $entry['rank'] }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($entry['student']->profile_picture)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $entry['student']->profile_picture) }}" alt="{{ $entry['student']->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-semibold text-lg">{{ substr($entry['student']->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $entry['student']->name }}
                                        @if(auth()->id() == $entry['student']->id)
                                            <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full font-semibold">You</span>
                                        @endif
                                    </div>
                                    @if($entry['student']->student_id)
                                        <div class="text-sm text-gray-500">{{ $entry['student']->student_id }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        @if(isset($game) && $game)
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-green-600">{{ $entry['score'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @php
                                        $minutes = floor($entry['time'] / 60);
                                        $seconds = $entry['time'] % 60;
                                    @endphp
                                    @if($minutes > 0)
                                        {{ $minutes }}m {{ $seconds }}s
                                    @else
                                        {{ $seconds }}s
                                    @endif
                                </div>
                            </td>
                        @else
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-indigo-600">{{ number_format($entry['total_score']) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-gamepad text-gray-400 mr-1"></i>{{ $entry['games_played'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-700">{{ $entry['avg_score'] }}</div>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-trophy text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No leaderboard data available yet</p>
            <p class="text-gray-400 text-sm mt-2">Students need to complete games to appear on the leaderboard</p>
        </div>
        @endif
    </div>

    <!-- Legend -->
    @if($leaderboard->count() > 0)
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
            <div class="text-sm text-blue-800">
                <p class="font-semibold mb-1">Leaderboard Information:</p>
                <ul class="list-disc list-inside space-y-1">
                    @if(isset($game) && $game)
                        <li>Rankings are based on the highest score achieved by each student</li>
                        <li>In case of a tie, the student with the faster time ranks higher</li>
                    @else
                        <li>Rankings are based on total cumulative score across all completed games</li>
                        <li>Games Played shows the number of games each student has completed</li>
                        <li>Average Score is calculated from all completed game attempts</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
