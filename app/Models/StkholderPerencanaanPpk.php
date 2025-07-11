<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Import this

class StkholderPerencanaanPpk extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'keterangan',
        'tahun_fiskal',
        // Add new columns here
        'pengajuan_anggaran',
        'kesepakatan_anggaran',
        'rencana_mulai',
        'rencana_selesai',
    ];

    // Add this cast for dates
    protected $casts = [
        'rencana_mulai' => 'date',
        'rencana_selesai' => 'date',
    ];

    public function dariTahunFiskal(): BelongsTo
    {
        return $this->belongsTo(TahunFiskal::class, 'tahun_fiskal');
    }

    // Define the many-to-many relationship
    public function strategis(): BelongsToMany
    {
        return $this->belongsToMany(Strategi::class, 'stkholder_perencanaan_ppk_strategi');
    }
}