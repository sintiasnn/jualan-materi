<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaketMateri extends Model
{
    protected $table = 'paket_materi';
    protected $fillable = [
        'paket_id',
        'materi_id',
    ];

    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketList::class, 'paket_id');
    }

    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}
