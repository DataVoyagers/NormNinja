<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            // Get quiz performance
            $quizAttempts = QuizAttempt::where('student_id', $student->id)
                ->whereHas('quiz', function($query) use ($teacher) {
                    $query->where('teacher_id', $teacher->id);
                })
                ->where('is_completed', true)
                ->get();

            // Calculate average manually
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

            $totalQuizzes = $teacher->quizzes()->where('is_published', true)->count();
            $completedQuizzes = $quizAttempts->count();

            // Get assignment performance
            $assignments = Assignment::where('teacher_id', $teacher->id)
                ->where('is_published', true)
                ->get();

            $totalAssignments = $assignments->count();
            $submittedAssignments = AssignmentSubmission::where('student_id', $student->id)
                ->whereIn('assignment_id', $assignments->pluck('id'))
                ->whereIn('status', ['submitted', 'graded'])
                ->count();

            $missingAssignments = $totalAssignments - $submittedAssignments;

            // Determine if student needs support
            $needsSupport = false;
            $supportReasons = [];

            if ($avgQuizScore < 60 && $completedQuizzes > 0) {
                $needsSupport = true;
                $supportReasons[] = "Low quiz average (" . round($avgQuizScore, 2) . "%)";
            }

            if ($missingAssignments > 0) {
                $needsSupport = true;
                $supportReasons[] = "{$missingAssignments} missing assignments";
            }

            $performanceData[] = [
                'student' => $student,
                'avg_quiz_score' => round($avgQuizScore, 2),
                'completed_quizzes' => $completedQuizzes,
                'total_quizzes' => $totalQuizzes,
                'submitted_assignments' => $submittedAssignments,
                'total_assignments' => $totalAssignments,
                'missing_assignments' => $missingAssignments,
                'needs_support' => $needsSupport,
                'support_reasons' => $supportReasons,
            ];
        }

        // Sort by needs support first, then by average score
        usort($performanceData, function($a, $b) {
            if ($a['needs_support'] != $b['needs_support']) {
                return $b['needs_support'] - $a['needs_support'];
            }
        return $a['avg_quiz_score'] - $b['avg_quiz_score'];
        });

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

        // Assignment submissions
        $assignmentSubmissions = AssignmentSubmission::where('student_id', $student->id)
            ->whereHas('assignment', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->with('assignment')
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

        return view('teacher.student-detail', compact('student', 'quizAttempts', 'assignmentSubmissions', 'gameAttempts'));
    }
}
