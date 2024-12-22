<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    protected $table = 'domain';
    protected $fillable = [
        'code',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'code' => 'string',
        'keterangan' => 'string',
    ];

    public function subdomain() : HasMany{
        return $this->hasMany(Subdomain::class, 'domain_id');
    }
}
