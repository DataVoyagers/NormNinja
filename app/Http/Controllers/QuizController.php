<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        if (auth()->user()->isTeacher()) {
            $quizzes = auth()->user()->quizzes()->latest()->paginate(15);
        } else {
            $quizzes = Quiz::where('is_published', true)->latest()->paginate(15);
        }
        
        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $this->authorize('create', Quiz::class);
        return view('quizzes.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Quiz::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_published' => 'boolean',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after:available_from',
        ]);

        $quiz = Quiz::create([
            'teacher_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'subject' => $request->subject,
            'duration_minutes' => $request->duration_minutes,
            'passing_score' => $request->passing_score,
            'is_published' => $request->boolean('is_published'),
            'available_from' => $request->available_from,
            'available_until' => $request->available_until,
        ]);

        return redirect()->route('quizzes.questions.index', $quiz)->with('success', 'Quiz created successfully. Now add questions.');
    }

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

    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        return view('quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_published' => 'boolean',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after:available_from',
        ]);

        // Check if quiz is being published for the first time
        $wasUnpublished = !$quiz->is_published;
        $willBePublished = $request->boolean('is_published');

        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'subject' => $request->subject,
            'duration_minutes' => $request->duration_minutes,
            'passing_score' => $request->passing_score,
            'is_published' => $request->boolean('is_published'),
            'available_from' => $request->available_from,
            'available_until' => $request->available_until,
        ]);

        return redirect()->route('quizzes.index')->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);
        $quiz->delete();

        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully.');
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

    public function statistics(Quiz $quiz)
    {
        // 1. all students
        $students = User::where('role', 'student')
            ->get()
            ->keyBy('id');

        // 2.best attempt of every student
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->whereNotNull('completed_at')
            ->with('student')
            ->get()
            ->groupBy('student_id')
            ->map(function ($studentAttempts) {
                return $studentAttempts->sortByDesc('percentage')->first();
            });
        // 3. show all students including those who didn't attempt
        $results = $students->map(function ($student) use ($attempts) {

            if (isset($attempts[$student->id])) {
                return $attempts[$student->id];
            }

            // student did not attempt
            return (object) [
                'student'       => $student,
                'percentage'    => null,
                'score'         => null,
                'completed_at'  => null,
                'status'        => 'Not Attempted',
            ];
        });
        // 4. sort by percentage desc, nulls last
        $results = $results->sortByDesc(function ($item) {
            return $item->percentage ?? -1;
        })->values();

        // 5. paginate manually
        $perPage = 20;
        $currentPage = request()->get('page', 1);

        $paginatedResults = new \Illuminate\Pagination\LengthAwarePaginator(
            $results->forPage($currentPage, $perPage),
            $results->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return view('quizzes.quizstatistics', [
            'quiz'     => $quiz,
            'attempts' => $paginatedResults
        ]);
    }
}