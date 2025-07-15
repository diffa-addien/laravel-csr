<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnggmntMetodeEngagement extends BaseModel
{
    use HasFactory;

    protected $table = 'enggmnt_metode_engagements';

    protected $fillable = [
        'nama_metode',
        'deskripsi',
    ];
}