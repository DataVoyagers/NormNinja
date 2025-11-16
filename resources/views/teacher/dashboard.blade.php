@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold text-gray-800">Teacher Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Learning Materials -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-semibold uppercase">Materials</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_materials'] }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-book text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Quizzes -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-semibold uppercase">Quizzes</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_quizzes'] }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-question-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Games -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-semibold uppercase">Games</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_games'] }}</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-gamepad text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Forums -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-semibold uppercase">Forums</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_forums'] }}</p>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-comments text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Students -->
        <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm font-semibold uppercase">Students</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_students'] }}</p>
                </div>
                <div class="bg-pink-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('learning-materials.create') }}" class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-4 text-center transition duration-200">
                <i class="fas fa-upload text-2xl text-blue-600 mb-2"></i>
                <p class="text-blue-800 font-semibold text-sm">Upload Material</p>
            </a>
            <a href="{{ route('quizzes.create') }}" class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-4 text-center transition duration-200">
                <i class="fas fa-plus-circle text-2xl text-green-600 mb-2"></i>
                <p class="text-green-800 font-semibold text-sm">Create Quiz</p>
            </a>
            <a href="{{ route('games.create') }}" class="bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg p-4 text-center transition duration-200">
                <i class="fas fa-gamepad text-2xl text-purple-600 mb-2"></i>
                <p class="text-purple-800 font-semibold text-sm">Create Game</p>
            </a>
            <a href="{{ route('forums.create') }}" class="bg-orange-50 hover:bg-orange-100 border border-orange-200 rounded-lg p-4 text-center transition duration-200">
                <i class="fas fa-comments text-2xl text-orange-600 mb-2"></i>
                <p class="text-orange-800 font-semibold text-sm">Create Forum</p>
            </a>
            <a href="{{ route('teacher.student-performance') }}" class="bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg p-4 text-center transition duration-200">
                <i class="fas fa-chart-line text-2xl text-red-600 mb-2"></i>
                <p class="text-red-800 font-semibold text-sm">Student Performance</p>
            </a>
            <a href="{{ route('learning-materials.index') }}" class="bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg p-4 text-center transition duration-200">
                <i class="fas fa-list text-2xl text-indigo-600 mb-2"></i>
                <p class="text-indigo-800 font-semibold text-sm">View All</p>
            </a>
        </div>
    </div>

    <!-- Recent Quiz Attempts -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Recent Quiz Attempts</h2>
        @if($recentQuizAttempts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quiz</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentQuizAttempts as $attempt)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $attempt->student->name }}</div>
                                <div class="text-sm text-gray-500">{{ $attempt->student->student_id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $attempt->quiz->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $attempt->score }}/{{ $attempt->total_points }}</div>
                                <div class="text-sm text-gray-500">{{ $attempt->percentage }}%</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($attempt->passed)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Passed
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Failed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $attempt->completed_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No quiz attempts yet.</p>
        @endif
    </div>
</div>
@endsection
