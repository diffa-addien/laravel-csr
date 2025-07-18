<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'pimpinan',
        'ruang_lingkup',
        'alamat',
    ];
}
