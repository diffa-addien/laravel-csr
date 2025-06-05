<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PengmasPelaksanaanKegiatan extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

     protected $fillable = [
        'program_id',
        'jumlah_penerima',
        'anggaran_pelaksanaan',
        'tanggal_pelaksanaan'
    ];

    public function dariProgram(): BelongsTo
    {
        return $this->belongsTo(PengmasRencanaProgramAnggaran::class, 'program_id', 'id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
             ->useDisk('uploads');
    }

}
