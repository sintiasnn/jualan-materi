<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RedeemCode extends Model
{
    protected $fillable = [
        'tipe',
        'code',
        'related_id',
        'related_type',
        'discount_amount',
        'max_quota',
        'used_quota',
        'activation_status',
        'expiry_date',
        'redeemed_by',
        'redeemed_at'
    ];

    protected $casts = [
        'activation_status' => 'boolean',
        'expiry_date' => 'datetime',
        'redeemed_at' => 'datetime',
        'discount_amount' => 'decimal:2'
    ];

    // Relationships
    public function redeemedByUser()
    {
        return $this->belongsTo(User::class, 'redeemed_by');
    }

    public function related()
    {
        return $this->morphTo();
    }

    // Quota Methods
    public function hasAvailableQuota(): bool
    {
        return $this->used_quota < $this->max_quota;
    }

    public function getRemainingQuota(): int
    {
        return max(0, $this->max_quota - $this->used_quota);
    }

    public function incrementUsedQuota(): bool
    {
        if (!$this->hasAvailableQuota()) {
            return false;
        }

        $this->used_quota++;
        return $this->save();
    }

    // Validation Methods
    public function isExpired(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        return Carbon::now()->isAfter($this->expiry_date);
    }

    public function isValid(): bool
    {
        return !$this->isExpired() && 
               $this->hasAvailableQuota() && 
               !$this->activation_status;
    }

    public function hasDiscount(): bool
    {
        return $this->discount_amount > 0;
    }

    // Redemption Method
    public function redeem(User $user): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $this->activation_status = true;
        $this->redeemed_by = $user->id;
        $this->redeemed_at = Carbon::now();
        
        return $this->incrementUsedQuota();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('activation_status', false)
                    ->where(function ($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>', Carbon::now());
                    })
                    ->whereRaw('used_quota < max_quota');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<=', Carbon::now());
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('tipe', $type);
    }

    // Helper Methods
    public function getStatusAttribute(): string
    {
        if ($this->isExpired()) {
            return 'expired';
        }
        if (!$this->hasAvailableQuota()) {
            return 'quota_exceeded';
        }
        if ($this->activation_status) {
            return 'used';
        }
        return 'active';
    }

    public function getIsUsableAttribute(): bool
    {
        return $this->isValid();
    }
}