<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengmasRencanaProgramAnggaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'regional_id',
        'nama_program',
        'jenis_program',
        'keterangan',
        'pengajuan_anggaran',
        'kesepakatan_anggaran',
        'rencana_mulai',
        'rencana_selesai',
        'output',
        'output_unit',
        'tujuan_utama',
        'tujuan_khusus',
        'justifikasi',
        'keterangan',
        'tahun_fiskal',
    ];
    public function dariTahunFiskal(): BelongsTo
    {
        return $this->belongsTo(TahunFiskal::class, 'tahun_fiskal');
    }
    
    public function regional(): BelongsTo
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    public function monevPengmas(): HasOne
    {
        return $this->hasOne(MonevPengmas::class, 'anggaran_id');
    }

    public function rincianAnggarans(): HasMany
    {
        return $this->hasMany(PengmasWilayahKegiatan::class, 'program_id');
    }

}
