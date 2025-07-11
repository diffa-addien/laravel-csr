<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RisTingkatRisiko extends BaseModel
{
    use HasFactory;

    protected $table = 'ris_tingkat_risikos';

    protected $fillable = [
        'tingkat_risiko',
        'deskripsi',
        'petunjuk',
    ];
}