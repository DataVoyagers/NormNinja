<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'game_type',
        'subject',
        'game_data',
        'is_published',
    ];

    protected $casts = [
        'game_data' => 'array',
        'is_published' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function attempts()
    {
        return $this->hasMany(GameAttempt::class);
    }

    public function studentAttempts($studentId)
    {
        return $this->attempts()->where('student_id', $studentId);
    }

    public function averageScore()
    {
        return $this->attempts()
            ->where('is_completed', true)
            ->avg('score');
    }

    public function completionRate()
    {
        $total = $this->attempts()->count();
        if ($total == 0) return 0;
        
        $completed = $this->attempts()->where('is_completed', true)->count();
        return round(($completed / $total) * 100, 2);
    }
}
