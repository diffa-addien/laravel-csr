<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class StkholderPelaksanaanPpk extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'kegiatan_id',
        'pelaksana_id',
        'pelaksana_type',
        'coverage',
        'kategori',
        'karakter',
        'biaya',
        'tanggal_pelaksanaan',
    ];

    protected $casts = [
        'biaya' => 'integer', // Pastikan tipe data sesuai untuk biaya
        'tanggal_pelaksanaan' => 'date', // Cast ke date untuk tanggal
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(StkholderPerencanaanProgramAnggaran::class, 'kegiatan_id');
    }

    public function pelaksana(): MorphTo
    {
        return $this->morphTo();
    }

    public function getPelaksanaKeyAttribute()
    {
        return match ($this->pelaksana_type) {
            'App\Models\StkholderProfilInternal' => 'int_' . $this->pelaksana_id,
            'App\Models\StkholderProfilExternal' => 'ext_' . $this->pelaksana_id,
            default => null,
        };
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
             ->useDisk('uploads');
    }
}