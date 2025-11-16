<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\Quiz;
use App\Models\Game;
use App\Models\Forum;
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
        ];

        // Available content
        $availableMaterials = LearningMaterial::where('is_published', true)->latest()->take(5)->get();
        $availableQuizzes = Quiz::where('is_published', true)->latest()->take(5)->get();
        $availableGames = Game::where('is_published', true)->latest()->take(5)->get();

        return view('student.dashboard', compact(
            'stats',
            'recentQuizAttempts',
            'recentGameAttempts',
            'availableMaterials',
            'availableQuizzes',
            'availableGames'
        ));
    }
}