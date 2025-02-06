<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submateri extends Model
{
    protected $table = 'submateri';
    protected $fillable = [
        'materi_id',
        'kode_submateri',
        'nama_submateri',
        'deskripsi',
        'tingkat_kesulitan',
        'reference',
        'video_url'
    ];

    public function materi() : BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}
