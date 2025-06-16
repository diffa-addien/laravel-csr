<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OrgProfil extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
             ->useDisk('uploads');
    }

}
