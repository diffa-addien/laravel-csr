<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StkholderRincianAnggaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kegiatan_id',
        'pelaksana_id',
        'frekuensi',
        'frekuensi_unit',
        'biaya',
        'kuantitas',
        'kuantitas_unit',
        'keterangan',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(StkholderPerencanaanProgramAnggaran::class, 'kegiatan_id', 'id');
    }

    public function pelaksana(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'pelaksana_id');
    }

    public function getPelaksanaNamesAttribute(): array
    {
        if (!$this->pelaksana_id) {
            return [];
        }

        $ids = explode(',', $this->pelaksana_id);

        return \App\Models\Vendor::whereIn('id', $ids)->pluck('nama')->toArray();
    }
}
