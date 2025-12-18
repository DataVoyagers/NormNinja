<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameAttempt;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        if (auth()->user()->isTeacher()) {
            $games = auth()->user()->games()->latest()->paginate(15);
        } else {
            $games = Game::where('is_published', true)->latest()->paginate(15);
        }
        
        return view('games.index', compact('games'));
    }

    public function create()
    {
        $this->authorize('create', Game::class);
        return view('games.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Game::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'game_type' => 'required|in:flashcard,matching,gamified_quiz',
            'subject' => 'nullable|string',
            'game_data' => 'required|array',
            'is_published' => 'boolean',
        ]);

        Game::create([
            'teacher_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'game_type' => $request->game_type,
            'subject' => $request->subject,
            'game_data' => $request->game_data,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('games.index')->with('success', 'Game created successfully.');
    }

    public function show(Game $game)
    {
        if (!$game->is_published && !auth()->user()->isTeacher()) {
            abort(403);
        }

        $userAttempt = null;
        if (auth()->user()->isStudent()) {
            $userAttempt = $game->studentAttempts(auth()->id())->latest()->first();
        }

        return view('games.show', compact('game', 'userAttempt'));
    }

    public function edit(Game $game)
    {
        $this->authorize('update', $game);
        return view('games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $this->authorize('update', $game);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'game_type' => 'required|in:flashcard,matching,gamified_quiz',
            'subject' => 'nullable|string',
            'game_data' => 'required|array',
            'is_published' => 'boolean',
        ]);

        $game->update($request->all());

        return redirect()->route('games.index')->with('success', 'Game updated successfully.');
    }

    public function destroy(Game $game)
    {
        $this->authorize('delete', $game);
        $game->delete();

        return redirect()->route('games.index')->with('success', 'Game deleted successfully.');
    }

    public function play(Game $game)
    {
        if (!$game->is_published && !auth()->user()->isTeacher()) {
            abort(403);
        }

        // Create a new attempt for the student
        if (auth()->user()->isStudent()) {
            $attempt = GameAttempt::create([
                'game_id' => $game->id,
                'student_id' => auth()->id(),
                'is_completed' => false,
            ]);
        }

        return view('games.play', compact('game', 'attempt'));
    }

    public function submitAttempt(Request $request, Game $game)
    {
        $request->validate([
            'attempt_id' => 'required|exists:game_attempts,id',
            'score' => 'required|integer|min:0',
            'time_spent' => 'required|integer|min:0',
        ]);

        $attempt = GameAttempt::findOrFail($request->attempt_id);
        
        if ($attempt->student_id !== auth()->id()) {
            abort(403);
        }

        $attempt->markAsCompleted($request->score, $request->time_spent);

        return redirect()->route('games.results', $attempt)
            ->with('success', 'Game completed successfully!');
    }

    public function results(GameAttempt $attempt)
    {
        if ($attempt->student_id !== auth()->id() && $attempt->game->teacher_id !== auth()->id()) {
            abort(403);
        }

        $game = $attempt->game;
        $allAttempts = $game->studentAttempts($attempt->student_id)->latest()->get();

        return view('games.results', compact('attempt', 'game', 'allAttempts'));
    }

    public function statistics(Game $game)
    {
        $this->authorize('update', $game);

        $attempts = $game->attempts()
            ->where('is_completed', true)
            ->with('student')
            ->latest()
            ->paginate(20);

        $stats = [
            'total_attempts' => $game->attempts()->count(),
            'completed_attempts' => $game->attempts()->where('is_completed', true)->count(),
            'average_score' => $game->averageScore(),
            'completion_rate' => $game->completionRate(),
            'average_time' => $game->attempts()
                ->where('is_completed', true)
                ->avg('time_spent_seconds'),
        ];

        return view('games.statistics', compact('game', 'attempts', 'stats'));
    }
}