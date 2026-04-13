<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpdbFee extends Model
{
    protected $fillable = ['ppdb_period_id', 'name', 'amount', 'notes', 'order'];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(PpdbPeriod::class, 'ppdb_period_id');
    }

    /**
     * Get amount formatted as Indonesian Rupiah
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
