<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KompumedKegiatanAnggaran extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'kegiatan_id',
        'deskripsi',
        'frekuensi',
        'frekuensi_unit',
        'biaya',
        'kuantitas',
        'kuantitas_unit',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(KompumedKegiatan::class, 'kegiatan_id', 'id');
    }
}
