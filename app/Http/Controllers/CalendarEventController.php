<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarEventController extends Controller
{
    /**
     * Display a listing of the user's calendar events.
     */
    public function index()
    {
        $events = CalendarEvent::where('user_id', Auth::id())
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($events);
    }

    /**
     * Store a newly created calendar event.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7' // Hex color code
        ]);

        $event = CalendarEvent::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'date' => $request->date,
            'description' => $request->description,
            'color' => $request->color ?? '#4F46E5'
        ]);

        return response()->json([
            'message' => 'Event created successfully!',
            'event' => $event
        ], 201);
    }

    /**
     * Update the specified calendar event.
     */
    public function update(Request $request, CalendarEvent $event)
    {
        // Ensure user owns this event
        if ($event->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7'
        ]);

        $event->update([
            'title' => $request->title,
            'date' => $request->date,
            'description' => $request->description,
            'color' => $request->color ?? $event->color
        ]);

        return response()->json([
            'message' => 'Event updated successfully!',
            'event' => $event
        ]);
    }

    /**
     * Remove the specified calendar event.
     */
    public function destroy(CalendarEvent $event)
    {
        // Ensure user owns this event
        if ($event->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully!'
        ]);
    }
}