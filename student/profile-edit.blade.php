@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-indigo-600 text-white px-6 py-4">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </h1>
            </div>

            <!-- Profile Content -->
            <div class="p-6">
                <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Read-only Information Section -->
                    <div class="border-b pb-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Account Information (Read-Only)</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-indigo-600"></i>Full Name
                                </label>
                                <input type="text" value="{{ $user->name }}" readonly
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 cursor-not-allowed">
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-2 text-indigo-600"></i>Email Address
                                </label>
                                <input type="email" value="{{ $user->email }}" readonly
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 cursor-not-allowed">
                            </div>

                            <!-- Student ID -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-id-card mr-2 text-indigo-600"></i>Student ID
                                </label>
                                <input type="text" value="{{ $user->student_id ?? 'N/A' }}" readonly
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 cursor-not-allowed">
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar mr-2 text-indigo-600"></i>Date of Birth
                                </label>
                                <input type="text" value="{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('F d, Y') : 'N/A' }}" readonly
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    <!-- Editable Information Section -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Editable Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Phone Number -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-2 text-indigo-600"></i>Phone Number
                                </label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror"
                                    placeholder="Enter your phone number">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-indigo-600"></i>Address
                                </label>
                                <textarea id="address" name="address" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror"
                                    placeholder="Enter your address">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Change Password Section -->
                    <div class="border-t pt-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Change Password (Optional)</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-2 text-indigo-600"></i>New Password
                                </label>
                                <input type="password" id="password" name="password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror"
                                    placeholder="Leave blank to keep current password">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-2 text-indigo-600"></i>Confirm New Password
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Confirm your new password">
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>Password must be at least 8 characters long
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <a href="{{ route('student.profile') }}"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-150">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection