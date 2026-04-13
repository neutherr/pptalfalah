<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'description', 'image',
        'cta_primary_label', 'cta_primary_url',
        'cta_secondary_label', 'cta_secondary_url',
        'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
