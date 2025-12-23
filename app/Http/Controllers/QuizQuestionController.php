<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    public function index(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $questions = $quiz->questions()->orderBy('order')->get();
        return view('quizzes.questions.index', compact('quiz', 'questions'));
    }

    public function create(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        return view('quizzes.questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $request->validate([
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'question_text' => 'required|string',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'correct_answer' => 'required|string',
            'points' => 'required|integer|min:1',
        ]);

        $maxOrder = $quiz->questions()->max('order') ?? 0;

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_type' => $request->question_type,
            'question_text' => $request->question_text,
            'options' => $request->question_type === 'multiple_choice' ? $request->options : null,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points,
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('quizzes.questions.index', $quiz)->with('success', 'Question added successfully.');
    }

    public function edit(Quiz $quiz, QuizQuestion $question)
    {
        $this->authorize('update', $quiz);
        
        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        return view('quizzes.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, QuizQuestion $question)
    {
        $this->authorize('update', $quiz);

        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        $request->validate([
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'question_text' => 'required|string',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'correct_answer' => 'required|string',
            'points' => 'required|integer|min:1',
        ]);

        $question->update([
            'question_type' => $request->question_type,
            'question_text' => $request->question_text,
            'options' => $request->question_type === 'multiple_choice' ? $request->options : null,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points,
        ]);

        return redirect()->route('quizzes.questions.index', $quiz)->with('success', 'Question updated successfully.');
    }

    public function destroy(Quiz $quiz, QuizQuestion $question)
    {
        $this->authorize('update', $quiz);

        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        $question->delete();

        // Reorder remaining questions
        $questions = $quiz->questions()->orderBy('order')->get();
        foreach ($questions as $index => $q) {
            $q->update(['order' => $index + 1]);
        }

        return redirect()->route('quizzes.questions.index', $quiz)->with('success', 'Question deleted successfully.');
    }
}
