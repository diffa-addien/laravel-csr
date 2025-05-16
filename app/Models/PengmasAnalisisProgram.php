<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengmasAnalisisProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_strategi',
        'id_program',
        'target_hasil',
        'indikator_berhasil',
        'asumsi_or_risiko',
        'pendukung_hasil'
    ];

    public function dariStrategi(): BelongsTo
    {
        return $this->belongsTo(Strategi::class, 'id_strategi');
    }

    public function dariProgram(): BelongsTo
    {
        return $this->belongsTo(PengmasRencanaProgramAnggaran::class, 'id_program');
    }

}
