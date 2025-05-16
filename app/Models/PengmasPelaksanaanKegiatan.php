<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengmasPelaksanaanKegiatan extends Model
{
    use HasFactory;

     protected $fillable = [
        'program_id',
        'jumlah_penerima',
        'anggaran_pelaksanaan',
        'tanggal_pelaksanaan'
    ];

    public function dariProgram(): BelongsTo
    {
        return $this->belongsTo(PengmasRencanaProgramAnggaran::class, 'program_id', 'id');
    }

}
