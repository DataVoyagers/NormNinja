<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'student_id',
        'score',
        'time_spent_seconds',
        'is_completed',
    ];

    protected $casts = [
        'score' => 'integer',
        'time_spent_seconds' => 'integer',
        'is_completed' => 'boolean',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
