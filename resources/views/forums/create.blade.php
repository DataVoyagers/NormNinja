@extends('layouts.app')

@section('title', 'Create Forum')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('forums.index') }}" class="text-pink-600 hover:text-pink-800 mr-4">
                <i class="fas fa-arrow-left"></i> Back to Forums
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Create New Forum</h1>
        <p class="text-gray-600 mt-2">Start a new discussion space for your students</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8 max-w-3xl">
        <form action="{{ route('forums.store') }}" method="POST">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-gray-700 font-semibold mb-2">
                    Forum Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('title') border-red-500 @enderror"
                       placeholder="e.g., General Discussion, Math Help, Science Questions"
                       required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-gray-700 font-semibold mb-2">
                    Description
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Describe the purpose of this forum...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subject -->
            <div class="mb-6">
                <label for="subject" class="block text-gray-700 font-semibold mb-2">
                    Subject/Category
                </label>
                <input type="text" 
                       name="subject" 
                       id="subject" 
                       value="{{ old('subject') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                       placeholder="e.g., Mathematics, Science, General">
                @error('subject')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-5 h-5 text-pink-600 border-gray-300 rounded focus:ring-pink-500">
                    <span class="ml-2 text-gray-700 font-semibold">
                        Active (students can post)
                    </span>
                </label>
                <p class="text-sm text-gray-500 mt-1 ml-7">
                    Uncheck to close the forum and prevent new posts
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-blue-800">Forum Guidelines</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Choose a clear, descriptive title</li>
                                <li>Set ground rules in the description</li>
                                <li>Monitor discussions regularly</li>
                                <li>Encourage respectful participation</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('forums.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-save mr-2"></i>Create Forum
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
