<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['question', 'answer', 'category', 'visible_pages', 'sort_order', 'is_active'])]
class Faq extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'visible_pages' => 'array',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Verilen sayfa slug'ında görünür olan SSS'leri getirir.
     * visible_pages NULL → tüm sayfalarda görünür.
     */
    public function scopeForPage(Builder $query, string $slug): Builder
    {
        return $query->where(function (Builder $q) use ($slug) {
            $q->whereNull('visible_pages')
              ->orWhereJsonContains('visible_pages', $slug);
        });
    }
}
