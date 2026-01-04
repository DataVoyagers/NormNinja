@extends('layouts.app')

@section('title', 'Student Performance')

@section('content')

@php
function progressColor($value) {
    if ($value >= 80) return ['bg-green-500','text-green-600'];
    if ($value < 50) return ['bg-red-500','text-red-600'];
    return ['bg-yellow-400','text-yellow-600'];
}

function remarkColor($reason) {
    $lower = strtolower($reason);
    if(str_contains($lower, 'low') || str_contains($lower,'no') || str_contains($lower,'declining')) return 'text-red-600';
    if(str_contains($lower,'excellent') || str_contains($lower,'high') || str_contains($lower,'on track')) return 'text-green-600';
    return 'text-gray-700';
}
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold text-gray-800">Student Performance Analytics</h1>
        <p class="text-gray-600 mt-2">Monitor student progress and identify students who need support</p>
    </div>

    <!-- Alert for Students Needing Support -->
    @php
        $studentsNeedingSupport = collect($performanceData)->filter(fn($d) => $d['needs_support']);
    @endphp

    @if($studentsNeedingSupport->count() > 0)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-red-800">
                    {{ $studentsNeedingSupport->count() }} Student(s) Need Academic Support
                </h3>
                <p class="text-sm text-red-700 mt-1">
                    These students are highlighted below in red and require attention.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Performance Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quiz Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Game Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($performanceData as $data)
                    <tr class="{{ $data['needs_support'] ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }}">
                        <!-- Student -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($data['student']->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $data['student']->name }}
                                        @if($data['needs_support'])
                                            <i class="fas fa-exclamation-circle text-red-500 ml-1" title="Needs Support"></i>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $data['student']->student_id }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Quiz Progress -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                [$bg, $text] = progressColor($data['quiz_completion_rate']);
                            @endphp
                            <div class="flex flex-col">
                                <div class="flex items-center">
                                    
                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">

                                        <div class="h-2 rounded-full {{ $data['quiz_completion_rate'] >= 80 ? 'bg-green-500' : ($data['quiz_completion_rate'] < 50 ? 'bg-red-500' : 'bg-yellow-400') }}" 
                                             style="width: {{ min($data['quiz_completion_rate'], 100) }}%"></div>
                                    </div>
                                    <span class="font-semibold {{ $data['quiz_completion_rate'] >= 80 ? 'text-green-600' : ($data['quiz_completion_rate'] < 50 ? 'text-red-600' : 'text-yellow-600') }}">
                                        {{ $data['quiz_completion_rate'] }}%
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $data['completed_quizzes'] }}/{{ $data['total_quizzes'] }} quizzes completed
                                </div>
                            </div>
                        </td>

                        <!-- Game Progress -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                [$bg, $text] = progressColor($data['quiz_completion_rate']);
                            @endphp
                            <div class="flex flex-col">
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="h-2 rounded-full {{ $data['games_completion_rate'] >= 80 ? 'bg-green-500' : ($data['games_completion_rate'] < 50 ? 'bg-red-500' : 'bg-yellow-400') }}" 
                                             style="width: {{ min($data['games_completion_rate'], 100) }}%"></div>
                                    </div>
                                    <span class="font-semibold {{ $data['games_completion_rate'] >= 80 ? 'text-green-600' : ($data['games_completion_rate'] < 50 ? 'text-red-600' : 'text-yellow-600') }}">
                                        {{ $data['games_completion_rate'] }}%
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $data['games_played'] }}/{{ $data['total_games'] }} games played
                                </div>
                            </div>
                        </td>

                        <!-- Remarks -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(count($data['support_reasons']) > 0)
                                @foreach($data['support_reasons'] as $reason)
                                    <div class="text-sm {{ remarkColor($reason) }} flex items-start mb-1">
                                        <i class="fas fa-circle text-xs mr-2 mt-1"></i>
                                        <span>{{ $reason }}</span>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-green-600 font-semibold flex items-center">
                                    <i class="fas fa-check-circle mr-1"></i> On Track
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Support Criteria</h3>
        <p class="text-sm text-gray-600 mb-4">
            Students are flagged as needing support based on one or more of the following criteria:
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Low Quiz Performance -->
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-chart-line text-red-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-gray-900">Low Quiz Performance</h4>
                    <p class="text-sm text-gray-600">Average quiz score below 60%</p>
                </div>
            </div>

            <!-- Low Quiz Completion Rate -->
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="fas fa-tasks text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-gray-900">Low Quiz Completion</h4>
                    <p class="text-sm text-gray-600">Completed less than 50% of quizzes</p>
                </div>
            </div>

            <!-- Low Game Completion Rate -->
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-gamepad text-red-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-gray-900">Low Game Completion</h4>
                    <p class="text-sm text-gray-600">Played less than 50% of assigned games</p>
                </div>
            </div>

            <!-- No Quiz or Game Attempts -->
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-user-clock text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-gray-900">No Engagement</h4>
                    <p class="text-sm text-gray-600">Has not attempted any quizzes or games</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
