<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TryoutUserResult extends Model
{
    protected $fillable = [
        'user_id',
        'tryout_id',
        'tryout_attempt_id',
        'final_grade',
        'user_feedback'
    ];

    protected $casts = [
        'final_grade' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tryout(): BelongsTo
    {
        return $this->belongsTo(Tryout::class);
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(TryoutUserAttempt::class, 'tryout_attempt_id');
    }

    // Scopes
    public function scopePassed($query, $passingGrade)
    {
        return $query->where('final_grade', '>=', $passingGrade);
    }

    public function scopeFailed($query, $passingGrade)
    {
        return $query->where('final_grade', '<', $passingGrade);
    }

    // Helpers
    public function isPassed(int $passingGrade): bool
    {
        return $this->final_grade >= $passingGrade;
    }

    public function isFailed(int $passingGrade): bool
    {
        return $this->final_grade < $passingGrade;
    }

    // Accessor for formatted feedback
    public function getFormattedFeedbackAttribute()
    {
        return $this->user_feedback ?? 'No feedback provided';
    }
}
