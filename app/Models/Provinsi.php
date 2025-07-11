<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Provinsi extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'nama_provinsi',
        'gubernur',
        'regional_id',
    ];

    public function dariRegional(): BelongsTo
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

}
