<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClassContent extends Model
{
    protected $table = 'class_content';
    protected $fillable = [
        'class_id',
        'bidang_id',
        'guru_id',
        'subdomain_id',
        'video_url',
        'file_url',
        'deskripsi',
        'type',
        'kode_materi',
        'nama_materi',
        'kode_submateri',
        'nama_submateri',
    ];

    protected $casts = [
        'video_url' => 'string',
        'file_url' => 'string',
        'type' => 'string',
    ];

    protected $hidden = [
        'type'
    ];

    // Relationships
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(RefBidangList::class, 'bidang_id');
    }

    public function guru(): BelongsTo {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function subdomain(): BelongsTo {
        return $this->belongsTo(Subdomain::class, 'subdomain_id');
    }

    public function content(): HasOne {
        return $this->hasOne(PaketMateri::class, 'materi_id');
    }

    // Scopes
    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopePdfs($query)
    {
        return $query->where('type', 'pdf');
    }

    public function scopeLinks($query)
    {
        return $query->where('type', 'link');
    }

    // Helpers
    public function getTypeLabelAttribute()
    {
        switch ($this->type) {
            case 'video':
                return 'Video';
            case 'pdf':
                return 'PDF';
            case 'link':
                return 'External Link';
            default:
                return 'Unknown';
        }
    }

    // Accessors for Full URLs
    public function getFullVideoUrlAttribute()
    {
        return $this->video_url ? url('storage/' . $this->video_url) : null;
    }

    public function getFullFileUrlAttribute()
    {
        return $this->file_url ? url('storage/' . $this->file_url) : null;
    }
}
