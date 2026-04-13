<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadFile extends Model
{
    protected $fillable = [
        'name', 'description', 'file_path', 'file_type',
        'category', 'is_active', 'download_count',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'download_count' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Increment download counter
     */
    public function incrementDownloads(): void
    {
        $this->increment('download_count');
    }
}
