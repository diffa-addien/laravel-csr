<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;


class StkholderPerencanaanProgramAnggaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'regional_id',
        'program_id',
        'kegiatan',
        'anggaran_pengajuan',
        'anggaran_kesepakatan',
        'keterangan',
    ];

    public function regional(): BelongsTo
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(StkholderPerencanaanPpk::class, 'program_id', 'id');
    }

    public function monevStakeholder(): HasOne
    {
        return $this->hasOne(MonevStakeholder::class, 'anggaran_id');
    }

    public function rincianAnggarans(): HasMany
    {
        return $this->hasMany(StkholderRincianAnggaran::class, 'kegiatan_id');
    }

}
