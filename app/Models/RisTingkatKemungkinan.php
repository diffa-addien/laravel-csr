<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RisTingkatKemungkinan extends BaseModel
{
    use HasFactory;

    protected $table = 'ris_tingkat_kemungkinans';

    protected $fillable = [
        'tingkat',
        'kemungkinan_risiko',
        'deskripsi',
        'kriteria_kualitatif',
        'kriteria_kuantitatif',
    ];
}