<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SdgIndikator extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_id',
        'no_indikator',
        'nama_indikator',
        'keterangan'
    ];

    public function dariTarget(): BelongsTo
    {
        return $this->belongsTo(SdgTarget::class, 'target_id');
    }
}
