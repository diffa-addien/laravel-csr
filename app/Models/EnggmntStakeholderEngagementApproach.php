<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnggmntStakeholderEngagementApproach extends BaseModel
{
    use HasFactory;

    protected $table = 'enggmnt_stakeholder_engagement_approaches';

    protected $fillable = [
        'nama_approach',
        'deskripsi',
    ];
}