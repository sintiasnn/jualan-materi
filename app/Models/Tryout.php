<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tryout extends Model
{
    protected $fillable = [
        'tipe',
        'time_limit',
        'passing_grade',
        'soal_count',
        'pembahasan_url',
        'deskripsi'
    ];

    protected $casts = [
        'tipe' => 'string',
        'time_limit' => 'integer',
        'passing_grade' => 'integer',
        'soal_count' => 'integer',
    ];

    // Relationships
    public function bankSoal(): HasMany
    {
        return $this->hasMany(TryoutBankSoal::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(TryoutUserAttempt::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(TryoutUserResult::class);
    }

    public function pakets(): BelongsToMany
    {
        return $this->belongsToMany(PaketList::class, 'tryout_paket', 'tryout_id', 'paket_id');
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            TryoutUserAttempt::class,
            'tryout_id',
            'id',
            'id',
            'user_id'
        )->distinct();
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('tipe', $type);
    }

    public function scopeWithPassingGradeAbove($query, $grade)
    {
        return $query->where('passing_grade', '>=', $grade);
    }

    // Helpers
    public function hasTimeLimit(): bool
    {
        return $this->time_limit > 0;
    }

    public function requiresPassingGrade(): bool
    {
        return $this->passing_grade > 0;
    }

    // Accessor for human-readable type
    public function getTipeLabelAttribute()
    {
        return ucfirst($this->tipe);
    }
}
