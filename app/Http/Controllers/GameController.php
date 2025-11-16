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

        return view('games.show', compact('game'));
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
        if (!$game->is_published) {
            abort(403);
        }

        return view('games.play', compact('game'));
    }

    public function saveAttempt(Request $request, Game $game)
    {
        $request->validate([
            'score' => 'required|integer|min:0',
            'time_spent_seconds' => 'required|integer|min:0',
        ]);

        GameAttempt::create([
            'game_id' => $game->id,
            'student_id' => auth()->id(),
            'score' => $request->score,
            'time_spent_seconds' => $request->time_spent_seconds,
            'is_completed' => true,
        ]);

        return response()->json(['message' => 'Game attempt saved successfully']);
    }
}
