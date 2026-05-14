<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Room;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Frontend için sık çağrılan query'leri cache'ler.
 * Spec § 11 TTL'leri:
 *   - settings: 1h (SettingService'te ayrı)
 *   - page contents: 30 min
 *   - aktif kampanya: 10 min (countdown için kısa)
 *   - galeri: 1h
 *   - faq: 1h
 */
class PageCache
{
    public const TTL_PAGE = 1800;     // 30 min
    public const TTL_CAMPAIGN = 600;  // 10 min
    public const TTL_GALLERY = 3600;  // 1h
    public const TTL_FAQS = 3600;     // 1h
    public const TTL_ROOMS = 3600;    // 1h

    public function page(string $slug): ?Page
    {
        return Cache::remember("page.{$slug}", self::TTL_PAGE, function () use ($slug) {
            return Page::bySlug($slug)->with(['contents' => fn ($q) => $q->orderBy('sort_order')])->first();
        });
    }

    public function activeCampaignForHome(): ?Campaign
    {
        return Cache::remember('campaign.active.home', self::TTL_CAMPAIGN, function () {
            return Campaign::active()->featuredOnHomepage()->orderBy('sort_order')->first();
        });
    }

    public function activeCampaignForLanding(string $slug): ?Campaign
    {
        return Cache::remember("campaign.active.{$slug}", self::TTL_CAMPAIGN, function () use ($slug) {
            return Campaign::active()
                ->where(function ($q) use ($slug) { $q->whereJsonContains('visible_landings', $slug); })
                ->orderBy('sort_order')
                ->first()
                ?? Campaign::active()->orderBy('sort_order')->first();
        });
    }

    public function rooms(int $limit = 100): Collection
    {
        return Cache::remember("rooms.list.{$limit}", self::TTL_ROOMS, function () use ($limit) {
            return Room::active()->orderBy('sort_order')->limit($limit)->get();
        });
    }

    public function faqsForPage(string $pageSlug, int $limit = 100): Collection
    {
        return Cache::remember("faqs.{$pageSlug}.{$limit}", self::TTL_FAQS, function () use ($pageSlug, $limit) {
            return Faq::active()->forPage($pageSlug)->orderBy('sort_order')->limit($limit)->get();
        });
    }

    public function galleryByCategory(): Collection
    {
        return Cache::remember('gallery.grouped', self::TTL_GALLERY, function () {
            return Gallery::active()->orderBy('category')->orderBy('sort_order')->get()->groupBy('category');
        });
    }

    /**
     * Admin'de update yapıldığında çağrılır.
     */
    public function invalidate(string $what): void
    {
        match ($what) {
            'pages' => $this->forgetPattern('page.'),
            'campaigns' => $this->forgetPattern('campaign.'),
            'rooms' => $this->forgetPattern('rooms.'),
            'faqs' => $this->forgetPattern('faqs.'),
            'gallery' => $this->forgetPattern('gallery.'),
            'all' => Cache::flush(),
            default => null,
        };
    }

    private function forgetPattern(string $prefix): void
    {
        // Cache driver'a göre değişir. File/Database driver için pattern flush yok,
        // belirli key'leri tek tek forget etmek gerek. Şimdilik tüm cache'i flush.
        Cache::flush();
    }
}
