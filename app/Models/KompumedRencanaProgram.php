<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KompumedRencanaProgram extends Model
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
}
