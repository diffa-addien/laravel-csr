<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bidang extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'kode_bidang',
        'nama_bidang',
        'keterangan',
    ];

    /**
     * Relasi Many-to-Many ke SdgTujuan.
     */
    public function sdgTujuans(): BelongsToMany
    {
        return $this->belongsToMany(SdgTujuan::class, 'bidang_sdg_tujuan');
    }
}