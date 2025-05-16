<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class StkholderRencanaKerja extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'kegiatan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(StkholderPerencanaanProgramAnggaran::class, 'kegiatan_id', 'id');
    }
}
