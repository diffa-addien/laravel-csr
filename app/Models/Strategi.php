<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Import this

class Strategi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'keterangan',
        'tahun_fiskal',
    ];

    public function dariTahunFiskal(): BelongsTo
    {
        return $this->belongsTo(TahunFiskal::class, 'tahun_fiskal');
    }

    // Define the inverse relationship
    public function stkholderPerencanaanPpks(): BelongsToMany
    {
        return $this->belongsToMany(StkholderPerencanaanPpk::class, 'stkholder_perencanaan_ppk_strategi');
    }
}