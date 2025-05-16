<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengmasWilayahKegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_desa',
        'program_id',
        'alamat',
        'jumlah_penerima',
        'keterangan',
    ];

    /**
     * Relasi ke model Kecamatan
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'id_desa');
    }

    public function dariProgram(): BelongsTo
    {
        return $this->belongsTo(PengmasRencanaProgramAnggaran::class, 'program_id');
    }

}
