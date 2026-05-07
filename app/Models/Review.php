<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealership_id', 'user_id', 'author_name', 'author_phone', 'author_email',
        'rating', 'text', 'pros', 'cons', 'status', 'ip_address', 'user_agent', 'admin_comment'
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'approved' => 'Опубликован',
            'pending'  => 'На модерации',
            'rejected' => 'Отклонён',
            default    => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'approved' => 'emerald',
            'pending'  => 'amber',
            'rejected' => 'rose',
            default    => 'slate',
        };
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }

    // ── Relations ─────────────────────────────────────────────────────────────
    public function dealership()
    {
        return $this->belongsTo(Dealership::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
