<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StkholderEngagementPlan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stkholder_engagement_plan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stakeholder_id',   // Diubah dari pelaksana_id
        'stakeholder_type', // Diubah dari pelaksana_type
        'influence_level',
        'interest_level',
        'frequency',
        'channel',
        'info_type',
    ];

    /**
     * Get the parent stakeholder model (internal or external).
     */
    public function stakeholder(): MorphTo // Diubah dari pelaksana()
    {
        return $this->morphTo();
    }
}
