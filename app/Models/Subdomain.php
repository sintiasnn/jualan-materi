<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subdomain extends Model
{protected $table = 'subdomain';
    protected $fillable = [
        'domain_code',
        'code',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'code' => 'string',
        'keterangan' => 'string',
        'domain_code' => 'string',
    ];

    public function domain() : BelongsTo{
            return $this->belongsTo(Domain::class, 'domain_code', 'code');
    }
}
