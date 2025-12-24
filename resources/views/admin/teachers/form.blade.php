<!-- Teacher Form Component - Reusable for Create and Edit -->
<div class="space-y-6">
    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Full Name <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               id="name" 
               name="name" 
               value="{{ old('name', $teacher->name ?? '') }}" 
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
               placeholder="Enter full name">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email Address <span class="text-red-500">*</span>
        </label>
        <input type="email" 
               id="email" 
               name="email" 
               value="{{ old('email', $teacher->email ?? '') }}" 
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror"
               placeholder="teacher@example.com">
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Teacher ID -->
    <div>
        <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">
            Teacher ID <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               id="teacher_id" 
               name="teacher_id" 
               value="{{ old('teacher_id', $teacher->teacher_id ?? '') }}" 
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('teacher_id') border-red-500 @enderror"
               placeholder="T-001">
        @error('teacher_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Phone -->
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
            Phone Number
        </label>
        <input type="text" 
               id="phone" 
               name="phone" 
               value="{{ old('phone', $teacher->phone ?? '') }}" 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('phone') border-red-500 @enderror"
               placeholder="+60123456789">
        @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password (only for create or if updating) -->
    @if(!isset($teacher) || !$teacher->exists)
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Password <span class="text-red-500">*</span>
        </label>
        <input type="password" 
               id="password" 
               name="password" 
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('password') border-red-500 @enderror"
               placeholder="Enter password">
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            Confirm Password <span class="text-red-500">*</span>
        </label>
        <input type="password" 
               id="password_confirmation" 
               name="password_confirmation" 
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
               placeholder="Confirm password">
    </div>
    @else
    <!-- For edit mode, password is optional -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Leave password fields empty to keep the current password
                </p>
            </div>
        </div>
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            New Password (Optional)
        </label>
        <input type="password" 
               id="password" 
               name="password" 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('password') border-red-500 @enderror"
               placeholder="Enter new password">
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            Confirm New Password
        </label>
        <input type="password" 
               id="password_confirmation" 
               name="password_confirmation" 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
               placeholder="Confirm new password">
    </div>
    @endif

    <!-- Profile Picture -->
    <div>
        <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">
            Profile Picture
        </label>
        @if(isset($teacher) && $teacher->profile_picture)
            <div class="mb-3">
                <img src="{{ Storage::url($teacher->profile_picture) }}" alt="Current profile picture" class="h-20 w-20 rounded-full object-cover border-2 border-gray-200">
                <p class="text-sm text-gray-500 mt-1">Current profile picture</p>
            </div>
        @endif
        <input type="file" 
               id="profile_picture" 
               name="profile_picture" 
               accept="image/*"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('profile_picture') border-red-500 @enderror">
        <p class="mt-1 text-sm text-gray-500">Accepted formats: JPG, PNG, GIF (Max: 2MB)</p>
        @error('profile_picture')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Status -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Account Status <span class="text-red-500">*</span>
        </label>
        <div class="flex items-center space-x-4">
            <label class="inline-flex items-center">
                <input type="radio" 
                       name="is_active" 
                       value="1" 
                       {{ old('is_active', $teacher->is_active ?? 1) == 1 ? 'checked' : '' }}
                       class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700">Active</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" 
                       name="is_active" 
                       value="0" 
                       {{ old('is_active', $teacher->is_active ?? 1) == 0 ? 'checked' : '' }}
                       class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700">Inactive</span>
            </label>
        </div>
        @error('is_active')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit Buttons -->
    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
        <a href="{{ route('admin.teachers.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition duration-200">
            Cancel
        </a>
        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition duration-200">
            <i class="fas fa-save mr-2"></i>{{ isset($teacher) && $teacher->exists ? 'Update Teacher' : 'Create Teacher' }}
        </button>
    </div>
</div>