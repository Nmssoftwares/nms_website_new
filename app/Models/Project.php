<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public const TYPES = [
        'software' => 'Software',
        'hardware' => 'Hardware',
    ];

    protected $fillable = [
        'title',
        'category',
        'type',
        'description',
        'image_path',
        'tech_stack',
        'website_url',
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

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? 'Software';
    }
}