<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NormNinja') - Learning Management System</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-indigo-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <img src="/images/logo.png" alt="NormNinja Logo" class="h-10 w-auto mr-3" onerror="this.style.display='none'">
                        <span class="text-2xl font-bold">NormNinja</span>
                    </a>
                </div>

                @auth
                <div class="flex items-center space-x-4">
                    <!-- Navigation Links based on role -->
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.students') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-user-graduate mr-2"></i>Students
                        </a>
                        <a href="{{ route('admin.teachers') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>Teachers
                        </a>
                    @elseif(auth()->user()->isTeacher())
                        <a href="{{ route('teacher.dashboard') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('learning-materials.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-book mr-2"></i>Materials
                        </a>
                        <a href="{{ route('quizzes.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-question-circle mr-2"></i>Quizzes
                        </a>
                        <a href="{{ route('games.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-gamepad mr-2"></i>Games
                        </a>
                        <a href="{{ route('forums.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-comments mr-2"></i>Forums
                        </a>
                        <a href="{{ route('teacher.student-performance') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-chart-line mr-2"></i>Performance
                        </a>
                    @elseif(auth()->user()->isStudent())
                        <a href="{{ route('student.dashboard') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('learning-materials.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-book mr-2"></i>Materials
                        </a>
                        <a href="{{ route('quizzes.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-question-circle mr-2"></i>Quizzes
                        </a>
                        <a href="{{ route('games.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-gamepad mr-2"></i>Games
                        </a>
                        <a href="{{ route('forums.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                            <i class="fas fa-comments mr-2"></i>Forums
                        </a>
                    @endif

                    <!-- User Menu -->
                    <div class="relative">
                        <button id="userMenuButton" type="button" class="flex items-center space-x-2 hover:bg-indigo-700 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-white">
                            <i class="fas fa-user-circle text-2xl"></i>
                            <span>{{ auth()->user()->name }}</span>
                            <i id="dropdownArrow" class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                        </button>
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                            <div class="py-1">
                                <div class="px-4 py-3 text-sm text-gray-700 border-b">
                                    <div class="font-semibold">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</div>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} NormNinja - Data Voyagers Team. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- User Dropdown JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownButton = document.getElementById('userMenuButton');
            const dropdownMenu = document.getElementById('userDropdown');
            const dropdownArrow = document.getElementById('dropdownArrow');
            
            if (dropdownButton && dropdownMenu && dropdownArrow) {
                // Toggle dropdown on button click
                dropdownButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = dropdownMenu.classList.contains('hidden');
                    
                    if (isHidden) {
                        dropdownMenu.classList.remove('hidden');
                        dropdownArrow.style.transform = 'rotate(180deg)';
                    } else {
                        dropdownMenu.classList.add('hidden');
                        dropdownArrow.style.transform = 'rotate(0deg)';
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                        dropdownArrow.style.transform = 'rotate(0deg)';
                    }
                });
                
                // Prevent dropdown from closing when clicking inside it
                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>