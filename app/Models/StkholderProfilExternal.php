<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StkholderProfilExternal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jabatan',
        'alamat',
        'keterangan',
    ];
}
