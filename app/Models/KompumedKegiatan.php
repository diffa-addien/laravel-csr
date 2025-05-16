<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KompumedKegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'regional_id',
        'program_id',
        'nama',
        'keterangan',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function regional(): BelongsTo
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(KompumedRencanaProgram::class, 'program_id', 'id');
    }

    public function monevKompumed(): HasOne
    {
        return $this->hasOne(monevKompumed::class, 'anggaran_id');
    }

    public function rincianAnggarans(): HasMany
    {
        return $this->hasMany(KompumedKegiatanAnggaran::class, 'kegiatan_id');
    }

    public function getTotalAnggaranAttribute()
    {
        return $this->rincianAnggarans->sum(function ($item) {
            return $item->biaya * $item->kuantitas;
        });
    }
}
