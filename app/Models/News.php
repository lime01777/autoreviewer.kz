<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'image', 'status', 'published_at',
        'seo_title', 'seo_description'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Get image URL with fallback
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && \Storage::disk('public')->exists($this->image)) {
            return \Storage::disk('public')->url($this->image);
        }
        return asset('images/placeholders/news.svg');
    }
}
