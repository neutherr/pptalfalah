<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'title', 'slug', 'subtitle', 'description', 'content', 'image',
        'icon', 'icon_bg_color', 'bullet_points',
        'order', 'is_active',
    ];

    protected $casts = [
        'bullet_points' => 'array',
        'is_active'     => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
