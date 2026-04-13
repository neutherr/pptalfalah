<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'attachment',
        'is_pinned', 'is_published', 'published_at',
    ];

    protected $casts = [
        'is_pinned'    => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
