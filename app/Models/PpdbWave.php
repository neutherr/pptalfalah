<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpdbWave extends Model
{
    protected $fillable = [
        'ppdb_period_id', 'name',
        'registration_start', 'registration_end',
        'test_date', 'announcement_date',
        'is_active', 'order',
    ];

    protected $casts = [
        'registration_start'  => 'date',
        'registration_end'    => 'date',
        'test_date'           => 'date',
        'announcement_date'   => 'date',
        'is_active'           => 'boolean',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(PpdbPeriod::class, 'ppdb_period_id');
    }
}
