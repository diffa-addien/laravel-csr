<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StkholderPelaksanaanPpk extends Model
{
    use HasFactory;

    protected $fillable = [
        'kegiatan_id',
        'pelaksana_id',
        'pelaksana_type',
        'coverage',
        'kategori',
        'karakter',
        'biaya',
        'tanggal_pelaksanaan'
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
}
