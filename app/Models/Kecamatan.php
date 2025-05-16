<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kec',
        'camat',
        'id_kabupaten',
    ];

    /**
     * Relasi ke model Kabupaten
     */
    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'id_kabupaten');
    }

}
