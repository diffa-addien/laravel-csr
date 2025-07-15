<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnggmntMaterialKomunikasi extends BaseModel
{
    use HasFactory;

    protected $table = 'enggmnt_material_komunikasis';

    protected $fillable = [
        'nama_material',
        'deskripsi',
    ];
}