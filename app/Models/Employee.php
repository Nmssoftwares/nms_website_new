<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'role',
        'bio',
        'photo_path',
        'linkedin_url',
        'email',
        'sort_order',
    ];

    public function photoUrl(): string
    {
        return $this->photo_path
            ? asset('storage/' . $this->photo_path)
            : asset('images/employee-placeholder.png');
    }
}