<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar & Reminders - NormNinja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">Calendar & Reminders</h1>
                    <a href="{{ 
                        auth()->user()->role === 'admin' ? route('admin.dashboard') : 
                        (auth()->user()->role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard'))
                    }}" class="text-blue-600 hover:text-blue-800">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </header>

        <!-- Success Message -->
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Side - Calendar (2/3 width) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-800">Calendar</h2>
                            <button onclick="openEventModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                                + Add Event
                            </button>
                        </div>
                        <div id="calendar"></div>
                    </div>
                </div>

                <!-- Right Side - Reminders (1/3 width) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-800">Reminders</h2>
                            <button onclick="openReminderModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                                + Add
                            </button>
                        </div>

                        <!-- Reminders List -->
                        <div class="space-y-3 max-h-[600px] overflow-y-auto">
                            @forelse($reminders->sortBy('reminder_date') as $reminder)
                                <div class="border rounded-lg p-4 {{ $reminder->is_completed ? 'bg-gray-50 opacity-60' : 'bg-white' }} 
                                    {{ $reminder->reminder_date < now() && !$reminder->is_completed ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3 flex-1">
                                            <input type="checkbox" 
                                                   {{ $reminder->is_completed ? 'checked' : '' }}
                                                   onchange="toggleReminder({{ $reminder->id }})"
                                                   class="mt-1 h-5 w-5 text-green-600 rounded focus:ring-green-500">
                                            <div class="flex-1">
                                                <h3 class="font-semibold {{ $reminder->is_completed ? 'line-through text-gray-500' : 'text-gray-800' }}">
                                                    {{ $reminder->title }}
                                                </h3>
                                                @if($reminder->description)
                                                    <p class="text-sm text-gray-600 mt-1">{{ $reminder->description }}</p>
                                                @endif
                                                <div class="flex items-center space-x-2 mt-2">
                                                    <span class="text-xs px-2 py-1 rounded
                                                        @if($reminder->priority === 'high') bg-red-100 text-red-800
                                                        @elseif($reminder->priority === 'medium') bg-yellow-100 text-yellow-800
                                                        @else bg-blue-100 text-blue-800
                                                        @endif">
                                                        {{ ucfirst($reminder->priority) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $reminder->reminder_date->format('M d, Y h:i A') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-1 ml-2">
                                            <button onclick="editReminder({{ $reminder->id }}, '{{ $reminder->title }}', '{{ $reminder->description }}', '{{ $reminder->reminder_date->format('Y-m-d\TH:i') }}', '{{ $reminder->priority }}')" 
                                                    class="text-blue-600 hover:text-blue-800 p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure?')" 
                                                        class="text-red-600 hover:text-red-800 p-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <p>No reminders yet</p>
                                    <p class="text-sm mt-1">Click "Add" to create your first reminder</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Event Modal -->
    <div id="eventModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 id="eventModalTitle" class="text-lg font-bold text-gray-900">Add Event</h3>
                <button onclick="closeEventModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="eventForm" action="{{ route('calendar.store') }}" method="POST">
                @csrf
                <input type="hidden" id="eventMethod" name="_method" value="">
                <input type="hidden" id="eventId" value="">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Title *</label>
                    <input type="text" name="title" id="eventTitle" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea name="description" id="eventDescription" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Start Date *</label>
                    <input type="datetime-local" name="start_date" id="eventStartDate" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">End Date</label>
                    <input type="datetime-local" name="end_date" id="eventEndDate"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Color</label>
                    <input type="color" name="color" id="eventColor" value="#3B82F6"
                           class="w-20 h-10 rounded cursor-pointer">
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="all_day" id="eventAllDay" class="mr-2 h-4 w-4 text-blue-600">
                        <span class="text-gray-700 text-sm font-bold">All Day Event</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEventModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reminder Modal -->
    <div id="reminderModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 id="reminderModalTitle" class="text-lg font-bold text-gray-900">Add Reminder</h3>
                <button onclick="closeReminderModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="reminderForm" action="{{ route('reminders.store') }}" method="POST">
                @csrf
                <input type="hidden" id="reminderMethod" name="_method" value="">
                <input type="hidden" id="reminderId" value="">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Title *</label>
                    <input type="text" name="title" id="reminderTitle" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea name="description" id="reminderDescription" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Reminder Date *</label>
                    <input type="datetime-local" name="reminder_date" id="reminderDate" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Priority *</label>
                    <select name="priority" id="reminderPriority" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeReminderModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Save Reminder
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Calendar initialization
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($calendarEvents), {
                    return[
                        'id' => $event->id,
                        'title' => $event->title,
                        'start' => $event->start_date->toIso8601String(),
                        'end' => $event->end_date ? $event->end_date->toIso8601String() : null,
                        'backgroundColor' => $event->color,
                        'borderColor' => $event->color,
                        'allDay' => $event->all_day,
                        'description' => $event->description,
                    ];
                })),
                eventClick: function(info) {
                    editEvent(
                        info.event.id,
                        info.event.title,
                        info.event.extendedProps.description || '',
                        info.event.start.toISOString().slice(0, 16),
                        info.event.end ? info.event.end.toISOString().slice(0, 16) : '',
                        info.event.backgroundColor,
                        info.event.allDay
                    );
                },
                height: 'auto'
            });
            calendar.render();
        });

        // Event Modal Functions
        function openEventModal() {
            document.getElementById('eventModalTitle').textContent = 'Add Event';
            document.getElementById('eventForm').action = '{{ route("calendar.store") }}';
            document.getElementById('eventMethod').value = '';
            document.getElementById('eventId').value = '';
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventDescription').value = '';
            document.getElementById('eventStartDate').value = '';
            document.getElementById('eventEndDate').value = '';
            document.getElementById('eventColor').value = '#3B82F6';
            document.getElementById('eventAllDay').checked = false;
            document.getElementById('eventModal').classList.remove('hidden');
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.add('hidden');
        }

        function editEvent(id, title, description, startDate, endDate, color, allDay) {
            document.getElementById('eventModalTitle').textContent = 'Edit Event';
            document.getElementById('eventForm').action = `/calendar/${id}`;
            document.getElementById('eventMethod').value = 'PUT';
            document.getElementById('eventId').value = id;
            document.getElementById('eventTitle').value = title;
            document.getElementById('eventDescription').value = description;
            document.getElementById('eventStartDate').value = startDate;
            document.getElementById('eventEndDate').value = endDate;
            document.getElementById('eventColor').value = color;
            document.getElementById('eventAllDay').checked = allDay;
            document.getElementById('eventModal').classList.remove('hidden');
        }

        // Reminder Modal Functions
        function openReminderModal() {
            document.getElementById('reminderModalTitle').textContent = 'Add Reminder';
            document.getElementById('reminderForm').action = '{{ route("reminders.store") }}';
            document.getElementById('reminderMethod').value = '';
            document.getElementById('reminderId').value = '';
            document.getElementById('reminderTitle').value = '';
            document.getElementById('reminderDescription').value = '';
            document.getElementById('reminderDate').value = '';
            document.getElementById('reminderPriority').value = 'medium';
            document.getElementById('reminderModal').classList.remove('hidden');
        }

        function closeReminderModal() {
            document.getElementById('reminderModal').classList.add('hidden');
        }

        function editReminder(id, title, description, date, priority) {
            document.getElementById('reminderModalTitle').textContent = 'Edit Reminder';
            document.getElementById('reminderForm').action = `/reminders/${id}`;
            document.getElementById('reminderMethod').value = 'PUT';
            document.getElementById('reminderId').value = id;
            document.getElementById('reminderTitle').value = title;
            document.getElementById('reminderDescription').value = description;
            document.getElementById('reminderDate').value = date;
            document.getElementById('reminderPriority').value = priority;
            document.getElementById('reminderModal').classList.remove('hidden');
        }

        function toggleReminder(id) {
            fetch(`/reminders/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                window.location.reload();
            });
        }
    </script>
</body>
</html>