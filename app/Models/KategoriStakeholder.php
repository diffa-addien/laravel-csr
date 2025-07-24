<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriStakeholder extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * By convention, Laravel would look for 'kategori_stakeholders'.
     * We explicitly define it to match 'kategori_stakeholder'.
     *
     * @var string
     */
    protected $table = 'kategori_stakeholder';

    /**
     * The attributes that are mass assignable.
     *
     * This is a security feature to prevent unwanted data from being saved.
     * Only 'nama_kategori' and 'deskripsi' can be filled using methods like create() or update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];
}
