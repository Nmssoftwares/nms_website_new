<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamCategory extends Model
{
    protected $fillable = ['name', 'slug', 'is_featured', 'is_visible', 'sort_order'];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_visible'  => 'boolean',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class)->orderBy('sort_order')->orderBy('name');
    }
}