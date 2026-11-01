<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'role',
        'team_category_id',
        'bio',
        'photo_path',
        'linkedin_url',
        'x_url',
        'email',
        'sort_order',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TeamCategory::class, 'team_category_id');
    }

    public function photoUrl(): string
    {
        return $this->photo_path
            ? asset('storage/' . $this->photo_path)
            : asset('images/employee-placeholder.png');
    }
}