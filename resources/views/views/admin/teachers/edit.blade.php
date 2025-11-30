@extends('layouts.app')

@section('title', 'Edit Teacher')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.teachers.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Edit Teacher</h1>
                    <p class="text-gray-600 mt-2">Update teacher account information</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <div class="flex-shrink-0 h-12 w-12">
                    @if($teacher->profile_picture)
                        <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-200" src="{{ Storage::url($teacher->profile_picture) }}" alt="{{ $teacher->name }}">
                    @else
                        <div class="h-12 w-12 rounded-full bg-green-500 flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $teacher->name }}</p>
                    <p class="text-xs text-gray-500">{{ $teacher->teacher_id }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 mr-3 text-xl mt-0.5"></i>
                <div>
                    <p class="font-medium mb-2">Please correct the following errors:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @include('admin.teachers.form')
        </form>
    </div>

    <!-- Account Information Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Account Created</p>
                <p class="text-gray-900 font-medium">{{ $teacher->created_at->format('F d, Y') }}</p>
                <p class="text-xs text-gray-500">{{ $teacher->created_at->diffForHumans() }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                <p class="text-gray-900 font-medium">{{ $teacher->updated_at->format('F d, Y') }}</p>
                <p class="text-xs text-gray-500">{{ $teacher->updated_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-lg shadow-md p-6 border-2 border-red-200">
        <h3 class="text-lg font-semibold text-red-600 mb-2">
            <i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone
        </h3>
        <p class="text-gray-600 mb-4">Once you delete this teacher account, there is no going back. Please be certain.</p>
        <form action="{{ route('admin.teachers.delete', $teacher) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this teacher? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition duration-200">
                <i class="fas fa-trash mr-2"></i>Delete Teacher Account
            </button>
        </form>
    </div>

    <!-- Information Card -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Update Information</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Leave password fields empty to keep current password</li>
                        <li>Changes to email will require email verification</li>
                        <li>Setting account to inactive will prevent teacher login</li>
                        <li>All teacher data will be preserved even if account is inactive</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection