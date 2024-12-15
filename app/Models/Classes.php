<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classes extends Model
{
    protected $fillable = [
        'title',
        'deskripsi'
    ];

    protected $with = ['contents', 'pakets'];

    // Relationships
    public function contents(): HasMany
    {
        return $this->hasMany(ClassContent::class, 'class_id');
    }

    public function pakets(): BelongsToMany
    {
        return $this->belongsToMany(PaketList::class, 'class_paket', 'class_id', 'paket_id');
    }

    // Scopes
    public function scopeWithContent($query)
    {
        return $query->has('contents');
    }

    public function scopeWithoutContent($query)
    {
        return $query->doesntHave('contents');
    }

    // Helpers
    public function hasContent(): bool
    {
        return $this->contents()->exists();
    }

    // Accessor for shortened description
    public function getShortDeskripsiAttribute()
    {
        return \Str::limit($this->deskripsi, 50, '...');
    }
}
