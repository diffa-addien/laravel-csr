<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Regional extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_regional',
        'id_provinsi',
        'pimpinan',
        'alamat',
        'visi',
        'misi',
        'tujuan',
    ];

    /**
     * Relasi ke model Kabupaten
     */
    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi');
    }

}
