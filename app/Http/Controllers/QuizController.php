<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use App\Models\Reminder;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        if (!$quiz->is_published && !auth()->user()->isTeacher()) {
            abort(403);
        }

        $userAttempts = null;
        if (auth()->user()->isStudent()) {
            $userAttempts = $quiz->attempts()->where('student_id', auth()->id())->get();
        }

        return view('quizzes.show', compact('quiz', 'userAttempts'));
    }

    // Quiz taking functionality
    public function start(Quiz $quiz)
    {
        if (!$quiz->is_published) {
            abort(403);
        }

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => auth()->id(),
            'answers' => [],
            'started_at' => now(),
        ]);

        Reminder::create([
            'user_id' => auth()->id(),
            'text' => 'Complete Quiz: ' . $quiz->title . ' - Don\'t forget to complete this quiz!',
            'is_completed' => false,
        ]);

        return redirect()->route('quizzes.take', ['quiz' => $quiz, 'attempt' => $attempt]);
    }

    public function take(Quiz $quiz, QuizAttempt $attempt)
    {
        if ($attempt->student_id !== auth()->id() || $attempt->is_completed) {
            abort(403);
        }

        $questions = $quiz->questions()->orderBy('order')->get();

        return view('quizzes.take', compact('quiz', 'attempt', 'questions'));
    }

    public function submit(Request $request, Quiz $quiz, QuizAttempt $attempt)
    {
        if ($attempt->student_id !== auth()->id() || $attempt->is_completed) {
            abort(403);
        }

        $answers = $request->input('answers', []);
        $score = 0;
        $totalPoints = 0;

        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            $studentAnswer = $answers[$question->id] ?? null;

            if ($question->question_type === 'multiple_choice' || $question->question_type === 'true_false') {
                if ($studentAnswer == $question->correct_answer) {
                    $score += $question->points;
                }
            } elseif ($question->question_type === 'short_answer') {
                if (strtolower(trim($studentAnswer)) == strtolower(trim($question->correct_answer))) {
                    $score += $question->points;
                }
            }
        }

        $attempt->update([
            'answers' => $answers,
            'score' => $score,
            'total_points' => $totalPoints,
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        return redirect()->route('quizzes.result', ['quiz' => $quiz, 'attempt' => $attempt])->with('success', 'Quiz submitted successfully!');
    }

    public function result(Quiz $quiz, QuizAttempt $attempt)
    {
        if ($attempt->student_id !== auth()->id()) {
            abort(403);
        }

        return view('quizzes.result', compact('quiz', 'attempt'));
    }
}
