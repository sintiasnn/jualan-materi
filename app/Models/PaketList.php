<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaketList extends Model
{
    protected $table = 'paket_list';
    protected $fillable = [
        'image',
        'nama_paket',
        'audience',
        'tipe',
        'harga',
        'discount',
        'tier',
        'deskripsi',
        'active_status'
    ];

    protected $casts = [
        'harga' => 'decimal:0',
        'discount' => 'decimal:0',
        'active_status' => 'boolean',
        'audience' => 'string',
        'tipe' => 'string',
        'tier' => 'string'
    ];

    // Add pivot timestamps to relationships
    public function tryouts(): BelongsToMany
    {
        return $this->belongsToMany(Tryout::class, 'tryout_paket', 'paket_id', 'tryout_id')
            ->withPivot(['activation_date', 'expiration_date', 'order'])
            ->withTimestamps();
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classes::class, 'class_paket', 'paket_id', 'class_id')
            ->withPivot(['activation_date', 'expiration_date', 'order'])
            ->withTimestamps();
    }

    public function materi(): BelongsToMany
    {
        return $this->belongsToMany(Materi::class, 'paket_materi', 'paket_id', 'materi_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(TransaksiUser::class, 'paket_id');
    }

    // Your existing scopes
    public function scopeActive($query)
    {
        return $query->where('active_status', true);
    }

    public function scopeFree($query)
    {
        return $query->where('tier', 'free');
    }

    public function scopePaid($query)
    {
        return $query->where('tier', 'paid');
    }

    // Add scope for audience type
    public function scopeForAudience($query, string $audience)
    {
        return $query->where('audience', $audience);
    }

    // Your existing helpers
    public function isClass(): bool
    {
        return $this->tipe === 'class';
    }

    public function isTryout(): bool
    {
        return $this->tipe === 'tryout';
    }

    public function isFree(): bool
    {
        return $this->tier === 'free';
    }

    public function isPaid(): bool
    {
        return $this->tier === 'paid';
    }

    // Your existing accessor
    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Add validation rules as a static property
    public static array $rules = [
        'nama_paket' => 'required|string|max:255',
        'audience' => 'required|in:ukmppd,aipki,preklinik,koas,osce',
        'tipe' => 'required|in:class,tryout',
        'harga' => 'required|numeric|min:0',
        'tier' => 'required|in:free,paid',
        'deskripsi' => 'nullable|string',
        'active_status' => 'boolean'
    ];

    // Helper to check if paket is active and within dates
    public function isAccessible(): bool
    {
        if (!$this->active_status) {
            return false;
        }

        $now = now();

        if ($this->isClass()) {
            return $this->classes()
                ->wherePivot('activation_date', '<=', $now)
                ->wherePivot(function ($query) use ($now) {
                    $query->whereNull('expiration_date')
                          ->orWhere('expiration_date', '>', $now);
                })
                ->exists();
        }

        if ($this->isTryout()) {
            return $this->tryouts()
                ->wherePivot('activation_date', '<=', $now)
                ->wherePivot(function ($query) use ($now) {
                    $query->whereNull('expiration_date')
                          ->orWhere('expiration_date', '>', $now);
                })
                ->exists();
        }

        return false;
    }
}
