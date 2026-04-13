<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpdbRequirement extends Model
{
    protected $fillable = ['ppdb_period_id', 'title', 'description', 'order'];

    public function period(): BelongsTo
    {
        return $this->belongsTo(PpdbPeriod::class, 'ppdb_period_id');
    }
}
