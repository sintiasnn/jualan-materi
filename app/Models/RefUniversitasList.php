<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefUniversitasList extends Model
{
    public $timestamps = false;

    protected $table = 'ref_universitas_list';

    protected $fillable = [
        'universitas_name',
        'singkatan',
        'deskripsi'
    ];

    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'universitas_id');
    }

    // Scopes
    public function scopeByName($query, $name)
    {
        return $query->where('universitas_name', 'like', "%$name%");
    }

    public function scopeByAbbreviation($query, $abbr)
    {
        return $query->where('singkatan', 'like', "%$abbr%");
    }

    // Accessor
    public function getFormattedNameAttribute()
    {
        return "{$this->universitas_name} ({$this->singkatan})";
    }
}
