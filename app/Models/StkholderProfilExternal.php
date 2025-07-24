<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Asumsi Anda memiliki BaseModel, jika tidak, ganti dengan 'Model'
// use Illuminate\Database\Eloquent\Model;

class StkholderProfilExternal extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kategori_stakeholder_id', // Kolom relasi ditambahkan
        'nama',
        'jabatan',
        'alamat',
        'keterangan',
    ];

    /**
     * Get the kategori for the stakeholder profile.
     */
    public function kategoriStakeholder(): BelongsTo
    {
        // Mendefinisikan relasi ke model KategoriStakeholder
        return $this->belongsTo(KategoriStakeholder::class);
    }
}
