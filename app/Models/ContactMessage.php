<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    // read_at is NOT fillable on purpose: visitors can never set it from the public form.
    protected $fillable = ['name', 'email', 'phone', 'service', 'message'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    /** Messages nobody has opened yet. */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }
}