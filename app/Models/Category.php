<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'description'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function dealerships()
    {
        return $this->belongsToMany(Dealership::class);
    }
}
