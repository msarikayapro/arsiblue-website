<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['page_id', 'section_key', 'content_type', 'content', 'sort_order', 'is_active'])]
class PageContent extends Model
{
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function castedContent(): mixed
    {
        if ($this->content === null) {
            return null;
        }

        return match ($this->content_type) {
            'json' => json_decode($this->content, true),
            default => $this->content,
        };
    }
}
