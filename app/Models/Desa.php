<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Desa extends BaseModel
{
    use HasFactory; 

    protected $fillable = [
        'nama_desa',
        'kepala_desa',
        'id_kecamatan',
    ];

    /**
     * Relasi ke model Kecamatan
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan');
    }
}
