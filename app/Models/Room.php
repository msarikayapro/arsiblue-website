<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name', 'slug', 'short_description', 'long_description',
    'features', 'availability_note',
    'main_image', 'images',
    'is_active', 'sort_order',
])]
class Room extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'features' => 'array',
            'images' => 'array',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
