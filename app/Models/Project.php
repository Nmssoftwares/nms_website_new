<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'image_path',
        'tech_stack',
        'sort_order',
    ];

    public function imageUrl(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    public function techStackList(): array
    {
        if (!$this->tech_stack) {
            return [];
        }
        return array_map('trim', explode(',', $this->tech_stack));
    }
}