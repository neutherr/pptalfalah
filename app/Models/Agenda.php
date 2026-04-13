<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'location',
        'start_datetime', 'end_datetime',
        'organizer', 'image', 'is_published',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime'   => 'datetime',
        'is_published'   => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
