<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'dealership_id', 'user_id', 'author_name', 'author_phone', 'author_email',
        'rating', 'text', 'pros', 'cons', 'status', 'ip_address', 'user_agent', 'admin_comment'
    ];

    public function dealership()
    {
        return $this->belongsTo(Dealership::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
