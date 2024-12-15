<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefBidangList extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'bidang_name',
        'deskripsi'
    ];

    // Relationships
    public function classContents(): HasMany
    {
        return $this->hasMany(ClassContent::class, 'bidang_id');
    }

    public function bankSoal(): HasMany
    {
        return $this->hasMany(TryoutBankSoal::class, 'bidang_id');
    }

    // Scopes
    public function scopeByName($query, $name)
    {
        return $query->where('bidang_name', 'like', "%$name%");
    }

    // Helpers
    public function hasClassContents(): bool
    {
        return $this->classContents()->exists();
    }

    public function hasBankSoal(): bool
    {
        return $this->bankSoal()->exists();
    }
}
