<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class KompumedPelaksanaanKegiatan extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'kegiatan_id',
        'deskripsi',
        'frekuensi',
        'frekuensi_unit',
        'biaya',
        'kuantitas',
        'kuantitas_unit',
        'tanggal_pelaksanaan'
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(KompumedKegiatan::class, 'kegiatan_id', 'id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
             ->useDisk('uploads');
    }
}
