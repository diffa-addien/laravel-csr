<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManajemenIsu extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'nama_isu',
        'deskripsi',
    ];

}