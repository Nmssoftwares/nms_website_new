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
        'is_locked',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Safety net: fixed members can never be deleted, from anywhere in the app.
        static::deleting(function (Employee $employee) {
            if ($employee->is_locked) {
                return false;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TeamCategory::class, 'team_category_id');
    }

    public function photoUrl(): string
    {
        if (! $this->photo_path) {
            return asset('images/employee-placeholder.png');
        }

        // Seeded photos live in public/images/..., admin uploads live in storage.
        if (str_starts_with($this->photo_path, 'images/')) {
            return asset($this->photo_path);
        }

        return asset('storage/' . $this->photo_path);
    }
}