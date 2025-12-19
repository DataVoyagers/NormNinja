@extends('layouts.app')
//
@section('title', 'Edit Forum')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('forums.index') }}" class="text-pink-600 hover:text-pink-800 mr-4">
                <i class="fas fa-arrow-left"></i> Back to Forums
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Forum</h1>
        <p class="text-gray-600 mt-2">Update forum settings and information</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8 max-w-3xl">
        <form action="{{ route('forums.update', $forum) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-gray-700 font-semibold mb-2">
                    Forum Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title', $forum->title) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('title') border-red-500 @enderror"
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
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $forum->description) }}</textarea>
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
                       value="{{ old('subject', $forum->subject) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
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
                           {{ old('is_active', $forum->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 text-pink-600 border-gray-300 rounded focus:ring-pink-500">
                    <span class="ml-2 text-gray-700 font-semibold">
                        Active (students can post)
                    </span>
                </label>
                <p class="text-sm text-gray-500 mt-1 ml-7">
                    Uncheck to close the forum and prevent new posts
                </p>
            </div>

            <!-- Forum Statistics -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Forum Statistics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Total Posts</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $forum->posts()->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $forum->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Discussions</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $forum->topLevelPosts()->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Activity</p>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $forum->posts()->latest()->first()?->created_at->diffForHumans() ?? 'No activity' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Warning Box -->
            @if(!$forum->is_active)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-yellow-800">Forum is Currently Closed</h3>
                        <p class="mt-1 text-sm text-yellow-700">
                            Students cannot create new posts or replies in this forum.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('forums.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-save mr-2"></i>Update Forum
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
