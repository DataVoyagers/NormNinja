@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-teal-600 to-green-600 rounded-lg shadow-md p-6 text-white">
        <h1 class="text-3xl font-bold">Welcome back, {{ auth()->user()->name }}! 👨‍🏫</h1>
        <p class="mt-2">Manage your classes and engage with your students!</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Total Students -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Students</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_students'] ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-user-graduate text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Quizzes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Quizzes</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_quizzes'] ?? 0 }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-question-circle text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Games -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Games</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_games'] ?? 0 }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-gamepad text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Materials -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase">Materials</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_materials'] ?? 0 }}</p>
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
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_forums'] ?? 0 }}</p>
                </div>
                <div class="bg-pink-100 rounded-full p-3">
                    <i class="fas fa-comments text-2xl text-pink-600"></i>
                </div>
            </div>
        </div>
    </div>


<!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Create Quiz -->
        <a href="{{ route('quizzes.create') }}" class="bg-green-500 hover:bg-green-600 rounded-lg shadow-md p-6 text-white transition transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <i class="fas fa-plus-circle text-4xl mb-2"></i>
                    <p class="text-lg font-bold">Create Quiz</p>
                </div>
            </div>
        </a>

        <!-- Create Game -->
        <a href="{{ route('games.create') }}" class="bg-purple-500 hover:bg-purple-600 rounded-lg shadow-md p-6 text-white transition transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <i class="fas fa-gamepad text-4xl mb-2"></i>
                    <p class="text-lg font-bold">Create Game</p>
                </div>
            </div>
        </a>

        <!-- Upload Material -->
        <a href="{{ route('learning-materials.create') }}" class="bg-orange-500 hover:bg-orange-600 rounded-lg shadow-md p-6 text-white transition transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <i class="fas fa-upload text-4xl mb-2"></i>
                    <p class="text-lg font-bold">Upload Material</p>
                </div>
            </div>
        </a>

        <!-- View Performance -->
        <a href="{{ route('teacher.student-performance') }}" class="bg-blue-500 hover:bg-blue-600 rounded-lg shadow-md p-6 text-white transition transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <i class="fas fa-chart-bar text-4xl mb-2"></i>
                    <p class="text-lg font-bold">Student Performance</p>
                </div>
            </div>
        </a>
    </div>
</div>


    <!-- Calendar Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-6 mb-10">
        <!-- Calendar (2/3 width) -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6 mt-1">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-calendar text-teal-600 mr-2"></i>
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
            <button onclick="openAddEventModal()" class="mt-4 w-full bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 transition">
                <i class="fas fa-plus mr-2"></i>Add Event
            </button>
        </div>

        <!-- Events List (1/3 width) -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-1">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Upcoming Events</h3>
            <div id="eventsList" class="space-y-2 max-h-[600px] overflow-y-auto">
                <!-- Events will be populated here -->
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
                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Title<span class="text-red-500">*</span> </label>
                <input type="text" 
                       id="eventTitle" 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-teal-500" 
                       required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date<span class="text-red-500">*</span> </label>
                <input type="date" 
                       id="eventDate" 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-teal-500" 
                       required>

                <p id="dateHint" class="text-xs mt-1 text-gray-500">
                    • Date cannot be in the past
                </p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                <textarea id="eventDescription" 
                          class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-teal-500" 
                          rows="3"></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Color</label>
                <select id="eventColor" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="#14B8A6">Teal</option>
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
                        class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition">
                    Save Event
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Recent Quiz Attempts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-clipboard-check text-green-600 mr-2"></i>
                Recent Quiz Attempts
            </h2>
            @if(isset($recentQuizAttempts) && $recentQuizAttempts->count() > 0)
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($recentQuizAttempts->take(5) as $attempt)
                    <div class="border rounded-lg p-3 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 text-sm">{{ $attempt->student->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $attempt->quiz->title ?? 'Quiz Deleted' }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-clock mr-1"></i>{{ $attempt->completed_at ? $attempt->completed_at->diffForHumans() : 'In Progress' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
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
                <a href="{{ route('teacher.student-performance') }}" class="block text-center mt-4 text-teal-600 hover:text-teal-800 text-sm font-semibold">
                    View All Performance Data <i class="fas fa-arrow-right ml-1"></i>
                </a>
            @else
                <p class="text-gray-400 text-center py-8">No quiz attempts yet</p>
            @endif
        </div>

        <!-- Recent Game Attempts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-trophy text-purple-600 mr-2"></i>
                Recent Game Scores
            </h2>
            @if(isset($recentGameAttempts) && $recentGameAttempts->count() > 0)
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($recentGameAttempts->take(5) as $attempt)
                    <div class="border rounded-lg p-3 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 text-sm">{{ $attempt->student->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $attempt->game->title }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-clock mr-1"></i>{{ gmdate('i:s', $attempt->time_spent_seconds) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-bold text-purple-600">
                                    {{ $attempt->score }}
                                </div>
                                <div class="text-xs text-gray-500">points</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-8">No game attempts yet</p>
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

function isDateValid(dateStr) {
    if (!dateStr) return false;
    const selected = new Date(dateStr + 'T00:00:00');
    const today = getMalaysiaToday();
    return selected >= getMalaysiaToday();
}

function getMalaysiaToday() {
    const now = new Date();
    const malaysiaOffset = 8 * 60; // UTC+8
    const localOffset = now.getTimezoneOffset();
    const malaysiaTime = new Date(now.getTime() + (malaysiaOffset + localOffset) * 60000);

    return new Date(malaysiaTime.getFullYear(), malaysiaTime.getMonth(), malaysiaTime.getDate());
}



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

// ===== CALENDAR EVENTS CRUD =====

async function loadEvents() {
    try {
        const response = await fetch(API.events.index, {
            credentials: 'same-origin',
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
    
    const dateValue = document.getElementById('eventDate').value;

    if (!isDateValid(dateValue)) {
        showNotification('Event date is invalid. Please select a valid date.', 'error');
        document.getElementById('eventDate').classList.add('border-red-500', 'focus:ring-red-500');
        return; 
    } else {
        document.getElementById('eventDate').classList.remove('border-red-500', 'focus:ring-red-500');
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
            credentials: 'same-origin',
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
            credentials: 'same-origin',
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
    
    const dateValue = event.date.includes(' ') ? event.date.split(' ')[0] : event.date;
    document.getElementById('eventDate').value = dateValue;
    
    document.getElementById('eventDescription').value = event.description || '';
    document.getElementById('eventColor').value = event.color || '#14B8A6';
    document.getElementById('eventModal').classList.remove('hidden');
}

// ===== RENDERING FUNCTIONS =====

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
    document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
    
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
        
        const dayEvents = events.filter(e => {
            const eventDate = e.date.includes(' ') ? e.date.split(' ')[0] : e.date;
            return eventDate === dateStr;
        });
        
        let className = '';
        if (isToday) {
            className = 'bg-teal-600 text-white font-bold';
        } else if (dayEvents.length > 0) {
            className = 'bg-teal-50';
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
    
    eventsList.innerHTML = sortedEvents.map(event => {
        const eventDescription = event.description ? `<p class="text-xs text-gray-600 mt-1">${event.description}</p>` : '';
        return `
            <div class="p-3 border rounded hover:bg-gray-50 transition" style="border-left: 4px solid ${event.color}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">${event.title}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-calendar mr-1"></i>${formatDate(event.date)}
                        </p>
                        ${eventDescription}
                    </div>
                    <div class="flex gap-2 ml-2">
                        <!-- Edit Button -->
                        <button class="text-blue-500 hover:text-blue-700" 
                                data-event='${JSON.stringify(event)}' 
                                onclick="editEventFromButton(this)">
                            <i class="fas fa-edit text-sm"></i>
                        </button>
                        <!-- Delete Button -->
                        <button onclick="deleteEvent(${event.id})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Helper function for edit button
function editEventFromButton(button) {
    const event = JSON.parse(button.getAttribute('data-event'));
    editEvent(event);
}

// ===== MODAL FUNCTIONS =====

function openAddEventModal() {
    document.getElementById('eventModalTitle').textContent = 'Add Event';
    document.getElementById('eventId').value = '';
    document.getElementById('eventTitle').value = '';
    document.getElementById('eventDate').value = '';
    document.getElementById('eventDescription').value = '';
    document.getElementById('eventColor').value = '#14B8A6';
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
        
        if (typeof dateStr === 'string' && dateStr.includes(' ')) {
            cleanDate = dateStr.split(' ')[0];
        }
        
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