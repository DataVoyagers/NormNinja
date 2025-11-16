@extends('layouts.app')

@section('title', 'Learning Materials')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Learning Materials</h1>
            <p class="text-gray-600 mt-2">
                @if(auth()->user()->isTeacher())
                    Manage and share educational resources
                @else
                    Browse and access learning resources
                @endif
            </p>
        </div>
        @if(auth()->user()->isTeacher())
        <a href="{{ route('learning-materials.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
            <i class="fas fa-upload mr-2"></i>Upload Material
        </a>
        @endif
    </div>

    <!-- Materials Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($materials as $material)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200">
            <!-- Material Header with Icon -->
            <div class="p-6">
                <div class="flex items-start">
                    <!-- File Type Icon -->
                    <div class="flex-shrink-0 mr-4">
                        @if($material->file_type === 'pdf')
                            <div class="bg-red-100 text-red-600 p-3 rounded-lg">
                                <i class="fas fa-file-pdf text-3xl"></i>
                            </div>
                        @elseif(in_array($material->file_type, ['mp4', 'avi', 'mov']))
                            <div class="bg-purple-100 text-purple-600 p-3 rounded-lg">
                                <i class="fas fa-file-video text-3xl"></i>
                            </div>
                        @elseif(in_array($material->file_type, ['doc', 'docx']))
                            <div class="bg-blue-100 text-blue-600 p-3 rounded-lg">
                                <i class="fas fa-file-word text-3xl"></i>
                            </div>
                        @elseif(in_array($material->file_type, ['ppt', 'pptx']))
                            <div class="bg-orange-100 text-orange-600 p-3 rounded-lg">
                                <i class="fas fa-file-powerpoint text-3xl"></i>
                            </div>
                        @else
                            <div class="bg-gray-100 text-gray-600 p-3 rounded-lg">
                                <i class="fas fa-file text-3xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Material Info -->
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-gray-800 mb-1">{{ $material->title }}</h3>
                        @if($material->subject)
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-book mr-1"></i>{{ $material->subject }}
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($material->description)
                <p class="text-gray-600 text-sm mt-4">{{ Str::limit($material->description, 120) }}</p>
                @endif

                <!-- Meta Info -->
                <div class="mt-4 flex items-center text-xs text-gray-500 space-x-3">
                    @if($material->grade_level)
                    <span><i class="fas fa-graduation-cap mr-1"></i>Grade {{ $material->grade_level }}</span>
                    @endif
                    <span><i class="fas fa-calendar mr-1"></i>{{ $material->created_at->format('M d, Y') }}</span>
                </div>

                <!-- Status Badge -->
                <div class="mt-3">
                    @if($material->is_published)
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Published
                        </span>
                    @else
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>Draft
                        </span>
                    @endif
                </div>
            </div>

            <!-- Material Footer -->
            <div class="bg-gray-50 px-6 py-3 border-t flex items-center justify-between">
                @if(auth()->user()->isTeacher())
                <div class="space-x-3">
                    <a href="{{ route('learning-materials.show', $material) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('learning-materials.edit', $material) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
                <form action="{{ route('learning-materials.destroy', $material) }}" method="POST" class="inline" onsubmit="return confirm('Delete this material?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
                @else
                <a href="{{ route('learning-materials.show', $material) }}" class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-semibold transition duration-200">
                    <i class="fas fa-download mr-1"></i>Access Material
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
            <i class="fas fa-book-open text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No learning materials available yet</p>
            @if(auth()->user()->isTeacher())
            <a href="{{ route('learning-materials.create') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold mt-2 inline-block">
                Upload your first material
            </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($materials->hasPages())
    <div class="flex justify-center">
        {{ $materials->links() }}
    </div>
    @endif
</div>
@endsection
