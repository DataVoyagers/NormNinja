@extends('layouts.app')

@section('title', isset($student) ? 'Edit Student' : 'Add Student')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ isset($student) ? 'Edit Student' : 'Add New Student' }}</h1>
        <p class="text-gray-600 mt-2">{{ isset($student) ? 'Update student information' : 'Create a new student account' }}</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ isset($student) ? route('admin.students.update', $student) : route('admin.students.store') }}" method="POST">
            @csrf
            @if(isset($student))
                @method('PUT')
            @endif

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $student->name ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $student->email ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Student ID -->
            <div class="mb-4">
                <label for="student_id" class="block text-gray-700 font-semibold mb-2">Student ID *</label>
                <input type="text" id="student_id" name="student_id" value="{{ old('student_id', $student->student_id ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('student_id') border-red-500 @enderror">
                @error('student_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-2">
                    Password {{ isset($student) ? '(leave blank to keep current)' : '*' }}
                </label>
                <input type="password" id="password" name="password" {{ !isset($student) ? 'required' : '' }}
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-semibold mb-2">Phone Number</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $student->phone ?? '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label for="address" class="block text-gray-700 font-semibold mb-2">Address</label>
                <textarea id="address" name="address" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('address', $student->address ?? '') }}</textarea>
            </div>

            <!-- Date of Birth -->
            <div class="mb-4">
                <label for="date_of_birth" class="block text-gray-700 font-semibold mb-2">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', isset($student) && $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Active Status (only for edit) -->
            @if(isset($student))
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $student->is_active) ? 'checked' : '' }}
                        class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <span class="text-gray-700 font-semibold">Account Active</span>
                </label>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-4 border-t">
                <a href="{{ route('admin.students') }}" class="text-gray-600 hover:text-gray-800 font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-save mr-2"></i>{{ isset($student) ? 'Update Student' : 'Create Student' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
