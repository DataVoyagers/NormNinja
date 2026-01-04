<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isTeacher()) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $teacher = auth()->user();
        
        $stats = [
            'total_materials' => $teacher->learningMaterials()->count(),
            'total_quizzes' => $teacher->quizzes()->count(),
            'total_games' => $teacher->games()->count(),
            'total_forums' => $teacher->forums()->count(),
            'total_students' => User::where('role', 'student')->count(),
        ];

        // Get recent activities
        $recentQuizAttempts = QuizAttempt::whereHas('quiz', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with(['student', 'quiz'])->latest()->take(5)->get();

        return view('teacher.dashboard', compact('stats', 'recentQuizAttempts'));
    }

    public function studentPerformance()
    {
        $teacher = auth()->user();
        $students = User::where('role', 'student')->get();

        $performanceData = [];

        foreach ($students as $student) {
            // Quiz performance
            $quizAttempts = QuizAttempt::where('student_id', $student->id)
                ->whereHas('quiz', function($query) use ($teacher) {
                    $query->where('teacher_id', $teacher->id);
                })
                ->where('is_completed', true)
                ->get();

            $completedQuizzes = $quizAttempts->count();
            $totalQuizzes = $teacher->quizzes()->where('is_published', true)->count();

            // Average quiz score
            $avgQuizScore = 0;
            if ($quizAttempts->count() > 0) {
                $totalPercentage = 0;
                foreach ($quizAttempts as $attempt) {
                    if ($attempt->total_points > 0) {
                        $totalPercentage += ($attempt->score / $attempt->total_points) * 100;
                    }
                }
                $avgQuizScore = $totalPercentage / $quizAttempts->count();
            }

            // Quiz completion rate
            $quizCompletionRate = $totalQuizzes > 0 ? ($completedQuizzes / $totalQuizzes) * 100 : 0;

            // Game performance
            $gamesPlayed = $student->gameAttempts()
            ->whereHas('game', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)
                  ->where('is_published', true);
            })
            ->get() // get all attempts first
            ->pluck('game_id') // get game IDs
            ->unique() // keep only unique games
            ->count(); // count unique games

            $totalGames = $teacher->games()->where('is_published', true)->count();
            $gamesCompletionRate = $totalGames > 0 ? ($gamesPlayed / $totalGames) * 100 : 0;

            // Course progress (optional, include games too)
            $courseProgress = ($totalQuizzes + $totalGames) > 0 
                ? round(($completedQuizzes + $gamesPlayed) / ($totalQuizzes + $totalGames) * 100) 
                : 0;

            // Check for declining quiz performance
            $decliningPerformance = false;
            if ($quizAttempts->count() >= 3) {
                $recentAttempts = $quizAttempts->sortByDesc('created_at')->take(3);
                $earlierAttempts = $quizAttempts->sortBy('created_at')->take(3);

                $recentAvg = $earlierAvg = 0;

                foreach ($recentAttempts as $attempt) {
                    if ($attempt->total_points > 0) {
                        $recentAvg += ($attempt->score / $attempt->total_points) * 100;
                    }
                }
                $recentAvg = $recentAvg / 3;

                foreach ($earlierAttempts as $attempt) {
                    if ($attempt->total_points > 0) {
                        $earlierAvg += ($attempt->score / $attempt->total_points) * 100;
                    }
                }
                $earlierAvg = $earlierAvg / 3;

                if ($recentAvg < $earlierAvg - 10) {
                    $decliningPerformance = true;
                }
            }

            // Determine if student needs support
            $needsSupport = false;
            $supportReasons = [];

            // Low quiz average
            if ($avgQuizScore < 60 && $completedQuizzes > 0) {
                $needsSupport = true;
                $supportReasons[] = "Low quiz average (" . round($avgQuizScore, 2) . "%)";
            }

            // Low quiz completion rate
            if ($totalQuizzes > 0 && $quizCompletionRate < 50) {
                $needsSupport = true;
                $supportReasons[] = "Low quiz completion (" . round($quizCompletionRate, 2) . "%)";
            }

            // Low game completion rate
            if ($totalGames > 0 && $gamesCompletionRate < 50) {
                $needsSupport = true;
                $supportReasons[] = "Low game completion (" . round($gamesCompletionRate, 2) . "%)";
            }

            // No quiz attempts
            if ($totalQuizzes > 0 && $completedQuizzes == 0) {
                $needsSupport = true;
                $supportReasons[] = "No quiz attempts yet";
            }

            // Declining performance
            if ($decliningPerformance) {
                $needsSupport = true;
                $supportReasons[] = "Performance declining over time";
            }

            $performanceData[] = [
                'student' => $student,
                'avg_quiz_score' => round($avgQuizScore, 2),
                'completed_quizzes' => $completedQuizzes,
                'total_quizzes' => $totalQuizzes,
                'games_played' => $gamesPlayed,
                'total_games' => $totalGames,
                'course_progress' => $courseProgress,
                'quiz_completion_rate' => round($quizCompletionRate, 2),
                'games_completion_rate' => round($gamesCompletionRate, 2),
                'needs_support' => $needsSupport,
                'support_reasons' => $supportReasons,
            ];
        }

        return view('teacher.student-performance', compact('performanceData'));
    }

    public function studentDetail($studentId)
    {
        $teacher = auth()->user();
        $student = User::where('role', 'student')->findOrFail($studentId);

        // Quiz performance
        $quizAttempts = QuizAttempt::where('student_id', $student->id)
            ->whereHas('quiz', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->with('quiz')
            ->orderBy('created_at', 'desc')
            ->get();

        // Game attempts
        $gameAttempts = $student->gameAttempts()
            ->whereHas('game', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->with('game')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.student-detail', compact('student', 'quizAttempts', 'gameAttempts'));
    }

    public function showProfile()
    {
        $user = auth()->user();
        return view('teacher.profile', compact('user'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        return view('teacher.profile-edit', compact('user'));
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

        return redirect()->route('teacher.profile')->with('success', 'Profile updated successfully.');
    }
}