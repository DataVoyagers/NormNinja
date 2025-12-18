@extends('layouts.app')

@section('title', 'Upload Learning Material')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('learning-materials.index') }}" class="text-purple-600 hover:text-purple-800 mr-4">
                <i class="fas fa-arrow-left"></i> Back to Materials
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Upload Learning Material</h1>
        <p class="text-gray-600 mt-2">Share educational resources with your students</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8 max-w-3xl">
        <form id="uploadForm" action="{{ route('learning-materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-gray-700 font-semibold mb-2">
                    Material Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('title') border-red-500 @enderror"
                       placeholder="e.g., Chapter 5 Notes, Video Tutorial"
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
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Describe what this material covers...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Upload -->
            <div id="drop-area" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-500 transition cursor-pointer">
                <input type="file"
                    name="file"
                    id="fileInput"
                    class="hidden"
                    accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.avi,.mov"
                    required onchange="updateFileName(this)">
                <div>
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600 mb-1">Click to upload or drag and drop</p>
                    <p class="text-sm text-gray-500">PDF, DOC, PPT, or Video (Max 40MB)</p>
                    <p id="file-name" class="text-sm text-purple-600 font-semibold mt-2"></p>
                </div>
            </div>

            <script>
            const MAX_SIZE_MB = 40;
            const dropArea = document.getElementById('drop-area');
            const fileInput = document.getElementById('fileInput');
            const fileNameDisplay = document.getElementById('file-name');

            // Open file dialog when drop area is clicked
            dropArea.addEventListener('click', () => fileInput.click());

            // Show file name and check size
            fileInput.addEventListener('change', handleFiles);

            function handleFiles() {
                const file = fileInput.files[0];
                if (!file) return;

                if (file.size > MAX_SIZE_MB * 1024 * 1024) {
                    alert(`File is too large! Maximum allowed size is ${MAX_SIZE_MB} MB.`);
                    fileInput.value = '';
                    fileNameDisplay.textContent = '';
                } else {
                    fileNameDisplay.textContent = `📎 ${file.name}`;
                }
            }

            // Drag & drop visual feedback
            dropArea.addEventListener('dragover', e => {
                e.preventDefault();
                dropArea.classList.add('border-purple-500', 'bg-purple-50');
            });

            dropArea.addEventListener('dragleave', e => {
                e.preventDefault();
                dropArea.classList.remove('border-purple-500', 'bg-purple-50');
            });

            dropArea.addEventListener('drop', e => {
                e.preventDefault();
                dropArea.classList.remove('border-purple-500', 'bg-purple-50');

                const files = e.dataTransfer.files;
                if (files.length) {
                    fileInput.files = files; // assign dropped file
                    handleFiles();
                }
            });
            </script>

            <!-- Subject and Grade Level Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-gray-700 font-semibold mb-2">
                        Subject
                    </label>
                    <input type="text" 
                           name="subject" 
                           id="subject" 
                           value="{{ old('subject') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="e.g., Mathematics, Science">
                    @error('subject')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grade Level -->
                <div>
                    <label for="grade_level" class="block text-gray-700 font-semibold mb-2">
                        Grade Level
                    </label>
                    <input type="number" 
                           name="grade_level" 
                           id="grade_level" 
                           value="{{ old('grade_level') }}"
                           min="1"
                           max="12"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="e.g., 9">
                    @error('grade_level')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Published Status -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_published" 
                           value="1" 
                           {{ old('is_published', true) ? 'checked' : '' }}
                           class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <span class="ml-2 text-gray-700 font-semibold">
                        Publish immediately (students can access)
                    </span>
                </label>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-blue-800">Supported File Types</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>Documents:</strong> PDF, DOC, DOCX</li>
                                <li><strong>Presentations:</strong> PPT, PPTX</li>
                                <li><strong>Videos:</strong> MP4, AVI, MOV</li>
                                <li><strong>Max Size:</strong> 40 MB per file</li>
                            </ul>
                        </div>
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
                    <i class="fas fa-upload mr-2"></i>Upload Material
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