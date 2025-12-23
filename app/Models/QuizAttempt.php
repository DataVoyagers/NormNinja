<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'student_id',
        'answers',
        'score',
        'total_points',
        'is_completed',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'score' => 'integer',
        'total_points' => 'integer',
        'is_completed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Add this to appends
    protected $appends = ['percentage'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // This calculates percentage dynamically
    public function getPercentageAttribute()
    {
        if ($this->total_points == 0) return 0;
        return round(($this->score / $this->total_points) * 100, 2);
    }

    public function getPassedAttribute()
    {
        if (!$this->quiz) {
            return false; // or null, or anything safe
        }
        
        return $this->percentage >= $this->quiz->passing_score;
    }
}