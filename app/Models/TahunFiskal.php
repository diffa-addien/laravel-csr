<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunFiskal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_fiskal',
        'tanggal_buka',
        'tanggal_tutup',
        'is_active',
    ];

}
