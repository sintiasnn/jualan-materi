<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_TUTOR = 'tutor';
    const ROLE_USER = 'user';

    protected $fillable = [
        'name', 
        'gender', 
        'email', 
        'username', 
        'phone_number',
        'avatar', 
        'password', 
        'role', 
        'universitas_id',
        'referral_code', 
        'referred_by', 
        'email_notification',
        'wa_notification', 
        'remember_token', 
        'active_status'
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    protected $casts = [
        'email_notification' => 'boolean',
        'wa_notification' => 'boolean',
        'active_status' => 'boolean',
        'gender' => 'string',
        'role' => 'string'
    ];

    // Role Management
    public static function roles()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_TUTOR,
            self::ROLE_USER,
        ];
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Relationships
    public function universitas(): BelongsTo
    {
        return $this->belongsTo(RefUniversitasList::class, 'universitas_id');
    }

    public function tryoutAttempts(): HasMany
    {
        return $this->hasMany(TryoutUserAttempt::class);
    }

    public function tryoutResults(): HasMany
    {
        return $this->hasMany(TryoutUserResult::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(TransaksiUser::class);
    }

    public function tutorQuestions(): HasMany
    {
        return $this->hasMany(TryoutBankSoal::class, 'tutor_id');
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->attributes['name'];
    }
}
