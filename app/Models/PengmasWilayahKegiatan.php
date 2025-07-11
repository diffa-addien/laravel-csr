<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengmasWilayahKegiatan extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id_desa',
        'program_id',
        'nama_kegiatan',
        'bidang_id',
        'anggaran',
        'alamat',
        'rencana_mulai',
        'rencana_selesai',
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

    public function dariBidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'bidang_id', 'id');
    }

    // In App\Models\PengmasWilayahKegiatan.php
    public function pelaksanaan(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\PengmasPelaksanaanKegiatan::class, 'kegiatan_id');
    }

}
