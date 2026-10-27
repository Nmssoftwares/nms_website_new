<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body_html',
        'featured_image',
        'author',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                      ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function featuredImageUrl(): ?string
    {
        return $this->featured_image
            ? asset('storage/' . $this->featured_image)
            : null;
    }
}