<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dealership extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'slug', 'short_description', 'full_description', 'logo', 'cover_image',
        'address', 'city', 'district', 'phone', 'whatsapp', 'website', 'instagram',
        'working_hours', 'latitude', 'longitude', 'status', 'is_featured',
        'seo_title', 'seo_description'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'working_hours' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function averageRating(): float
    {
        return (float) $this->approvedReviews()->avg('rating') ?: 0.0;
    }

    public function reviewsCount(): int
    {
        return $this->approvedReviews()->count();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->where('status', 'approved');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
