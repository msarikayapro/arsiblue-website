<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title', 'subtitle', 'label_text',
    'old_price', 'new_price', 'currency',
    'nights', 'adults', 'children', 'child_age_limit',
    'rooms_left', 'urgency_text',
    'countdown_enabled', 'valid_until',
    'description', 'included_items',
    'hero_image', 'gallery_images',
    'is_active', 'show_on_homepage', 'visible_landings',
    'sort_order',
])]
class Campaign extends Model
{
    /** Spec § 4 — başlangıç oda sayısı varsayımı (progress bar için). */
    private const BASELINE_ROOMS = 10;

    protected function casts(): array
    {
        return [
            'countdown_enabled' => 'boolean',
            'is_active' => 'boolean',
            'show_on_homepage' => 'boolean',
            'valid_until' => 'datetime',
            'included_items' => 'array',
            'gallery_images' => 'array',
            'visible_landings' => 'array',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('valid_until')->orWhere('valid_until', '>', now()));
    }

    public function scopeFeaturedOnHomepage(Builder $query): Builder
    {
        return $query->where('show_on_homepage', true);
    }

    public function priceFormatted(): string
    {
        return priceFormat($this->new_price, $this->currency ?? 'TL');
    }

    public function oldPriceFormatted(): string
    {
        return priceFormat($this->old_price, $this->currency ?? 'TL');
    }

    /**
     * @return array{days:int,hours:int,minutes:int,seconds:int}|null
     */
    public function countdownData(): ?array
    {
        if (! $this->countdown_enabled || $this->valid_until === null) {
            return null;
        }

        $diff = now()->diff($this->valid_until);
        if ($this->valid_until->isPast()) {
            return ['days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 0];
        }

        return [
            'days' => (int) $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
        ];
    }

    public function progressPercent(): int
    {
        if ($this->rooms_left === null) {
            return 0;
        }
        $sold = max(0, self::BASELINE_ROOMS - $this->rooms_left);

        return (int) round(($sold / self::BASELINE_ROOMS) * 100);
    }
}
