<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['category', 'image_path', 'thumbnail_path', 'alt_text', 'sort_order', 'is_active'])]
class Gallery extends Model
{
    protected $table = 'gallery';

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function categoryModel(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class, 'category', 'slug');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
}
