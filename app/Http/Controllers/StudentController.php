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
        CalendarEvent::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'date' => $request->date,
            
        ]);
        return back();
    }

    public function calendarUpdate(Request $request, CalendarEvent $event)
    {
        $event->update($request->only(['title', 'date']));
        return back();
    }

    public function calendarDelete(CalendarEvent $event)
    {
        $event->delete();
        return back();
    }

    public function reminderStore(Request $request)
    {
        Reminder::create([
            'user_id' => auth()->id(),
            'text' => $request->text,
            'date' => $request->date,
        ]);
        return back();
    }

    public function reminderUpdate(Request $request, Reminder $reminder)
    {
        $reminder->update($request->only(['text', 'date']));
        return back();
    }

    public function reminderDelete(Reminder $reminder)
    {
        $reminder->delete();
        return back();
    }

    public function dashboard()
    {
        $student = auth()->user();

        // Get recent activities
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

        // Calculate average quiz score manually
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

        // Statistics
        $stats = [
            'completed_quizzes' => $student->quizAttempts()->where('is_completed', true)->count(),
            'average_quiz_score' => $averageScore,
            'games_played' => $student->gameAttempts()->count(),
            'materials_available' => LearningMaterial::where('is_published', true)->count(),
            'active_forums' => Forum::where('is_active', true)->count(),
            'calendarEvents' => CalendarEvent::where('user_id', $student->id)->get(),
            'reminders' => Reminder::where('user_id', $student->id)->get(),
        ];

        // Available content
        $availableMaterials = LearningMaterial::where('is_published', true)->latest()->take(5)->get();
        $availableQuizzes = Quiz::where('is_published', true)->latest()->take(5)->get();
        $availableGames = Game::where('is_published', true)->latest()->take(5)->get();
        $calendarEvents = CalendarEvent::where('user_id', $student->id)->get();
        $reminders = Reminder::where('user_id', $student->id)->get();

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