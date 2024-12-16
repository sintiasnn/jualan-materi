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
        'expiry_date',
        'discount_amount',
        'related_id',
        'related_type',
        'redeemed_by',
        'redeemed_at',
    ];

    protected $casts = [
        'activation_status' => 'boolean',
        'tipe' => 'string',
        'expiry_date' => 'datetime',
        'discount_amount' => 'decimal:2', // Format discount as decimal with 2 decimal points
        'redeemed_at' => 'datetime',
    ];

    // Relationships
    public function transactions(): HasMany
    {
        return $this->hasMany(TransaksiUser::class, 'redeem_code_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('activation_status', true)
                     ->where('expiry_date', '>=', now()); // Only active codes that haven't expired
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
        return $this->activation_status === true && $this->expiry_date >= now();
    }

    public function isInactive(): bool
    {
        return $this->activation_status === false || $this->expiry_date < now();
    }

    // Accessor for status label
    public function getStatusLabelAttribute()
    {
        return $this->activation_status ? 'Active' : 'Inactive';
    }

    // Check if the code has expired
    public function isExpired(): bool
    {
        return $this->expiry_date < now();
    }

    // Helper to check if the code has a discount
    public function hasDiscount(): bool
    {
        return !is_null($this->discount_amount) && $this->discount_amount > 0;
    }
}
