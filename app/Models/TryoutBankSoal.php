<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TryoutBankSoal extends Model
{
    public $timestamps = false;

    protected $table = 'tryout_bank_soal';
    
    protected $fillable = [
        'tryout_id',
        'bidang_id',
        'soal_image',
        'soal_content',
        'ops_a',
        'reasoning_a',
        'ops_b',
        'reasoning_b',
        'ops_c',
        'reasoning_c',
        'ops_d',
        'reasoning_d',
        'ops_e',
        'reasoning_e',
        'true_ans',
        'pembahasan_url',
        'tutor_id',
        'approval'
    ];

    protected $casts = [
        'approval' => 'boolean',
        'pembahasan_url' => 'string',
        'soal_image' => 'string',
    ];

    // Relationships
    public function tryout(): BelongsTo
    {
        return $this->belongsTo(Tryout::class);
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(RefBidangList::class, 'bidang_id');
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('approval', true);
    }

    public function scopePending($query)
    {
        return $query->where('approval', false);
    }

    // Helpers
    public function isApproved(): bool
    {
        return $this->approval === true;
    }

    public function isPending(): bool
    {
        return $this->approval === false;
    }

    // Accessor for options
    public function getOptionsAttribute()
    {
        return [
            'A' => $this->ops_a,
            'B' => $this->ops_b,
            'C' => $this->ops_c,
            'D' => $this->ops_d,
            'E' => $this->ops_e,
        ];
    }

    // Default approval status
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (is_null($model->approval)) {
                $model->approval = false;
            }
        });
    }
}
