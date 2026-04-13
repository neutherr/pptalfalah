<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PpdbPeriod extends Model
{
    protected $fillable = ['academic_year', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function waves(): HasMany
    {
        return $this->hasMany(PpdbWave::class)->orderBy('order');
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(PpdbRequirement::class)->orderBy('order');
    }

    public function fees(): HasMany
    {
        return $this->hasMany(PpdbFee::class)->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
