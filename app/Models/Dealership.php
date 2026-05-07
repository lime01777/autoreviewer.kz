<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dealership extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'legal_name',
        'slug',
        'short_description',
        'full_description',
        'logo',
        'cover_image',
        'address',
        'city',
        'district',
        'phone',
        'whatsapp',
        'email',
        'website',
        'instagram',
        'working_hours',
        'latitude',
        'longitude',
        'status',
        'type',
        'brand',
        'brands',
        'is_official_dealer',
        'country',
        'is_featured',
        'seo_title',
        'seo_description',
        'source_url',
        'source_name',
        'source_checked_at',
        'data_verified',
        'data_status',
        'notes',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_official_dealer' => 'boolean',
        'data_verified' => 'boolean',
        'brands' => 'array',
        'working_hours' => 'array',
        'source_checked_at' => 'datetime',
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

    public function brands()
    {
        return $this->belongsToMany(Brand::class);
    }

    /**
     * Get logo URL with fallback
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo && \Storage::disk('public')->exists($this->logo)) {
            return \Storage::disk('public')->url($this->logo);
        }
        return asset('images/placeholders/logo.svg');
    }

    /**
     * Get cover image URL with fallback
     */
    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image && \Storage::disk('public')->exists($this->cover_image)) {
            return \Storage::disk('public')->url($this->cover_image);
        }
        return asset('images/placeholders/cover.svg');
    }
}
