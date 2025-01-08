<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Mail\Mailables\Content;

class PaketContent extends Model
{
    protected $table = 'paket_content';
    protected $fillable = [
        'paket_id',
        'content_id',
        'activation_date',
        'expired_date',
    ];

    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketList::class, 'paket_id');
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(ClassContent::class, 'content_id');
    }
}
