<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Glossary extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'nama_glossary',
        'keterangan',
    ];
}
