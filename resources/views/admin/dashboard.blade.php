@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Students -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-semibold uppercase">Total Students</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_students'] }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-user-graduate text-3xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-blue-100 text-sm">{{ $stats['active_students'] }} active</span>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-semibold uppercase">Total Teachers</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_teachers'] }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-chalkboard-teacher text-3xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-100 text-sm">{{ $stats['active_teachers'] }} active</span>
            </div>
        </div>

        <!-- Active Students -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-semibold uppercase">Active Students</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['active_students'] }}</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Teachers -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-semibold uppercase">Active Teachers</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['active_teachers'] }}</p>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                    <i class="fas fa-user-check text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.students.create') }}" class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-4 text-center transition duration-200">
                <i class="fas fa-user-plus text-3xl text-blue-600 mb-2"></i>
                <p class="text-blue-800 font-semibold">Add Student</p>
            </a>
            <a href="{{ route('admin.teachers.create') }}" class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-4 text-center transition duration-200">
                <i class="fas fa-user-tie text-3xl text-green-600 mb-2"></i>
                <p class="text-green-800 font-semibold">Add Teacher</p>
            </a>
            <a href="{{ route('admin.students') }}" class="bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg p-4 text-center transition duration-200">
                <i class="fas fa-users text-3xl text-purple-600 mb-2"></i>
                <p class="text-purple-800 font-semibold">Manage Students</p>
            </a>
            <a href="{{ route('admin.teachers') }}" class="bg-orange-50 hover:bg-orange-100 border border-orange-200 rounded-lg p-4 text-center transition duration-200">
                <i class="fas fa-chalkboard-teacher text-3xl text-orange-600 mb-2"></i>
                <p class="text-orange-800 font-semibold">Manage Teachers</p>
            </a>
        </div>
    </div>
</div>
@endsection
