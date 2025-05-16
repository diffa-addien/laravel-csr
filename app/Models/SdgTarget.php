<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SdgTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'tujuan_id',
        'target',
    ];

    public function dariTujuan(): BelongsTo
    {
        return $this->belongsTo(SdgTujuan::class, 'tujuan_id');
    }
}
