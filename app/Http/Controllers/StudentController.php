<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\Quiz;
use App\Models\Game;
use App\Models\Forum;
use App\Models\CalendarEvent;
use App\Models\Reminder;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isStudent()) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function calendarStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        CalendarEvent::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Event added successfully!');
    }

    public function calendarUpdate(Request $request, $id)
    {
    $request->validate([
        'title' => 'required|string|max:255',
        'date' => 'required|date',
    ]);

    $event = CalendarEvent::where('user_id', auth()->id())->findOrFail($id);
    $event->update([
        'title' => $request->title,
        'date' => $request->date,
    ]);

    return redirect()->back()->with('success', 'Event updated successfully!');
    }

    public function calendarDelete($id)
    {
    $event = CalendarEvent::where('user_id', auth()->id())->findOrFail($id);
    $event->delete();

    return redirect()->back()->with('success', 'Event deleted successfully!');
    }



public function dashboard()
{
    $student = auth()->user();

    // Recent activities
    $recentQuizAttempts = $student->quizAttempts()
        ->with('quiz')
        ->where('is_completed', true)
        ->latest()
        ->take(5)
        ->get();

    $recentGameAttempts = $student->gameAttempts()
        ->with('game')
        ->latest()
        ->take(5)
        ->get();

    // Calculate average quiz score
    $completedAttempts = $student->quizAttempts()
        ->where('is_completed', true)
        ->get();

    $averageScore = 0;
    if ($completedAttempts->count() > 0) {
        $totalPercentage = 0;
        foreach ($completedAttempts as $attempt) {
            if ($attempt->total_points > 0) {
                $totalPercentage += ($attempt->score / $attempt->total_points) * 100;
            }
        }
        $averageScore = round($totalPercentage / $completedAttempts->count(), 2);
    }

    // Stats
    $stats = [
        'completed_quizzes' => $student->quizAttempts()->where('is_completed', true)->count(),
        'average_quiz_score' => $averageScore,
        'games_played'       => $student->gameAttempts()->count(),
        'materials_available' => \App\Models\LearningMaterial::where('is_published', true)->count(),
        'active_forums'       => \App\Models\Forum::where('is_active', true)->count(),
    ];

    // Available content
    $availableMaterials = LearningMaterial::where('is_published', true)->latest()->take(5)->get();
    $availableQuizzes   = Quiz::where('is_published', true)->latest()->take(5)->get();
    $availableGames     = Game::where('is_published', true)->latest()->take(5)->get();

    // New: calendar + reminders
    $calendarEvents = CalendarEvent::where('user_id', $student->id)->get();
    $reminders      = Reminder::where('user_id', $student->id)->get();

    return view('student.dashboard', compact(
        'stats',
        'recentQuizAttempts',
        'recentGameAttempts',
        'availableMaterials',
        'availableQuizzes',
        'availableGames',
        'calendarEvents',
        'reminders'
    ));
}
}