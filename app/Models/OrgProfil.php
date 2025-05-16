<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrgProfil extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'nama',
        'pimpinan',
        'lv1',
        'lv2',
        'lv3',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrgPenugasan::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(OrgPenugasan::class, 'parent_id');
    }

}
