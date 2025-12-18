@extends('layouts.app')

@section('title', 'Student Performance')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold text-gray-800">Student Performance Analytics</h1>
        <p class="text-gray-600 mt-2">Monitor student progress and identify those who need support</p>
    </div>

    <!-- Alert for Students Needing Support -->
    @php
        $studentsNeedingSupport = collect($performanceData)->filter(function($data) {
            return $data['needs_support'];
        });
    @endphp

    @if($studentsNeedingSupport->count() > 0)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-red-800">
                    {{ $studentsNeedingSupport->count() }} Student(s) Need Academic Support
                </h3>
                <p class="text-sm text-red-700 mt-1">
                    These students are highlighted below in red and require immediate attention.
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Student
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quiz Performance
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($performanceData as $data)
                    <tr class="{{ $data['needs_support'] ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }}">
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
                                            <i class="fas fa-exclamation-circle text-red-500 ml-2" title="Needs Support"></i>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $data['student']->student_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="h-2 rounded-full {{ $data['avg_quiz_score'] >= 60 ? 'bg-green-500' : 'bg-red-500' }}" 
                                             style="width: {{ min($data['avg_quiz_score'], 100) }}%"></div>
                                    </div>
                                    <span class="font-semibold {{ $data['avg_quiz_score'] >= 60 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $data['avg_quiz_score'] }}%
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $data['completed_quizzes'] }}/{{ $data['total_quizzes'] }} quizzes completed
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($data['needs_support'])
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-hand-paper mr-1"></i>
                                    Needs Support
                                </span>
                                <div class="mt-2 text-xs text-red-700">
                                    @foreach($data['support_reasons'] as $reason)
                                        <div class="flex items-start mt-1">
                                            <i class="fas fa-circle text-xs mr-2 mt-1"></i>
                                            <span>{{ $reason }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    On Track
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
        <p class="text-sm text-gray-600 mb-4">Students are flagged as needing support based on one or more of the following criteria:</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="fas fa-tasks text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-gray-900">Low Completion Rate</h4>
                    <p class="text-sm text-gray-600">Completed less than 50% of quizzes</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-user-clock text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-gray-900">No Engagement</h4>
                    <p class="text-sm text-gray-600">Has not attempted any quizzes</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-arrow-down text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-gray-900">Declining Performance</h4>
                    <p class="text-sm text-gray-600">Recent scores lower than earlier attempts</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection