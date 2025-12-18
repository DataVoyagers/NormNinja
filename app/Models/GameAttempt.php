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

    public function getFormattedTimeAttribute()
    {
        if (!$this->time_spent_seconds) return '0:00';
        
        $minutes = floor($this->time_spent_seconds / 60);
        $seconds = $this->time_spent_seconds % 60;
        
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function markAsCompleted($score, $timeSpent)
    {
        $this->update([
            'score' => $score,
            'time_spent_seconds' => $timeSpent,
            'is_completed' => true,
        ]);
    }
}