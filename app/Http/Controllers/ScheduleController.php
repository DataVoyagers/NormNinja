<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'date' => 'required|date',
        'time' => 'nullable',
        'notes' => 'nullable'
    ]);

    Schedule::create([
        'user_id' => $request->user_id,
        'title' => $request->title,
        'date' => $request->date,
        'time' => $request->time,
        'notes' => $request->notes,
    ]);

    return response()->json(['message' => 'Event added successfully']);
}

public function index($user_id)
{
    return Schedule::where('user_id', $user_id)->orderBy('date')->get();
}

}
