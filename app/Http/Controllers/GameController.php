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

        $game = Game::create([
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

        // Check if game is being published for the first time
        $wasUnpublished = !$game->is_published;
        $willBePublished = $request->boolean('is_published');

        $game->update($request->all());

        // Notify students if game is being published for the first time
        if ($wasUnpublished && $willBePublished) {
            $this->notifyStudentsAboutNewContent('game', $game);
        }

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

    public function leaderboard(Request $request, Game $game = null)
    {
        // If a specific game is provided, show leaderboard for that game only
        if ($game) {
            if (!$game->is_published && !auth()->user()->isTeacher()) {
                abort(403);
            }

            // Get best attempt per student for this specific game
            $leaderboard = GameAttempt::where('game_id', $game->id)
                ->where('is_completed', true)
                ->with('student')
                ->selectRaw('student_id, MAX(score) as best_score, MIN(time_spent_seconds) as best_time')
                ->groupBy('student_id')
                ->orderByDesc('best_score')
                ->orderBy('best_time')
                ->get()
                ->map(function ($attempt, $index) use ($game) {
                    return [
                        'rank' => $index + 1,
                        'student' => $attempt->student,
                        'score' => $attempt->best_score,
                        'time' => $attempt->best_time,
                    ];
                });

            return view('games.leaderboard', compact('game', 'leaderboard'));
        }

        // Overall leaderboard across all games
        $leaderboard = GameAttempt::where('is_completed', true)
            ->with('student')
            ->selectRaw('student_id, SUM(score) as total_score, COUNT(*) as games_played, AVG(score) as avg_score')
            ->groupBy('student_id')
            ->orderByDesc('total_score')
            ->orderByDesc('games_played')
            ->get()
            ->map(function ($attempt, $index) {
                return [
                    'rank' => $index + 1,
                    'student' => $attempt->student,
                    'total_score' => $attempt->total_score,
                    'games_played' => $attempt->games_played,
                    'avg_score' => round($attempt->avg_score, 1),
                ];
            });

        // Get list of published games for filter
        $games = Game::where('is_published', true)->orderBy('title')->get();

        return view('games.leaderboard', compact('leaderboard', 'games', 'game'));
    }
}