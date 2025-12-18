@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-indigo-600 text-white px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-user-circle mr-2"></i>My Profile
                </h1>
                <a href="{{ route('student.profile.edit') }}"
                   class="bg-white text-indigo-600 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors duration-150 font-semibold">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
            </div>

            <!-- Profile Content -->
            <div class="p-6">
                <!-- Personal Information Section -->
                <div class="border-b pb-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Account Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">
                                <i class="fas fa-user mr-2 text-indigo-600"></i>Full Name
                            </label>
                            <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">
                                <i class="fas fa-envelope mr-2 text-indigo-600"></i>Email Address
                            </label>
                            <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                        </div>

                        <!-- Student ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">
                                <i class="fas fa-id-card mr-2 text-indigo-600"></i>Student ID
                            </label>
                            <p class="text-gray-900 font-medium">{{ $user->student_id ?? 'N/A' }}</p>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">
                                <i class="fas fa-calendar mr-2 text-indigo-600"></i>Date of Birth
                            </label>
                            <p class="text-gray-900 font-medium">
                                {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('F d, Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Contact Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">
                                <i class="fas fa-phone mr-2 text-indigo-600"></i>Phone Number
                            </label>
                            <p class="text-gray-900 font-medium">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">
                                <i class="fas fa-map-marker-alt mr-2 text-indigo-600"></i>Address
                            </label>
                            <p class="text-gray-900 font-medium">{{ $user->address ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
                    <a href="{{ route('student.dashboard') }}"
                        class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection