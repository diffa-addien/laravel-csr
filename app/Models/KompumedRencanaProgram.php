<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Import ini

class KompumedRencanaProgram extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'keterangan',
        'tahun_fiskal',
        // Tambahkan kolom baru
        'pengajuan_anggaran',
        'kesepakatan_anggaran',
        'rencana_mulai',
        'rencana_selesai',
    ];

    // Tambahkan casting untuk tanggal
    protected $casts = [
        'rencana_mulai' => 'date',
        'rencana_selesai' => 'date',
    ];
    
    public function dariTahunFiskal(): BelongsTo
    {
        return $this->belongsTo(TahunFiskal::class, 'tahun_fiskal');
    }

    // Definisikan relasi many-to-many ke Strategi
    public function strategis(): BelongsToMany
    {
        return $this->belongsToMany(Strategi::class, 'kompumed_rencana_program_strategi');
    }
}