<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RedeemCode extends Model
{
    protected $fillable = [
        'tipe',
        'code',
        'activation_status',
        'expiry_date'
    ];

    protected $casts = [
        'activation_status' => 'boolean',
        'tipe' => 'string',
        'expiry_date' => 'datetime',
    ];

    // Relationships
    public function transactions(): HasMany
    {
        return $this->hasMany(TransaksiUser::class, 'redeem_code_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('activation_status', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('activation_status', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('tipe', $type);
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->activation_status === true;
    }

    public function isInactive(): bool
    {
        return $this->activation_status === false;
    }

    // Accessor for status label
    public function getStatusLabelAttribute()
    {
        return $this->activation_status ? 'Active' : 'Inactive';
    }
}
