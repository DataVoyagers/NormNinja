@extends('layouts.app')

@section('title', 'Edit Learning Material')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8 text-center">
        <div class="flex items-center mb-4">
            <a href="{{ route('learning-materials.index') }}" class="text-purple-600 hover:text-purple-800 mr-4">
                <i class="fas fa-arrow-left"></i> Back to Materials
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Learning Material</h1>
        <p class="text-gray-600 mt-2">Update material information and content</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8 max-w-3xl mx-auto">
        <form action="{{ route('learning-materials.update', $learningMaterial) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-gray-700 font-semibold mb-2">
                    Material Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title', $learningMaterial->title) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('title') border-red-500 @enderror"
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
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $learningMaterial->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current File Info -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    Current File
                </label>
                <div class="bg-gray-50 rounded-lg p-4 border">
                    <div class="flex items-center">
                        <i class="fas fa-file-{{ $learningMaterial->file_type === 'pdf' ? 'pdf' : ($learningMaterial->file_type === 'doc' || $learningMaterial->file_type === 'docx' ? 'word' : ($learningMaterial->file_type === 'ppt' || $learningMaterial->file_type === 'pptx' ? 'powerpoint' : 'video')) }} text-3xl text-purple-600 mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-800">{{ basename($learningMaterial->file_path) }}</p>
                            <p class="text-sm text-gray-500">Type: {{ strtoupper($learningMaterial->file_type) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Replace File (Optional) -->
            <div class="mb-6">
                <label for="file" class="block text-gray-700 font-semibold mb-2">
                    Replace File (Optional)
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-500 transition">
                    <input type="file" 
                           name="file" 
                           id="file" 
                           class="hidden"
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.avi,.mov"
                           onchange="updateFileName(this)">
                    <label for="file" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600 mb-1">Click to upload a new file</p>
                        <p class="text-sm text-gray-500">PDF, DOC, PPT, or Video (Max 50MB)</p>
                        <p id="file-name" class="text-sm text-purple-600 font-semibold mt-2"></p>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Leave empty to keep the current file
                </p>
                @error('file')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Published Status -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_published" 
                           value="1" 
                           {{ old('is_published', $learningMaterial->is_published) ? 'checked' : '' }}
                           class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <span class="ml-2 text-gray-700 font-semibold">
                        Published (students can access)
                    </span>
                </label>
            </div>

            <!-- Material Statistics -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Material Statistics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $learningMaterial->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $learningMaterial->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('learning-materials.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-save mr-2"></i>Update Material
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name;
    const fileNameDisplay = document.getElementById('file-name');
    if (fileName) {
        fileNameDisplay.textContent = '📎 ' + fileName;
    } else {
        fileNameDisplay.textContent = '';
    }
}
</script>
@endsection