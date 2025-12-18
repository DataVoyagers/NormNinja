<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\Quiz;
use App\Models\Game;
use App\Models\Forum;
use App\Models\CalendarEvent;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    public function dashboard(Request $request)
    {
        $student = auth()->user();

        // Get sorting parameters
        $quizSort = $request->get('quiz_sort', 'date_newest');
        $gameSort = $request->get('game_sort', 'date_newest');

        // Get recent quiz attempts with sorting
        $quizQuery = $student->quizAttempts()
            ->with('quiz')
            ->where('is_completed', true);

        // Apply quiz sorting
        switch ($quizSort) {
            case 'alphabet_az':
                $quizQuery->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
                    ->orderBy('quizzes.title', 'asc')
                    ->select('quiz_attempts.*');
                break;
            case 'alphabet_za':
                $quizQuery->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
                    ->orderBy('quizzes.title', 'desc')
                    ->select('quiz_attempts.*');
                break;
            case 'date_oldest':
                $quizQuery->orderBy('completed_at', 'asc');
                break;
            case 'time_oldest':
                $quizQuery->orderBy('completed_at', 'asc');
                break;
            case 'time_newest':
                $quizQuery->orderBy('completed_at', 'desc');
                break;
            case 'date_newest':
            default:
                $quizQuery->orderBy('completed_at', 'desc');
                break;
        }

        $recentQuizAttempts = $quizQuery->take(5)->get();

        // Get recent game attempts with sorting
        $gameQuery = $student->gameAttempts()
            ->with('game');

        // Apply game sorting
        switch ($gameSort) {
            case 'alphabet_az':
                $gameQuery->join('games', 'game_attempts.game_id', '=', 'games.id')
                    ->orderBy('games.title', 'asc')
                    ->select('game_attempts.*');
                break;
            case 'alphabet_za':
                $gameQuery->join('games', 'game_attempts.game_id', '=', 'games.id')
                    ->orderBy('games.title', 'desc')
                    ->select('game_attempts.*');
                break;
            case 'date_oldest':
                $gameQuery->orderBy('created_at', 'asc');
                break;
            case 'date_newest':
                $gameQuery->orderBy('created_at', 'desc');
                break;
            case 'time_oldest':
                $gameQuery->orderBy('time_spent_seconds', 'asc');
                break;
            case 'time_newest':
                $gameQuery->orderBy('time_spent_seconds', 'desc');
                break;
            default:
                $gameQuery->orderBy('created_at', 'desc');
                break;
        }

        $recentGameAttempts = $gameQuery->take(5)->get();

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
            'reminders',
            'quizSort',
            'gameSort'
        ));
    }

    public function showProfile()
    {
        $user = auth()->user();
        return view('student.profile', compact('user'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        return view('student.profile-edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [];

        // Update phone if provided
        if ($request->filled('phone')) {
            $data['phone'] = $request->phone;
        }

        // Update address if provided
        if ($request->filled('address')) {
            $data['address'] = $request->address;
        }

        // Update the user with allowed fields
        if (!empty($data)) {
            $user->update($data);
        }

        // Update password if provided
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully.');
    }
}