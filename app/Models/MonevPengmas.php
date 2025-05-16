<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class MonevPengmas extends Model
{
    use HasFactory;

    protected $table = 'monev_pengmas';
    
    protected $fillable = [
        'anggaran_id',
        'nilai_evaluasi',
    ];

    public function dariAnggaran(): BelongsTo
    {
        return $this->belongsTo(StkholderPerencanaanProgramAnggaran::class, 'anggaran_id');
    }
}
