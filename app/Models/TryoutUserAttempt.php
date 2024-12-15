<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TryoutUserAttempt extends Model
{
    protected $fillable = [
        'tryout_id',
        'user_id',
        'ans_json',
        'attempt_start',
        'attempt_end',
        'time_left',
        'status'
    ];

    protected $casts = [
        'attempt_start' => 'datetime',
        'attempt_end' => 'datetime',
        'ans_json' => 'array',
        'status' => 'string'
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

    public function result(): HasMany
    {
        return $this->hasMany(TryoutUserResult::class, 'tryout_attempt_id');
    }

    // Scopes
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePaused($query)
    {
        return $query->where('status', 'paused');
    }

    // Helpers
    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPaused(): bool
    {
        return $this->status === 'paused';
    }

    // Accessor: Calculate duration of the attempt
    public function getDurationAttribute()
    {
        if ($this->attempt_end && $this->attempt_start) {
            return $this->attempt_end->diffInSeconds($this->attempt_start);
        }
        return null;
    }

    // Default value for status
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (is_null($model->status)) {
                $model->status = 'ongoing';
            }
        });
    }
}
