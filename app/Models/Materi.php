<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materi extends Model
{
    protected $table = 'materi';
    protected $fillable = [
        'subdomain_id',
        'kode_materi',
        'nama_materi',
        'tingkat_kesulitan',
        'reference',
        'video_url',
        'content'
    ];

    public function subdomain(): BelongsTo
    {
        return $this->belongsTo(Subdomain::class, 'subdomain_id');
    }

}
