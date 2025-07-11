<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StkholderAnalisis extends BaseModel
{
    use HasFactory;

    protected $table = 'stkholder_analisis';

    protected $fillable = [
        'kegiatan_id',
        'deskripsi',
        'target_hasil',
        'indikator_berhasil',
        'asumsi_or_risiko',
        'pendukung_hasil'
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(StkholderPerencanaanProgramAnggaran::class, 'kegiatan_id');
    }
}
