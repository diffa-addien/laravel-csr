<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RisTingkatDampak extends Model
{
    use HasFactory;

    protected $table = 'ris_tingkat_dampaks';

    protected $fillable = [
        'tingkat',
        'dampak_risiko',
        'deskripsi',
    ];
}