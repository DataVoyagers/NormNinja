@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-md p-6 text-white">
        <h1 class="text-3xl font-bold">Welcome back, {{ auth()->user()->name }}! 🎓</h1>
        <p class="mt-2">Let's continue your learning journey today!</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Overall Course Progress -->
        @php
            $progress = $stats['course_progress'];
    
            // Determine color based on progress
            if ($progress >= 75) {
                $borderColor = 'border-green-500';
                $bgColor = 'bg-green-100';
                $textColor = 'text-green-600';
                $barColor = 'bg-green-500';
            } elseif ($progress >= 50) {
                $borderColor = 'border-blue-500';
                $bgColor = 'bg-blue-100';
                $textColor = 'text-blue-600';
                $barColor = 'bg-blue-500';
            } elseif ($progress >= 25) {
                $borderColor = 'border-yellow-500';
                $bgColor = 'bg-yellow-100';
                $textColor = 'text-yellow-600';
                $barColor = 'bg-yellow-500';
            } else {
                $borderColor = 'border-red-500';
                $bgColor = 'bg-red-100';
                $textColor = 'text-red-600';
                $barColor = 'bg-red-500';
            }
        @endphp

        <!-- Course Progress -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 {{ $borderColor }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Course Progress</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $progress }}%</p>
                </div>
                <div class="{{ $bgColor }} rounded-full p-3">
                    <i class="fas fa-chart-line text-2xl {{ $textColor }}"></i>
                </div>
            </div>
            <!-- Progress bar with dynamic color -->
            <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-300" 
                    style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <!-- Completed Quizzes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Quizzes Done</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $completedQuizzesCount }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>


        <!-- Games Played -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Games Played</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['games_played'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-gamepad text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Materials Available -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Materials</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['materials_available'] }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class="fas fa-book text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>

        <!-- Active Forums -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-pink-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Forums</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['active_forums'] }}</p>
                </div>
                <div class="bg-pink-100 rounded-full p-3">
                    <i class="fas fa-comments text-2xl text-pink-600"></i>
                </div>
            </div>
        </div>
    </div>


      <!-- Available Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Learning Materials -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-book text-blue-600 mr-2"></i>
                Learning Materials
            </h2>
            @if($availableMaterials->count() > 0)
                <div class="space-y-2">
                    @foreach($availableMaterials as $material)
                    <a href="{{ route('learning-materials.show', $material) }}" class="block p-3 border rounded hover:bg-blue-50 transition duration-200">
                        <div class="font-semibold text-gray-800 text-sm">{{ Str::limit($material->title, 40) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $material->subject }}</div>
                    </a>
                    @endforeach
                </div>
                <a href="{{ route('learning-materials.index') }}" class="block text-center mt-4 text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All Materials
                </a>
            @else
                <p class="text-gray-500 text-center py-4">No materials available</p>
            @endif
        </div>

        <!-- Available Quizzes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-question-circle text-green-600 mr-2"></i>
                Available Quizzes
            </h2>
            @if($availableQuizzes->count() > 0)
                <div class="space-y-2">
                    @foreach($availableQuizzes as $quiz)
                    <a href="{{ route('quizzes.show', $quiz) }}" class="block p-3 border rounded hover:bg-green-50 transition duration-200">
                        <div class="font-semibold text-gray-800 text-sm">{{ Str::limit($quiz->title, 40) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $quiz->questions->count() }} questions</div>
                    </a>
                    @endforeach
                </div>
                <a href="{{ route('quizzes.index') }}" class="block text-center mt-4 text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All Quizzes
                </a>
            @else
                <p class="text-gray-500 text-center py-4">No quizzes available</p>
            @endif
        </div>

        <!-- Available Games -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-gamepad text-purple-600 mr-2"></i>
                Educational Games
            </h2>
            @if($availableGames->count() > 0)
                <div class="space-y-2">
                    @foreach($availableGames as $game)
                    <a href="{{ route('games.show', $game) }}" class="block p-3 border rounded hover:bg-purple-50 transition duration-200">
                        <div class="font-semibold text-gray-800 text-sm">{{ Str::limit($game->title, 40) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ ucfirst(str_replace('_', ' ', $game->game_type)) }}</div>
                    </a>
                    @endforeach
                </div>
                <a href="{{ route('games.index') }}" class="block text-center mt-4 text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All Games
                </a>
            @else
                <p class="text-gray-500 text-center py-4">No games available</p>
            @endif
        </div>
    </div>
</div>


  <!-- Calendar Section -->
   <div class="mt-12">
     <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Calendar (2/3 width) -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-calendar text-indigo-600 mr-2"></i>
                    Schedule Calendar
                </h2>
            </div>


            <!-- Calendar Navigation -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800" id="currentMonth"></h3>
                <div class="flex gap-2">
                    <button onclick="previousMonth()" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button onclick="currentMonth()" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Today
                    </button>
                    <button onclick="nextMonth()" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="border rounded-lg overflow-hidden">
                <!-- Day Headers -->
                <div class="grid grid-cols-7 bg-gray-100">
                    <div class="p-2 text-center text-xs font-semibold text-gray-600">Sun</div>
                    <div class="p-2 text-center text-xs font-semibold text-gray-600">Mon</div>
                    <div class="p-2 text-center text-xs font-semibold text-gray-600">Tue</div>
                    <div class="p-2 text-center text-xs font-semibold text-gray-600">Wed</div>
                    <div class="p-2 text-center text-xs font-semibold text-gray-600">Thu</div>
                    <div class="p-2 text-center text-xs font-semibold text-gray-600">Fri</div>
                    <div class="p-2 text-center text-xs font-semibold text-gray-600">Sat</div>
                </div>

                <!-- Calendar Days -->
                <div id="calendarDays" class="grid grid-cols-7">
                    <!-- Days will be populated by JavaScript -->
                </div>
            </div>

            <!-- Add Event Button -->
            <button onclick="openAddEventModal()" class="mt-4 w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i>Add Event
            </button>
        </div>

        <!-- Events List (1/3 width) -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Upcoming Events</h3>
            <div id="eventsList" class="space-y-2 max-h-[500px] overflow-y-auto">
                <!-- Events will be populated here -->
            </div>
        </div>
    </div>
</div>
  

<!-- Add/Edit Event Modal -->
<div id="eventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96 shadow-xl">
        <h3 class="text-xl font-bold text-gray-800 mb-4" id="eventModalTitle">Add Event</h3>
        <form id="eventForm" onsubmit="saveEvent(event)">
            <input type="hidden" id="eventId">
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Title <span class="text-red-500">*</span> </label>
                <input type="text" 
                       id="eventTitle" 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                       required>
            
                <!-- <p id="titleHint" class="text-xs mt-1 text-gray-500">
                    • Must be at least 3 characters
                </p>   -->
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date <span class="text-red-500">*</span> </label>
                <input type="date" 
                       id="eventDate" 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                       required>
            
                 <p id="dateHint" class="text-xs mt-1 text-gray-500">
                    • Date cannot be in the past
                </p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                <textarea id="eventDescription" 
                          class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                          rows="3"></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Color</label>
                <select id="eventColor" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="#4F46E5">Indigo</option>
                    <option value="#10B981">Green</option>
                    <option value="#F59E0B">Orange</option>
                    <option value="#EF4444">Red</option>
                    <option value="#8B5CF6">Purple</option>
                    <option value="#EC4899">Pink</option>
                </select>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" 
                        onclick="closeEventModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Save Event
                </button>
            </div>
        </form>
    </div>
</div>


    <!-- Quick Access -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12">
        <!-- Recent Quiz Attempts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Recent Quiz Results</h2>
                <a href="{{ route('quizzes.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Sort Dropdown -->
            <div class="mb-4">
                <form method="GET" action="{{ route('student.dashboard') }}" id="quizSortForm">
                    @if(request('game_sort'))
                        <input type="hidden" name="game_sort" value="{{ request('game_sort') }}">
                    @endif
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-semibold text-gray-600">Sort by:</label>
                        <select name="quiz_sort"
                                onchange="document.getElementById('quizSortForm').submit()"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="alphabet_az" {{ $quizSort == 'alphabet_az' ? 'selected' : '' }}>Alphabet (A-Z)</option>
                            <option value="alphabet_za" {{ $quizSort == 'alphabet_za' ? 'selected' : '' }}>Alphabet (Z-A)</option>
                            <option value="date_newest" {{ $quizSort == 'date_newest' ? 'selected' : '' }}>Date (Newest)</option>
                            <option value="date_oldest" {{ $quizSort == 'date_oldest' ? 'selected' : '' }}>Date (Oldest)</option>
                            <option value="time_newest" {{ $quizSort == 'time_newest' ? 'selected' : '' }}>Time (Newest)</option>
                            <option value="time_oldest" {{ $quizSort == 'time_oldest' ? 'selected' : '' }}>Time (Oldest)</option>
                        </select>
                    </div>
                </form>
            </div>
            @if($recentQuizAttempts->count() > 0)
                <div class="space-y-3">
                    @foreach($recentQuizAttempts as $attempt)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">
                                   {{ optional($attempt->quiz)->title ?? 'This quiz is no longer available' }} </h3>

                                <p class="text-sm text-gray-500 mt-1">{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y') : '' }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $attempt->percentage }}%
                                </div>
                                <div class="text-xs {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $attempt->passed ? 'Passed' : 'Failed' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-clipboard-list text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">No quiz attempts yet</p>
                    <a href="{{ route('quizzes.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold mt-2 inline-block">
                        Take Your First Quiz
                    </a>
                </div>
            @endif
        </div>


        <!-- Recent Game Attempts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Recent Game Scores</h2>
                <a href="{{ route('games.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Sort Dropdown -->
            <div class="mb-4">
                <form method="GET" action="{{ route('student.dashboard') }}" id="gameSortForm">
                    @if(request('quiz_sort'))
                        <input type="hidden" name="quiz_sort" value="{{ request('quiz_sort') }}">
                    @endif
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-semibold text-gray-600">Sort by:</label>
                        <select name="game_sort"
                                onchange="document.getElementById('gameSortForm').submit()"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="alphabet_az" {{ $gameSort == 'alphabet_az' ? 'selected' : '' }}>Alphabet (A-Z)</option>
                            <option value="alphabet_za" {{ $gameSort == 'alphabet_za' ? 'selected' : '' }}>Alphabet (Z-A)</option>
                            <option value="date_newest" {{ $gameSort == 'date_newest' ? 'selected' : '' }}>Date (Newest)</option>
                            <option value="date_oldest" {{ $gameSort == 'date_oldest' ? 'selected' : '' }}>Date (Oldest)</option>
                            <option value="time_newest" {{ $gameSort == 'time_newest' ? 'selected' : '' }}>Time (Newest)</option>
                            <option value="time_oldest" {{ $gameSort == 'time_oldest' ? 'selected' : '' }}>Time (Oldest)</option>
                        </select>
                    </div>
                </form>
            </div>
            @if($recentGameAttempts->count() > 0)
                <div class="space-y-3">
                    @foreach($recentGameAttempts as $attempt)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">{{ $attempt->game?->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ gmdate('i:s', $attempt->time_spent_seconds) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ $attempt->score }}
                                </div>
                                <div class="text-xs text-gray-500">points</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-gamepad text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">No games played yet</p>
                    <a href="{{ route('games.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold mt-2 inline-block">
                        Play Your First Game
                    </a>
                </div>
            @endif
        </div>
    </div>

  

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
// Calendar functionality with CRUD
// Initialize with Malaysia timezone (UTC+8)
const now = new Date();
const malaysiaOffset = 8 * 60;
const localOffset = now.getTimezoneOffset();
const malaysiaTime = new Date(now.getTime() + (malaysiaOffset + localOffset) * 60000);
let currentDate = new Date(malaysiaTime.getFullYear(), malaysiaTime.getMonth(), malaysiaTime.getDate());
let events = [];

// Get CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// API endpoints
const API = {
    events: {
        index: '/calendar-events',
        store: '/calendar-events',
        update: (id) => `/calendar-events/${id}`,
        destroy: (id) => `/calendar-events/${id}`
    }
};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadEvents();
    renderCalendar();
});

function isDateValid() {
    const dateInput = document.getElementById('eventDate');
    const dateValue = dateInput.value;

    if (!dateValue) return false;

    const selectedDate = new Date(dateValue);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    return selectedDate >= today;
}


// ===== CALENDAR EVENTS CRUD =====

async function loadEvents() {
    try {
        const response = await fetch(API.events.index, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            events = await response.json();
            renderCalendar();
            renderEventsList();
        }
    } catch (error) {
        console.error('Error loading events:', error);
        showNotification('Failed to load events', 'error');
    }
}

async function saveEvent(e) {
    e.preventDefault();
    console.log("saveEvent triggered");
    if (!isDateValid()) {
    showNotification('Event date is invalid. Please select a valid date.', 'error');
    return; 
    }

    const eventId = document.getElementById('eventId').value;
    
    const data = {
        title: document.getElementById('eventTitle').value,
        date: document.getElementById('eventDate').value,
        description: document.getElementById('eventDescription').value,
        color: document.getElementById('eventColor').value
    };
    
    try {
        const isEdit = eventId !== '';
        const url = isEdit ? API.events.update(eventId) : API.events.store;
        const method = isEdit ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showNotification(result.message, 'success');
            await loadEvents();
            closeEventModal();
        } else {
            showNotification(result.message || 'Failed to save event', 'error');
        }
    } catch (error) {
        console.error('Error saving event:', error);
        showNotification('Failed to save event', 'error');
    }
}

async function deleteEvent(id) {
    if (!confirm('Are you sure you want to delete this event?')) return;
    
    try {
        const response = await fetch(API.events.destroy(id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showNotification(result.message, 'success');
            await loadEvents();
        } else {
            showNotification(result.message || 'Failed to delete event', 'error');
        }
    } catch (error) {
        console.error('Error deleting event:', error);
        showNotification('Failed to delete event', 'error');
    }
}

function editEvent(event) {
    document.getElementById('eventModalTitle').textContent = 'Edit Event';
    document.getElementById('eventId').value = event.id;
    document.getElementById('eventTitle').value = event.title;
    
    // Handle date format (might have timestamp)
    const dateValue = event.date.includes(' ') ? event.date.split(' ')[0] : event.date;
    document.getElementById('eventDate').value = dateValue;
    
    document.getElementById('eventDescription').value = event.description || '';
    document.getElementById('eventColor').value = event.color || '#4F46E5';
    document.getElementById('eventModal').classList.remove('hidden');
}

// ===== RENDERING FUNCTIONS =====

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    // Update month display
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
    document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
    
    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    
    const calendarDays = document.getElementById('calendarDays');
    calendarDays.innerHTML = '';
    
    // Previous month days
    for (let i = firstDay - 1; i >= 0; i--) {
        const day = daysInPrevMonth - i;
        const dayDiv = createDayElement(day, 'text-gray-400 bg-gray-50', null);
        calendarDays.appendChild(dayDiv);
    }
    
    // Current month days
    const today = new Date();
    // Get today's date in Malaysia timezone (UTC+8)
    const malaysiaOffset = 8 * 60; // Malaysia is UTC+8
    const localOffset = today.getTimezoneOffset();
    const malaysiaTime = new Date(today.getTime() + (malaysiaOffset + localOffset) * 60000);
    const todayStr = `${malaysiaTime.getFullYear()}-${String(malaysiaTime.getMonth() + 1).padStart(2, '0')}-${String(malaysiaTime.getDate()).padStart(2, '0')}`;

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const isToday = dateStr === todayStr;
        
        // Find events for this day (handle both date formats)
        const dayEvents = events.filter(e => {
            const eventDate = e.date.includes(' ') ? e.date.split(' ')[0] : e.date;
            return eventDate === dateStr;
        });
        
        let className = '';
        if (isToday) {
            className = 'bg-indigo-600 text-white font-bold';
        } else if (dayEvents.length > 0) {
            className = 'bg-blue-50';
        }
        
        const indicator = dayEvents.length > 0 ? `<span class="text-xs block mt-1" style="color: ${dayEvents[0].color}">●</span>` : '';
        const dayDiv = createDayElement(day, className, indicator);
        calendarDays.appendChild(dayDiv);
    }
    
    // Next month days
    const totalCells = calendarDays.children.length;
    const remainingCells = 42 - totalCells;
    for (let day = 1; day <= remainingCells; day++) {
        const dayDiv = createDayElement(day, 'text-gray-400 bg-gray-50', null);
        calendarDays.appendChild(dayDiv);
    }
}

function createDayElement(day, className, indicator) {
    const div = document.createElement('div');
    div.className = `p-3 border text-center text-sm ${className}`;
    div.innerHTML = `${day}${indicator || ''}`;
    return div;
}

function renderEventsList() {
    const eventsList = document.getElementById('eventsList');
    
    if (events.length === 0) {
        eventsList.innerHTML = '<p class="text-gray-400 text-center text-sm">No events scheduled</p>';
        return;
    }
    
    // Sort events by date
    const sortedEvents = [...events].sort((a, b) => {
        const dateA = a.date.includes(' ') ? a.date.split(' ')[0] : a.date;
        const dateB = b.date.includes(' ') ? b.date.split(' ')[0] : b.date;
        return new Date(dateA) - new Date(dateB);
    });
    
    eventsList.innerHTML = sortedEvents.map(event => `
        <div class="p-3 border rounded hover:bg-gray-50 transition" style="border-left: 4px solid ${event.color}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800 text-sm">${event.title}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-calendar mr-1"></i>${formatDate(event.date)}
                    </p>
                    ${event.description ? `<p class="text-xs text-gray-600 mt-1">${event.description}</p>` : ''}
                </div>
                <div class="flex gap-2 ml-2">
                    <button onclick='editEvent(${JSON.stringify(event)})' class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    <button onclick="deleteEvent(${event.id})" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// ===== MODAL FUNCTIONS =====

function openAddEventModal() {
    document.getElementById('eventModalTitle').textContent = 'Add Event';
    document.getElementById('eventId').value = '';
    document.getElementById('eventTitle').value = '';
    document.getElementById('eventDate').value = '';
    document.getElementById('eventDescription').value = '';
    document.getElementById('eventColor').value = '#4F46E5';
    document.getElementById('eventModal').classList.remove('hidden');
}

function closeEventModal() {
    document.getElementById('eventModal').classList.add('hidden');
}

// ===== NAVIGATION FUNCTIONS =====

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

function currentMonth() {
    const now = new Date();
    // Get current date in Malaysia timezone (UTC+8)
    const malaysiaOffset = 8 * 60; // Malaysia is UTC+8
    const localOffset = now.getTimezoneOffset();
    const malaysiaTime = new Date(now.getTime() + (malaysiaOffset + localOffset) * 60000);
    currentDate = new Date(malaysiaTime.getFullYear(), malaysiaTime.getMonth(), malaysiaTime.getDate());
    renderCalendar();
}

// ===== HELPER FUNCTIONS =====

function formatDate(dateStr) {
    if (!dateStr) return 'No date';
    
    try {
        let cleanDate = dateStr;
        
        // Handle datetime format (2025-11-22 00:00:00)
        if (typeof dateStr === 'string' && dateStr.includes(' ')) {
            cleanDate = dateStr.split(' ')[0];
        }
        
        // Handle ISO format (2025-11-22T00:00:00Z)
        if (typeof dateStr === 'string' && dateStr.includes('T')) {
            cleanDate = dateStr.split('T')[0];
        }
        
        const date = new Date(cleanDate + 'T00:00:00');
        
        if (isNaN(date.getTime())) {
            return 'Invalid Date';
        }
        
        const options = { month: 'short', day: 'numeric', year: 'numeric' };
        return date.toLocaleDateString('en-US', options);
        
    } catch (error) {
        return 'Invalid Date';
    }
}

function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>

@endsection
