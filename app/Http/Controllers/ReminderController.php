<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    /**
     * Display a listing of the user's reminders.
     */
    public function index()
    {
        $reminders = Reminder::where('user_id', Auth::id())
            ->orderBy('is_completed', 'asc')
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reminders);
    }

    /**
     * Store a newly created reminder.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'date' => 'nullable|date'
        ]);

        $reminder = Reminder::create([
            'user_id' => Auth::id(),
            'text' => $request->text,
            'date' => $request->date,
            'is_completed' => false
        ]);

        return response()->json([
            'message' => 'Reminder created successfully!',
            'reminder' => $reminder
        ], 201);
    }

    /**
     * Update the specified reminder.
     */
    public function update(Request $request, Reminder $reminder)
    {
        // Ensure user owns this reminder
        if ($reminder->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'text' => 'required|string|max:500',
            'date' => 'nullable|date'
        ]);

        $reminder->update([
            'text' => $request->text,
            'date' => $request->date
        ]);

        return response()->json([
            'message' => 'Reminder updated successfully!',
            'reminder' => $reminder
        ]);
    }

    /**
     * Toggle the completion status of a reminder.
     */
    public function toggle(Reminder $reminder)
    {
        // Ensure user owns this reminder
        if ($reminder->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reminder->update([
            'is_completed' => !$reminder->is_completed
        ]);

        return response()->json([
            'message' => 'Reminder status updated!',
            'reminder' => $reminder
        ]);
    }

    /**
     * Remove the specified reminder.
     */
    public function destroy(Reminder $reminder)
    {
        // Ensure user owns this reminder
        if ($reminder->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reminder->delete();

        return response()->json([
            'message' => 'Reminder deleted successfully!'
        ]);
    }
}