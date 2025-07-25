<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonevKompumed extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'anggaran_id',
        'nilai_evaluasi',
    ];

    public function dariAnggaran(): BelongsTo
    {
        return $this->belongsTo(KompumedKegiatan::class, 'anggaran_id');
    }
}
