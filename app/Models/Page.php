<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['slug', 'title', 'template', 'is_active', 'sort_order'])]
class Page extends Model
{
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function contents(): HasMany
    {
        return $this->hasMany(PageContent::class);
    }

    /**
     * Hızlı section çağırma: $page->getContent('hero_title')
     */
    public function getContent(string $sectionKey, mixed $default = null): mixed
    {
        $content = $this->contents->firstWhere('section_key', $sectionKey);

        if ($content === null || ! $content->is_active) {
            return $default;
        }

        return $content->castedContent();
    }

    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
