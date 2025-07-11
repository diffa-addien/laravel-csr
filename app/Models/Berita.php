<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


class Berita extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasSlug;

    protected $fillable = [
        'kategori_id',
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'sumber',
        'is_published',
        'published_at',
        'penulis',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('judul')
            ->saveSlugsTo('slug');
    }


    // Wajib ada untuk Spatie Media Library
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->useDisk('uploads'); // Menggunakan disk 'uploads' Anda
    }

    // Relasi ke Kategori (One to Many - Inverse)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi ke Tag (Many to Many)
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
